<?php
/*
Plugin Name: Shopp Featured Images
Description: Adds post thumbnail support to Shopp products. Where a thumbnail is specified this will be used automatically as the product cover image instead of using the first product gallery image, as is the default behaviour. The product post thumbnails can also be used elsewhere at will.
Version: 1.1.1
Author: Shoppdeveloper.com
Author URI: http://www.shoppdeveloper.com
Contributors: Shoppdeveloper.com, Barry Hughs
License: GPL3

    "Shopp Featured Images" adds post thumbnail support to Shopp products
    Copyright (C) 2012 Barry Hughes, (C) 2017 Shoppdeveloper.com Team

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class ShoppFeaturedImages {
	public $dir = '';
	public $url = '';
	public $posttype;

	public static function init() {
			add_action('plugins_loaded', array(__CLASS__, 'loader'));
	}


	public static function loader() {
		if ( defined('SHOPP_VERSION') and version_compare(SHOPP_VERSION, '1.2') >= 0)
			new ShoppFeaturedImages;
	}


	public function __construct() {
		$this->dir         = dirname(__FILE__);
		$this->url         = WP_PLUGIN_URL . '/' . basename($this->dir);
		$this->posttype    = ShoppProduct::posttype();
		$productEditorHook = 'add_meta_boxes_' . $this->posttype;

		add_action('registered_post_type', array($this, 'addThumbnailSupport'));
		add_action($productEditorHook, array($this, 'addJSSupport'));
		add_action($productEditorHook, array($this, 'registerMetabox'));
		add_filter('shopp_tag_product_coverimage', array($this, 'autoThumbnailSupport'), 10, 3);
	}


	public function addThumbnailSupport ( $postType, $args = null ) {
		if ( $postType === $this->posttype )
			add_post_type_support($postType, 'thumbnail');
	}


	public function addJSSupport() {
		add_thickbox();
		wp_enqueue_script('post');
		wp_enqueue_script('media-upload');
	}


	public function registerMetabox() {
		add_meta_box('postimagediv', __('Featured Image'),
			array($this, 'renderMetabox'),
			$this->posttype, 'side', 'low');
	}


	public function renderMetabox ( $product ) {
		$id = $product->id;
		// If no productid is present, it is a new product
		// Create a draft product
		if ( empty($id) )
			$this->createProduct();
		$thumbnailID = get_post_meta($id, '_thumbnail_id', true);
		echo _wp_post_thumbnail_html($thumbnailID, $id);
	}


	/**
	 * Replaces the cover image with the product's featured image, if specified.
	 * This automatic replacement can be turned off by filtering
	 * shopp_auto_featured_thumb and returning false from the filter.
	 * @param string        $result  The output
	 * @param array         $options The options
	 * @param ShoppCustomer $O       The working object
	 * @return string       The (link of the) featured image of the product
	 */
	public function autoThumbnailSupport ( $result, $options = null, $Object = null ) {
		if (apply_filters('shopp_auto_featured_thumb', true) === false)
			return $result;

		$postID     = shopp('product.get-id');
		$thumb_size = 'post-thumbnail';
		$thumb_attr = array();

		if ( isset($options) ) { 
			$arr = array('size' => 'size', 'setting' => 'setting', 'property' => 'property');
			foreach ($options as $option => $value) {
				if ( isset($arr[ $option ]) ) $$option = $value;
				else $thumb_attr[ $option ] = $value; 
			}

		}

		if ( isset($setting) ) $size = $setting;
		if ( isset($size) ) {
			if ( 'original' == $size ) $thumb_size = 'full';
			else {
				$ImageSetting = new ImageSetting($size, 'name');
				if ( ! empty($ImageSetting->value) ) {
					$thumb_size = array();
					$thumb_size[] = $ImageSetting->value->width;
					$thumb_size[] = $ImageSetting->value->height;
				}
			}
		}

		$thumb_size  = apply_filters('shopp_featured_img_size', $thumb_size);
		$thumb_attr  = apply_filters('shopp_featured_img_attr', $thumb_attr);

		if ( isset($property) && 'url' == $property )
			$thumbnail = the_post_thumbnail_url( $thumb_size );
		else
			$thumbnail = get_the_post_thumbnail($postID, $thumb_size, $thumb_attr);

		if ( empty($thumbnail) ) return $result;
		else return $thumbnail;
	}

	/**
	 * Adds a draft product and loads 
	 * the product editor page of the draft product
	 */
	public function createProduct () {
		$data = array(
    			'name'    => 'no name', 
    			'slug'    => ' ',
    			'publish' => array('flag' => false),
			);
		$product  = shopp_add_product($data);  
		$adminurl = admin_url('admin.php');
		$redirect = add_query_arg( array('page' => 'shopp-products', 'id' => $product->id), $adminurl );
		echo "<script>window.location.replace('$redirect');</script>";
	}
}

ShoppFeaturedImages::init();