/*
	scripts.js
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	Copyright: (c) 2015 Mamekko, http://welcustom.net
*/

jQuery(function($){
	/* Incriments button */
	/* Source: http://jigowatt.co.uk/blog/magento-quantity-increments-jquery-edition/ */
	$('.item-quant').append('<input type="button" value="+" id="add1" class="plus">').prepend('<input type="button" value="-" id="minus1" class="minus">');
	$('.plus').click(function(){
		var currentVal = parseInt(jQuery(this).prev('.skuquantity').val());
		if (!currentVal || currentVal=='' || currentVal == 'NaN') currentVal = 0;
		$(this).prev('.skuquantity').val(currentVal + 1); 
	});
	$('.minus').click(function(){
		var currentVal = parseInt(jQuery(this).next('.skuquantity').val());
		if (currentVal == 'NaN') currentVal = 0;
		if (currentVal > 0) {
			$(this).next('.skuquantity').val(currentVal - 1);
		}
	});
	
	/* flexslider */
	$('#carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 166,
		itemMargin: 5,
		asNavFor: '#slider'
	});
	$('#slider').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		sync: "#carousel"
	});
	$('#slider li:first-child img').attr('itemprop', 'image');
	
	/* Review system */
	$('#respond h3').click(function(){
		$(this).toggleClass('open');
		$('.comment-form').slideToggle();
	});
	$('.comment-reply-link').click(function(){
		$('.comment-form').slideDown();
	});
});