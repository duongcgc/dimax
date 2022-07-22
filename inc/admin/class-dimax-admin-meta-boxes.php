<?php
/**
 * Meta boxes functions
 *
 * @package Dimax
 */

namespace Dimax\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Meta boxes initial
 *
 */
class Meta_Boxes {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'meta_box_scripts' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_boxes' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function meta_box_scripts( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			wp_enqueue_script( 'dimax-meta-boxes', get_template_directory_uri() . '/assets/js/backend/meta-boxes.js', array( 'jquery' ), '20201012', true );
		}
	}

	/**
	 * Registering meta boxes
	 *
	 * @since 1.0.0
	 *
	 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
	 *
	 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
	 *
	 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
	 *
	 * @return array All registered meta boxes
	 */
	public function register_meta_boxes( $meta_boxes ) {
		// Header
		$meta_boxes[] = $this->register_header_settings();

		// Page Header
		$meta_boxes[] = $this->register_page_header_settings();

		// Content
		$meta_boxes[] = $this->register_content_settings();

		// Page Boxed
		$meta_boxes[] = $this->register_page_boxed_settings();

		// Footer
		$meta_boxes[] = $this->register_footer_settings();

		return $meta_boxes;
	}

	/**
	 * Register header settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_header_settings() {

		return array(
			'id'       => 'header-settings',
			'title'    => esc_html__( 'Header Settings', 'dimax' ),
			'pages'    => array( 'page', 'post' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Hide Header Section', 'dimax' ),
					'id'   => 'rz_hide_header_section',
					'type' => 'select',
					'type' => 'checkbox',
					'std'  => false,
				),
				array(
					'name'    => esc_html__( 'Header Layout', 'dimax' ),
					'id'      => 'rz_header_layout',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'dimax' ),
						'v1'      => esc_html__('Header v1', 'dimax'),
						'v2'      => esc_html__('Header v2', 'dimax'),
						'v3'      => esc_html__('Header v3', 'dimax'),
						'v4'      => esc_html__('Header v4', 'dimax'),
						'v5'      => esc_html__('Header v5', 'dimax'),
						'v6'      => esc_html__('Header v6', 'dimax'),
						'v7'      => esc_html__('Header v7', 'dimax'),
						'v8'      => esc_html__('Header v8', 'dimax'),
						'v9'      => esc_html__('Header v9', 'dimax'),
					),
				),
				array(
					'name'    => esc_html__( 'Header Background', 'dimax' ),
					'id'      => 'rz_header_background',
					'type'    => 'select',
					'options' => array(
						'default'     => esc_html__( 'Default', 'dimax' ),
						'transparent' => esc_html__( 'Transparent', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Header Text Color', 'dimax' ),
					'id'      => 'rz_header_text_color',
					'class'   => 'header-text-color hidden',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'dimax' ),
						'dark'    => esc_html__( 'Dark', 'dimax' ),
						'light'   => esc_html__( 'Light', 'dimax' ),
					),
				),
				array(
					'name' => esc_html__( 'Hide Border Bottom', 'dimax' ),
					'id'   => 'rz_hide_header_border',
					'type' => 'checkbox',
					'std'  => false,
				),
				array(
					'name'       => esc_html__( 'Header Spacing', 'dimax' ),
					'id'         => 'rz_header_bottom_spacing_bottom',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'dimax' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '20',
				),
				array(
					'name'    => esc_html__( 'Primary Menu', 'dimax' ),
					'id'      => 'rz_header_primary_menu',
					'type'    => 'select',
					'options' => $this->get_menus(),
				),
				array(
					'name'    => esc_html__( 'Department Menu Display', 'dimax' ),
					'id'      => 'rz_department_menu_display',
					'type'    => 'select',
					'options' => array(
						'default'    => esc_html__( 'On Hover', 'dimax' ),
						'onpageload' => esc_html__( 'On Page Load', 'dimax' ),
					),
				),
			)
		);
	}

	/**
	 * Get nav menus
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_menus() {
		if ( ! is_admin() ) {
			return [];
		}

		$menus = wp_get_nav_menus();
		if ( ! $menus ) {
			return [];
		}

		$output = array(
			0 => esc_html__( 'Default', 'dimax' ),
		);
		foreach ( $menus as $menu ) {
			$output[ $menu->slug ] = $menu->name;
		}

		return $output;
	}

	/**
	 * Register page header settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_page_header_settings() {
		return array(
			'id'       => 'page-header-settings',
			'title'    => esc_html__( 'Page Header Settings', 'dimax' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Hide Page Header', 'dimax' ),
					'id'   => 'rz_hide_page_header',
					'type' => 'checkbox',
					'std'  => false,
				),

				array(
					'name'  => esc_html__( 'Hide Title', 'dimax' ),
					'id'    => 'rz_hide_title',
					'type'  => 'checkbox',
					'std'   => false,
					'class' => 'page-header-hide-title',
				),

				array(
					'name'  => esc_html__( 'Hide Breadcrumb', 'dimax' ),
					'id'    => 'rz_hide_breadcrumb',
					'type'  => 'checkbox',
					'std'   => false,
					'class' => 'page-header-hide-breadcrumb',
				),

				array(
					'name'    => esc_html__( 'Spacing', 'dimax' ),
					'id'      => 'rz_page_header_spacing',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'dimax' ),
						'custom'  => esc_html__( 'Custom', 'dimax' ),
					),
				),

				array(
					'name'       => esc_html__( 'Top Spacing', 'dimax' ),
					'id'         => 'rz_page_header_top_padding',
					'class'      => 'custom-page-header-spacing hidden',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'dimax' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '50',
				),

				array(
					'name'       => esc_html__( 'Bottom Spacing', 'dimax' ),
					'id'         => 'rz_page_header_bottom_padding',
					'class'      => 'custom-page-header-spacing hidden',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'dimax' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '50',
				),
			)
		);
	}

	/**
	 * Register content settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_content_settings() {
		return array(
			'id'       => 'content-settings',
			'title'    => esc_html__( 'Content Settings', 'dimax' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name'    => esc_html__( 'Content Width', 'dimax' ),
					'id'      => 'rz_content_width',
					'type'    => 'select',
					'options' => array(
						''      => esc_html__( 'Normal', 'dimax' ),
						'large' => esc_html__( 'Large', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Content Top Spacing', 'dimax' ),
					'id'      => 'rz_content_top_spacing',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'dimax' ),
						'no'      => esc_html__( 'No spacing', 'dimax' ),
						'custom'  => esc_html__( 'Custom', 'dimax' ),
					),
				),
				array(
					'name'       => '&nbsp;',
					'id'         => 'rz_content_top_padding',
					'class'      => 'custom-spacing hidden',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'dimax' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '80',
				),
				array(
					'name'    => esc_html__( 'Content Bottom Spacing', 'dimax' ),
					'id'      => 'rz_content_bottom_spacing',
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'dimax' ),
						'no'      => esc_html__( 'No spacing', 'dimax' ),
						'custom'  => esc_html__( 'Custom', 'dimax' ),
					),
				),
				array(
					'name'       => '&nbsp;',
					'id'         => 'rz_content_bottom_padding',
					'class'      => 'custom-spacing hidden',
					'type'       => 'slider',
					'suffix'     => esc_html__( ' px', 'dimax' ),
					'js_options' => array(
						'min' => 0,
						'max' => 300,
					),
					'std'        => '80',
				),
			)
		);
	}

	/**
	 * Register page boxed settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_page_boxed_settings() {
		return array(
			'id'       => 'page-boxed-settings',
			'title'    => esc_html__( 'Boxed Layout Settings', 'dimax' ),
			'pages'    => array( 'page', 'post', 'product' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Disable Boxed Layout', 'dimax' ),
					'id'   => 'rz_disable_page_boxed',
					'type' => 'checkbox',
					'std'  => false,
				),
				array(
					'name' => esc_html__( 'Background Color', 'dimax' ),
					'id'   => 'rz_page_boxed_bg_color',
					'type' => 'color',
					'std'  => false,
				),
				array(
					'name'             => esc_html__( 'Background Image', 'dimax' ),
					'id'               => 'rz_page_boxed_bg_image',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'std'              => false,
				),
				array(
					'name'    => esc_html__( 'Background Horizontal', 'dimax' ),
					'id'      => 'rz_page_boxed_bg_horizontal',
					'type'    => 'select',
					'options' => array(
						''       => esc_html__( 'Default', 'dimax' ),
						'left'   => esc_html__( 'Left', 'dimax' ),
						'center' => esc_html__( 'Center', 'dimax' ),
						'right'  => esc_html__( 'Right', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Vertical', 'dimax' ),
					'id'      => 'rz_page_boxed_bg_vertical',
					'type'    => 'select',
					'options' => array(
						''       => esc_html__( 'Default', 'dimax' ),
						'top'    => esc_html__( 'Top', 'dimax' ),
						'center' => esc_html__( 'Center', 'dimax' ),
						'bottom' => esc_html__( 'Bottom', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Repeat', 'dimax' ),
					'id'      => 'rz_page_boxed_bg_repeat',
					'type'    => 'select',
					'options' => array(
						''          => esc_html__( 'Default', 'dimax' ),
						'no-repeat' => esc_html__( 'No Repeat', 'dimax' ),
						'repeat'    => esc_html__( 'Repeat', 'dimax' ),
						'repeat-y'  => esc_html__( 'Repeat Vertical', 'dimax' ),
						'repeat-x'  => esc_html__( 'Repeat Horizontal', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Attachment', 'dimax' ),
					'id'      => 'rz_page_boxed_bg_attachment',
					'type'    => 'select',
					'options' => array(
						''       => esc_html__( 'Default', 'dimax' ),
						'scroll' => esc_html__( 'Scroll', 'dimax' ),
						'fixed'  => esc_html__( 'Fixed', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Size', 'dimax' ),
					'id'      => 'rz_page_boxed_bg_size',
					'type'    => 'select',
					'options' => array(
						''        => esc_html__( 'Default', 'dimax' ),
						'auto'    => esc_html__( 'Auto', 'dimax' ),
						'cover'   => esc_html__( 'Cover', 'dimax' ),
						'contain' => esc_html__( 'Contain', 'dimax' ),
					),
				),
			)
		);
	}

	/**
	 * Register footer settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register_footer_settings() {
		return array(
			'id'       => 'footer-settings',
			'title'    => esc_html__( 'Footer Settings', 'dimax' ),
			'pages'    => array( 'page', 'post' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name' => esc_html__( 'Hide Footer Section', 'dimax' ),
					'id'   => 'rz_hide_footer_section',
					'type' => 'select',
					'type' => 'checkbox',
					'std'  => false,
				),
				array(
					'name' => esc_html__( 'Footer Border', 'dimax' ),
					'id'   => 'rz_footer_section_border_top',
					'type' => 'select',
					'desc' => esc_html__( 'Show/hide a divide line on top of the footer', 'dimax' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'dimax' ),
						'0' 	  => esc_html__( 'Hide', 'dimax' ),
						'1' 	  => esc_html__( 'Show', 'dimax' ),
					),
				),
				array(
					'name'    => esc_html__( 'Border Color', 'dimax' ),
					'id'      => 'rz_footer_section_border_color',
					'type'    => 'select',
					'options' => array(
						'default'     => esc_html__( 'Default', 'dimax' ),
						'custom' 	  => esc_html__( 'Custom', 'dimax' ),
					),
				),
				array(
					'name' => esc_html__( 'Color', 'dimax' ),
					'id'   => 'rz_footer_section_custom_border_color',
					'class' => 'footer-section-custom-border-color hidden',
					'type' => 'color',
					'std'  => false,
				),
			)
		);
	}
}
