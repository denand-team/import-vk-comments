(function( $ ) {
	'use strict';
})( jQuery );
$ = jQuery;

function getpages() {

	jQuery.ajax({
		type: "GET",
		url: import_vk_comments_settings.ajax_pages_uri,

		success: function (data) {
			function echotoadmin (index, value) {
				jQuery("#server-results").delay(1000).append(index + ': ' + value.url + '<br>');
			}

			jQuery.each(data, function (index, value) {
				setTimeout(echotoadmin(index, value), 500, 2);
			});
		}
	});

}



function doTheThing() {
	return new Promise((resolve, reject) => {
	  $.ajax({
		url:  import_vk_comments_settings.ajax_pages_uri,
		type: 'GET',
		data: {
		  key: 'value',
		},
		success: function (data) {
		  resolve(data)
		},
		error: function (error) {
		  reject(error)
		},
	  })
	})
  }
  

  function checkPromise() {
	// Получаем страницы с комментариями
		getpages()
		console.log('Get VK Pages List')

		document.getElementById("progresstitle").style.display = "block";
		document.getElementById("myItem1").style.display = "block";
		document.getElementById("resultslog").style.display = "block";
	doTheThing()
	.then((data) => {
		var bar1 = new ldBar("#myItem1");
		var percent = 0;
		var total = data.length;
		var increment = 100/total;
	jQuery.each(data, function (index, value) {
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				console.log('Do task', import_vk_comments_settings.ajax_get_comments + value['url'])
				percent = percent + increment;

				// Получаем данные и записываем
				jQuery.ajax({
					type: "GET",
					url: import_vk_comments_settings.ajax_get_comments + value['url'],
			
					success: function (data) {
						console.log('All is OK', import_vk_comments_settings.ajax_get_comments + value['url'])
					},
					error: function (error) {
						console.log('Error', import_vk_comments_settings.ajax_get_comments + value['url'])
					}
				});


				bar1.set(percent)
				resolve(data)
			}, index*2700)
		})
	})
	})
	.catch((error) => {
	  console.log(error)
	})
  }