/*
	use-ValidationEngine.js
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	Copyright: (c) 2014 Mamekko, http://welcustom.net
*/

jQuery(function($){
	$("#name1,#name2,#zipcode,#customer_pref,#member_pref,#address1,#address2,#tel").addClass("validate[required]");
	$("#mailaddress1").addClass("validate[required,custom[email]]");
	$("#mailaddress2").addClass("validate[required,equals[mailaddress1]]");
	$("#password2").addClass("validate[equals[password1]]");
	$("input[name='offer[payment_name]']").addClass("validate[required,radio]");
	$("#customer_pref option:first-child,#member_pref option:first-child").val("");
	$("form").validationEngine();
	$(".back_cart_button, .back_to_customer_button").click(function(){
	$("form").validationEngine('hideAll');
	$("form").validationEngine('detach');
		return true;
		});
	$("#tel,#fax,.used_point,.quantity").attr("type","tel");
});