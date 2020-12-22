<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://showspy.ru
 * @since      1.0.0
 *
 * @package    Import_Vk_Comments
 * @subpackage Import_Vk_Comments/admin/partials
 */


?>

    <h1>Настройка плагина Import VK Comments</h1>
    <h2>Шаг 1. Задайте данные виджета</h2>
    Задайте данные вашего виджета комментариев. Получить их можно <a href="https://vk.com/apps?act=manage" target="_blank">на этой странице</a> нажав на кнопку <b>"Редактировать"</b> вашего приложения.
<?php
echo '
 <form method="post" action="options.php">';
settings_fields( 'import_vk_comments_settings' );
do_settings_sections( 'import_vk_comments_settings' );
echo '<table class="form-table">
				<tr valign="top"><th scope="row">ID виджета комментариев:</th><td><input type="number" name="ivc_client_id" value="'.get_option( 'ivc_client_id' ).'" required/><br><em>widget_id</em></td></tr>
                <tr valign="top"><th scope="row">Сервисный ключ доступа:</th><td><input type="text" name="ivc_access_token" value="'.get_option( 'ivc_access_token' ).'" required/><br><em>service_token</em></td></tr>
				</td></tr>				
				</table>';
submit_button();
echo '</form>';

if (get_option( 'ivc_client_id' ) !== '' && get_option( 'ivc_access_token' ) !== '') {
	echo '<h2>Шаг 2. Получить и импортировать комментарии</h2>';

	echo '<p>После нажатия кнопки не закрывайте страницу до завершения операции, понадобится время из-за ограничения по количеству запросов к VK API. Комментарии появятся в статусе "Ожидающие".</p>';
	echo '<a href="#" class="button" onclick="checkPromise()" />Импортировать комментарии</a>';

	echo '<h3 id="progresstitle" style="display: none">Прогресс задачи</h3><div class="ldBar" data-preset="line" id="myItem1" style="display: none" ></div>';
	echo '<br><div id="resultslog" style="display: none"><h4>Полученные страницы:</h4><pre id="server-results"></pre></div>';
} else {
    echo '<h2>Шаг 2. Импорт появится после заполнения и сохранения полей</h2>';
    echo '<img src="'.plugin_dir_url( __DIR__ ).'img/instructions.jpg" alt="Инструкции авторизации">';
}

