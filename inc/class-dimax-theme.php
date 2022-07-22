<?php
/**
 * Dimax init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dimax
 */

namespace Dimax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


final class Theme {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance = null;

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
		require_once get_template_directory() . '/inc/class-dimax-autoload.php';
		require_once get_template_directory() . '/inc/libs/class-mobile_detect.php';
		if ( is_admin() ) {
			require_once get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
		}
	}

	/**
	 * Hooks to init
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Before init action.
		do_action( 'before_dimax_init' );

		// Setup
		$this->get( 'autoload' );
		$this->get( 'setup' );
		$this->get( 'widgets' );

		$this->get( 'woocommerce' );

		$this->get( 'mobile' );

		$this->get( 'maintenance' );

		// Header
		$this->get( 'preloader' );
		$this->get( 'topbar' );
		$this->get( 'header' );
		$this->get( 'campaigns' );

		// Page Header
		$this->get( 'page_header' );
		$this->get( 'breadcrumbs' );

		// Layout & Style
		$this->get( 'layout' );
		$this->get( 'dynamic_css' );

		// Comments
		$this->get( 'comments' );

		//Footer
		$this->get( 'footer' );

		// Modules
		$this->get( 'search_ajax' );
		$this->get( 'newsletter' );

		// Templates
		$this->get( 'page' );

		$this->get( 'blog' );

		// Admin
		$this->get( 'admin' );

		// Init action.
		do_action( 'after_dimax_init' );

	}

	/**
	 * Get Dimax Class.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'woocommerce':
				if ( class_exists( 'WooCommerce' ) ) {
					return WooCommerce::instance();
				}
				break;

			case 'options':
				return Options::instance();
				break;

			case 'search_ajax':
				return \Dimax\Modules\Search_Ajax::instance();
				break;

			case 'newsletter':
				return \Dimax\Modules\Newsletter_Popup::instance();
				break;

			case 'mobile':
				if ( Helper::is_mobile() ) {
					return \Dimax\Mobile::instance();
				}
				break;

			default :
				$class = ucwords( $class );
				$class = "\Dimax\\" . $class;
				if ( class_exists( $class ) ) {
					return $class::instance();
				}
				break;
		}

	}


	/**
	 * Setup the theme global variable.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup_prop( $args = array() ) {
		$default = array(
			'modals' => array(),
		);

		if ( isset( $GLOBALS['dimax'] ) ) {
			$default = array_merge( $default, $GLOBALS['dimax'] );
		}

		$GLOBALS['dimax'] = wp_parse_args( $args, $default );
	}

	/**
	 * Get a propery from the global variable.
	 *
	 * @param string $prop Prop to get.
	 * @param string $default Default if the prop does not exist.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_prop( $prop, $default = '' ) {
		self::setup_prop(); // Ensure the global variable is setup.

		return isset( $GLOBALS['dimax'], $GLOBALS['dimax'][ $prop ] ) ? $GLOBALS['dimax'][ $prop ] : $default;
	}

	/**
	 * Sets a property in the global variable.
	 *
	 * @param string $prop Prop to set.
	 * @param string $value Value to set.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_prop( $prop, $value = '' ) {
		if ( ! isset( $GLOBALS['dimax'] ) ) {
			self::setup_prop();
		}

		if ( ! isset( $GLOBALS['dimax'][ $prop ] ) ) {
			$GLOBALS['dimax'][ $prop ] = $value;

			return;
		}

		if ( is_array( $GLOBALS['dimax'][ $prop ] ) ) {
			if ( is_array( $value ) ) {
				$GLOBALS['dimax'][ $prop ] = array_merge( $GLOBALS['dimax'][ $prop ], $value );
			} else {
				$GLOBALS['dimax'][ $prop ][] = $value;
				array_unique( $GLOBALS['dimax'][ $prop ] );
			}
		} else {
			$GLOBALS['dimax'][ $prop ] = $value;
		}
	}
}
