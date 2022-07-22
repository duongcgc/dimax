<?php
/**
 * Dimax Widgets functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dimax
 */

namespace Dimax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dimax after setup theme
 */
class Widgets {
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
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
	}

	/**
	 * Register widget area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function widgets_init() {
		$sidebars = array(
			'blog-sidebar'           => esc_html__( 'Blog Sidebar', 'dimax' ),
			'header-bar'             => esc_html__( 'Header Bar', 'dimax' ),
		);

		// Register footer sidebars
		for ( $i = 1; $i <= 5; $i ++ ) {
			$sidebars["footer-$i"] = esc_html__( 'Footer', 'dimax' ) . " $i";
		}

		// Register sidebars
		foreach ( $sidebars as $id => $name ) {
			register_sidebar(
				array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				)
			);
		}

	}
}
