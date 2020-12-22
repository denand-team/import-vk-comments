<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://showspy.ru
 * @since      1.0.0
 *
 * @package    Import_Vk_Comments
 * @subpackage Import_Vk_Comments/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Import_Vk_Comments
 * @subpackage Import_Vk_Comments/admin
 * @author     DenAnd <denandteam@gmail.com>
 */


class Import_Vk_Comments_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add admin menu
		add_action( 'admin_menu', 				array( $this, 'admin_menu' ) );

		add_action('wp_ajax_get_pages', array( $this, 'get_pages'));
		add_action('wp_ajax_get_comments', array( $this, 'get_comments'));
	}

	/**
	 * Adds the menu item.
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */
	function admin_menu() {
		// Vars.
		$slug = 'import-vk-comments';
		$cap = 'manage_options';

		// Add menu item
		add_menu_page( 'Импорт комментариев ВК', 'VK Комментарии', 'manage_options', $slug, array( $this, 'import_vk_comments_init'), 'dashicons-share-alt2', '25.025' );
		add_action( 'admin_init', array( $this, 'import_vk_comments_settings')); // Обновление значений
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Import_Vk_Comments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Import_Vk_Comments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/import-vk-comments-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Внутренности страницы в админке
	 *
	 * @since    1.0.0
	 */
	public function import_vk_comments_init() {
		require_once plugin_dir_path( __DIR__ ).'admin/partials/import-vk-comments-admin-display.php';
	}

	/**
	 * Регистрация значений плагина
	 *
	 * @since    1.0.0
	 */
	public function import_vk_comments_settings() {
		register_setting( 'import_vk_comments_settings', 'ivc_access_token', 'esc_attr' );
		register_setting( 'import_vk_comments_settings', 'ivc_client_id', 'esc_attr' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Импорт комментариев
		$importVkCommentsAjaxArray = array(
			'access_token' => get_option( 'ivc_access_token' ),
			'client_id' => get_option( 'ivc_client_id' ),
			'ajax_pages_uri' => get_site_url().'/wp-admin/admin-ajax.php?action=get_pages',
			'ajax_get_comments' => get_site_url().'/wp-admin/admin-ajax.php?action=get_comments&url=',
			'vk_api_path' => plugin_dir_path( __DIR__ ).'vendor/autoload.php'
		);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/import-vk-comments-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-loading-bar', plugin_dir_url( __FILE__ ) . 'js/loading-bar.js', true);

		wp_localize_script($this->plugin_name, 'import_vk_comments_settings', $importVkCommentsAjaxArray);
	}

	/**
	 * Функция получения страниц с комментариями VK
	 *
	 * @throws \VK\Exceptions\VKApiException
	 * @throws \VK\Exceptions\VKClientException
	 *
	 * @since    1.0.0
	 */
	public function get_pages() {
		require_once (plugin_dir_path( __DIR__ ).'vendor/autoload.php');

		$access_token = get_option( 'ivc_access_token' );
		$vk = new VK\Client\VKApiClient();

		$request = [
			'widget_api_id' => [get_option( 'ivc_client_id' )],
			'period' => ['alltime'],
			'count' => [200],
			'order' => ['date'],
		];

		$pages = $vk->widgets()->getPages($access_token, $request);

		$comments = array();
		$key = 0;
		foreach ($pages['pages'] as $page) {
			if($page['comments']['count'] > 0) {
				$comments[$key]['url'] = esc_url_raw($page['url']);
				$comments[$key]['count'] = sanitize_text_field($page['comments']['count']);
				$key++;
			}
		}

		// Если на сайте более 200 страниц c комментариями
		if (($counter = $pages['count'] / 200) > 1) {
			$counter = round ($counter) + 1;
			for ($i = 1; $i <= $counter; $i++) {
				$request['offset'] = $request['offset'] + 200;

				$pages = $vk->widgets()->getPages($access_token, $request);
				foreach ($pages['pages'] as $page) {
					if($page['comments']['count'] > 0) {
						$comments[$key]['url'] = esc_url_raw($page['url']);
						$comments[$key]['count'] = sanitize_text_field($page['comments']['count']);
						$key++;
					}
				}
			}
		}
		update_option( 'ivc_pages', json_encode($comments));
		wp_send_json($comments);
	}


	/**
	 * Функция получения комментариев к URI
	 *
	 * @return array|int
	 * @throws \VK\Exceptions\VKApiException
	 * @throws \VK\Exceptions\VKClientException
	 *
	 * @since    1.0.0
	 */
	public function get_comments() {
		require_once (plugin_dir_path( __DIR__ ).'vendor/autoload.php');

		$url = esc_url_raw($_GET['url']);
		// Получаем ID записи в Wordpress
		$postid = url_to_postid($url);

		// Если не найден ID поста Wordpress, выходим из функции
		if ($postid == 0) {
			return 0;
		}

		// Инициализация клиента
		$access_token = get_option( 'ivc_access_token' );
		$vk = new VK\Client\VKApiClient();

		$request = [
			'widget_api_id' => [get_option( 'ivc_client_id' )],
			'url' => $url,
			'count' => [200],
			'order' => ['date'],
		];

		// Получение списка комментариев
		$comments = $vk->widgets()->getComments($access_token, $request);

		// Получаем ID комментаторов
		$authors = array();

		foreach ($comments['posts'] as $comment) {
			$authors[] = $comment['from_id'];
		}

		$requestnames = [
			'user_ids' => implode(',', $authors),
			'name_case' => 'Nom',
			'lang' => 0
		];

		// Получаем данные по ID комментаторов
		$usernames = $vk->users()->get($access_token, $requestnames);

		// Собираем все в общем массиве
		$postcomments = array();

		foreach ($comments['posts'] as $key => $comment) {
			$postcomments[$key]['comment_type'] = 'comment';
			$postcomments[$key]['comment_post_ID'] = $postid;
			$postcomments[$key]['comment_content'] = sanitize_textarea_field($comment['text']);
			$postcomments[$key]['comment_date'] = date('Y-m-d H:i:s', sanitize_text_field($comment['date']));
			$postcomments[$key]['comment_date_gmt'] = date('Y-m-d H:i:s', (sanitize_text_field($comment['date']) - 60*60*3));
			$postcomments[$key]['comment_approved'] = '0';
			$postcomments[$key]['comment_author_email'] = sanitize_text_field($comment['from_id']).'@vk.com';
			$postcomments[$key]['comment_author_url'] = '';
			$postcomments[$key]['comment_parent'] = '0';
			$postcomments[$key]['user_id'] = '0';
			$postcomments[$key]['comment_author_IP'] = '127.0.0.1';

			// Собираем имя комментатора
			foreach ($usernames as $username) {
				if($comment['from_id'] == $username['id']) {
					if($username['first_name'] == 'DELETED') {
						$altnames = array('User', 'Commentator', 'Anonymous', 'Person', 'Unnamed', 'Anon');
						$postcomments[$key]['comment_author'] = $altnames[array_rand($altnames)].sanitize_text_field($comment['from_id']);
					} else {
						$postcomments[$key]['comment_author'] = sanitize_text_field($username['first_name'].' '.$username['last_name']);
					}
					break;
				}
			}
		}
		// Записываем в БД
		$this->import_comments($postcomments);
		return $postcomments;
	}

	/**
	 * Функция импортирования комментариев в БД WordPress
	 *
	 * @param $postcomments - список комментариев записи, отформатированный для записи
	 *
	 * @since    1.0.0
	 */
	public function import_comments ($postcomments) {
		foreach ($postcomments as $comment) {
			wp_insert_comment( wp_slash($comment) );
		}
	}
}