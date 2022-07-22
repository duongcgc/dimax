<?php
/**
 * Product With Thumbnails template hooks.
 *
 * @package Dimax
 */

namespace Dimax\WooCommerce\Elements;

use Dimax\WooCommerce\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product With Thumbnails
 */
class Product_With_Thumbnails {
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
		$featured_icons = (array) \Dimax\Helper::get_option( 'product_loop_featured_icons' );

		add_action( 'dimax_product_with_thumbnails_woocommerce_before_loop', array( $this, 'product_wrapper_open' ), 5 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_before_loop', array( $this, 'product_loop_thumbnail' ), 10 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_before_loop_summary', array( $this, 'summary_wrapper_open' ), 5 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_loop_summary', array( $this, 'template_loop_top_open' ), 5 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_loop_summary', array( Helper::instance(), 'product_taxonomy' ), 15 );
		add_action( 'dimax_product_with_thumbnails_woocommerce_loop_summary', array( Helper::instance(), 'product_loop_title' ), 20 );
		add_action( 'dimax_product_with_thumbnails_woocommerce_loop_summary', array( $this, 'template_loop_cat_title_close' ), 30 );
		add_action( 'dimax_product_with_thumbnails_woocommerce_loop_summary', 'woocommerce_template_loop_price', 40 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_loop_summary', array( $this, 'template_loop_top_close' ), 100 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop_summary', array( $this, 'product_loop_buttons_open'	), 5 );
		add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop_summary', 'woocommerce_template_loop_add_to_cart', 10 );

		if ( in_array( 'qview', $featured_icons ) ) {
			add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop_summary', array( Helper::instance(), 'quick_view_button'	), 20 );
		}
		if ( in_array( 'wlist', $featured_icons ) ) {
			add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop_summary', array( Helper::instance(), 'wishlist_button' ), 30 );
		}

		add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop_summary', array( $this, 'product_loop_buttons_close' ), 90 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop_summary', array( $this, 'summary_wrapper_close' ), 100 );

		add_action( 'dimax_product_with_thumbnails_woocommerce_after_loop', array( $this, 'product_wrapper_close' ), 1000 );

		add_action( 'dimax_product_with_thumbnails_loop_thumbnail', array( $this, 'product_loop_inner_buttons_open' ) );

		if ( in_array( 'qview', $featured_icons ) ) {
			add_action( 'dimax_product_with_thumbnails_loop_thumbnail', array( Helper::instance(), 'quick_view_button'	) );
		}
		if ( in_array( 'wlist', $featured_icons ) ) {
			add_action( 'dimax_product_with_thumbnails_loop_thumbnail', array( Helper::instance(), 'wishlist_button' ) );
		}

		add_action( 'dimax_product_with_thumbnails_loop_thumbnail', array( $this, 'product_loop_inner_buttons_close' ) );
	}

	/**
	 * Open product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_open() {
		echo '<div class="product-inner">';
	}

	/**
	 * Close product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Open product summary wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function summary_wrapper_open() {
		echo '<div class="product-summary">';
	}

	/**
	 * Close product summary wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function summary_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Open product loop top.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function template_loop_top_open() {
		echo '<div class="product-loop__top"><div class="product-loop__cat-title">';
	}

		/**
	 * Close product Loop  cat & title.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function template_loop_cat_title_close() {
		echo '</div>';
	}

	/**
	 * Close product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function template_loop_top_close() {
		echo '</div>';
	}

	/**
	 * Open product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_buttons_open() {
		echo '<div class="product-loop__buttons">';
	}

	/**
	 * Close product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_buttons_close() {
		echo '</div>';
	}

	/**
	 * WooCommerce template thumbnail
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_thumbnail() {
		global $product;
		echo '<div class="product-thumbnail">';

			woocommerce_template_loop_product_link_open();
				echo '<span class="product-thumbnail__image">';
				echo $product->get_image('dimax-products-with-thumbnails-large');
				echo '</span>';
				$image_ids = $product->get_gallery_image_ids();
				if ( ! empty( $image_ids ) ) {
					echo '<span class="product-thumbnail__gallery">';
						for( $i=0; $i<3; $i++ ) {
							if( ! empty( $image_ids[$i] ) ) {
								echo wp_get_attachment_image( $image_ids[$i], 'dimax-products-with-thumbnails-small', false, array( 'class' => 'attachment-dimax-products-with-thumbnails-small size-dimax-products-with-thumbnails-small' ) );
							}
						}
					echo '</span>';
				}
			woocommerce_template_loop_product_link_close();
			do_action('dimax_product_with_thumbnails_loop_thumbnail');
		echo '</div>';
	}

	/**
	 * Open product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_inner_buttons_open() {
		echo '<div class="product-loop-inner__buttons">';
	}

	/**
	 * Close product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_inner_buttons_close() {
		echo '</div>';
	}
}
