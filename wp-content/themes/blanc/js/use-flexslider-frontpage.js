/*
	use-flexslider-frontpage.js
	Adding Flexslider fucntion for front page.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	Copyright: (c) 2015 Mamekko, http://welcustom.net
*/
jQuery(function($) {
	$(window).load(function(){
		$('.flexslider').flexslider({
			animation: "slide",
			slideshow: false,
		});
		$('.flex-viewport').after('<div class="flex-left-opacity"></div><div class="flex-right-opacity"></div>');
	});
	
	//Get slider images' height
	//Thanks to: http://stackoverflow.com/questions/5106243/how-do-i-get-background-image-size-in-jquery
	
	var image_url = $('.flexslider li:first-child').css('background-image'),
	image;

	// Remove url() or in case of Chrome url("")
	image_url = image_url.match(/^url\("?(.+?)"?\)$/);

	if (image_url[1]) {
		image_url = image_url[1];
		image = new Image();

		// just in case it is not already loaded
		$(image).load(function () {
			$('.flexslider li').css('height', image.height);
		});
		
		image.src = image_url;
	}
});