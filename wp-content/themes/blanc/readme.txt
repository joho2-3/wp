***************************************************
	Blanc WordPress theme for Welcart e-Commerce
***************************************************
[Usage Guide]
Author：Mamekko
Last updated：2015.03.02

-----contents-----
0. Preparation
1. For those who won't use Welcart e-commerce plugin
2. Front page
3. Archive page
4. Single item page
5. Usage of Plugins and fonts
-----------------
This Theme is distributed under GNU GPLv3 lisence. For your usage, you are considered to have agreed GPLv3 lisence terms.
http://www.gnu.org/licenses/gpl.html

[0. Preparation]
* Categories <--for Welcart users-->
[Items]If you use Welcart e-Commerce plugin, sort items under 'item' category (or its child categories) which is automatically set by welcart plugin.

[Posts] for blog shouldn't be categorized into categories for items NOTE: when Welcar e-Commerce plugins is activated, categories for items won't be shown in blog post editor.

* Disable usces_cart.css <--for Welcart users-->
Since v2.0, this theme dosn't use usces_cart.css which is automatically read by Welcart e-commcerce plugin. Go [Welcart Shop]>[System Setting], then check "To disable usces_cart.css".

* Default thumbnail for blog archive page
On blog archive page, this theme shows thumbnails automatically from 'post-thumbnail', otherwise one of attached images. But when any post-thumbnail or attachment is found, no-image.jpg (in /img/ folder)  is shown as the thumbnail of the post. We recommend you to replace no-image.jpg with your own.

[1. For those who won't use Welcart e-commerce plugin]
If you won't use Welcart e-commerce plugin, you can delete following files;

/css/validationEngine.jquery.css
/js/jquery.validationEngine.js
/js/scripts-item.js
/js/use-validationEngine.js
/js/langeages (folder)
/wc_templates (folder)
archive-item.php
breadcrumbs-item.php
comments-item.php
page-usces-cart.php
related-item.php
search-item.php
thimbnail-box.php
welcart.css

And also, you can delete some of the codes which are mentioned 'for Welcart' in funcions.php or other files.

[2. Front page]
From version2.0, default image isn't set for header image. You can upload your own image file for header image from "Appearance">"Header". It is better the image width is over 1200px.
All uploaded images are shown as slider images in Front Page.

<--following 2 'Box'es are for Welcart users-->
*'What's new' Box
New items released within 15 days are shown in randam order. If you want to change the period, open front-page.php then change the number of "strtotime('-15 days')" on line 55. But it would be overwrote when the theme is upgraded. It's recommended to make a child theme to change the templates.

*'Recommended' Box
Marchandises in 'itemreco' are shown in randam order.

[3. Archive page]
*Thumbnails
Priority of the thumbnails: 1. post-thumbnail, 2. one of the attached images, then 3. no-image.jpg in /img/ folder.

*Number of posts to show
- 'item' category : 12 posts
- other categories (such as blog posts) : the number you set at 'Blog pages show at most' in 'Reading Settings'.

*Tags <--for Welcart users-->
It is recommendable not to share same tags between 'item' posts and 'blog' posts. When a same tag is tagged for 2 of those, blog posts archive template is applied.

[4. Single item page <--for Welcart users-->]
*Images of marchandises
In single page, the width of images is around 670px. It's better to prepare images which width is at least 670px.

*Review comments
To use review system, go item-edit-page and show 'Discussion' from screen options, then check 'Allow comments.' in Discussion box.
It is recommended to use approval system ( which is in [settings]>[discussion] ) in order to prevent spams.

[5. Usage of Plugins]
This theme has functions of 'breadcrumbs', 'pagination', 'Light box' and 'related items'(for Welcart).
Please avoid usage of those kinds of plugins because it can cause troubles.

*When icons of Swipebox (lightbox) or FontAwesome don't appear
Check if your server allow SVG. If it doesn't, add SVG permission codes on .htaccess by yourselves.

<--for Welcart users-->
functions.php detects front-end language for jQuery Validation Engine. In case of the languages which jQuery Validation Engine doesn't support, it will show in English.