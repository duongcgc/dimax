<?php
/**
 * Mobile functions and definitions.
 *
 * @package Dimax
 */

namespace Dimax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile initial
 *
 */
class Mobile {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->get( 'general' );
		$this->get( 'header' );
		$this->get( 'footer' );
		$this->get( 'navigation_bar' );
		$this->get( 'catalog' );
		$this->get( 'product_loop' );
		$this->get( 'single_product' );
		$this->get( 'page_header' );
		$this->get( 'blog' );
	}

	/**
	 * Get Mobile Class Init.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'general':
				return \Dimax\Mobile\General::instance();
				break;
			case 'header':
				return \Dimax\Mobile\Header::instance();
				break;
			case 'footer':
				return \Dimax\Mobile\Footer::instance();
				break;
			case 'navigation_bar':
				if( Helper::get_option('mobile_navigation_bar') != 'none' ) {
					return \Dimax\Mobile\Navigation_Bar::instance();
				}
				break;
			case 'catalog':
				return \Dimax\Mobile\Catalog::instance();
				break;
			case 'product_loop':
				return \Dimax\Mobile\Product_Loop::instance();
				break;
			case 'single_product':
				return \Dimax\Mobile\Single_Product::instance();
				break;
			case 'page_header':
				return \Dimax\Mobile\Page_Header::instance();
				break;
			case 'blog':
				return \Dimax\Mobile\Blog::instance();
				break;
		}
	}
}
