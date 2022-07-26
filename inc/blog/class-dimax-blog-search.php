<?php
/**
 * Search functions and definitions.
 *
 * @package Dimax
 */

namespace Dimax\Blog;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Search initial
 *
 */
class Search {
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
		add_action( 'dimax_before_close_search_content', array( new \Dimax\Helper, 'posts_found' ) );
		add_action( 'dimax_before_close_search_content', array( new \Dimax\Helper, 'load_pagination' ), 30 );

		add_action( 'dimax_before_search_loop', array( $this, 'open_post_list' ), 40 );
		add_action( 'dimax_after_search_loop', array( $this, 'close_post_list' ), 10 );
	}


	/**
	 * Open post list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_post_list() {
		$classes = ' blog-wrapper--listing';
		echo '<div class="dimax-posts__list ' . esc_attr( $classes ) . ' ">';
	}

	/**
	 * Close post list
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_post_list() {
		echo '</div>';
	}
}

