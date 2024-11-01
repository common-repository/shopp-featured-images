=== Shopp Featured Images ===
Contributors: Shoppdeveloper.com, Barry Hughs
Author: Shoppdeveloper.com
Author URI: http://www.shoppdeveloper.com
Donate link: http://www.shoppdeveloper.com/downloads/virtual-cup-of-coffee/
Tags: ecommerce, post thumbs, featured images, shopp, webshop
Requires at least: 3.4.2
Tested up to: 4.7.3
Stable tag: 1.1.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds post thumbnails to Shopp products - providing an additional image that can be used independently of the
product gallery images.

== Description ==

Adding post thumbnail (or featured image) support to Shopp means products can now have a new representative
image that is completely independent of the product gallery. One simple use case for this is using a thumbnail
on the category pages and maintaining a completely different set of gallery images on the product page.

* Post thumbnails are regular images uploaded using WordPress's media tools - just as you would expect! - they
can be used from any theme template, not unlike regular post thumbs.
* You are not limited in how you use these new images - a range of filters are built in to Shopp Featured Images to afford further control.

Originated from "Featured Images for Shopp" plugin by Barry Hughes and Chris Jumonville.

== Installation ==

Shopp Featured Images can be installed like any other plugin.

* Upload the `shopp-sfi` directory to the `wp-content/plugins` directory
* Or install it using the tools built in to WordPress's Install Plugins page
* Activate!

== Frequently Asked Questions ==

= Are product thumbnails stored in the database? =

No. Even if you have configured Shopp to store its images in the database, Shopp Featured Images simply
pulls in WordPress's own media handling tools so any post thumbs will be saved as regular files and can be
re-used across posts/products.

= Can I change the post thumb size! =

Of course.

= I don't want my coverimages replaced by featured images

Add this code to your functions.php
add_filter('shopp_auto_featured_thumb', 'do_not_replace_coverimages');
function do_not_replace_coverimages() { return false; } 

== Screenshots ==

No screenshots are currently available.

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* Public release
* Minor fix during the registered_post_type callback

= 1.1 =
* First version by Shoppdeveloper.com Team
* Updated code for Shopp 1.3.x usage
* Removed Post_ID insertion code
* Added support for image settings
* Added support for coverimage property=url setting
* Convert Shopp 'original' to post thumbnail 'full'

== Upgrade Notice ==

This is a simple plugin and you should be able to simply write over the old version during any future
updated.