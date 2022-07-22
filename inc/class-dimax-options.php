<?php

/**
 * Theme Options
 *
 * @package Dimax
 */

namespace Dimax;

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

class Options {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * $dimax_customize
	 *
	 * @var $dimax_customize
	 */
	protected static $dimax_customize = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self:: $instance;
	}

	/**
	 * The class constructor
	 *
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {
		add_filter('dimax_customize_config', array($this, 'customize_settings'));
		self::$dimax_customize = Theme::instance()->get('customizer');
	}


	/**
	 * This is a short hand function for getting setting value from customizer
	 *
	 * @since 1.0.0
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option($name) {
		if (is_object(self::$dimax_customize)) {
			$value = self::$dimax_customize->get_option($name);
		} elseif (false !== get_theme_mod($name)) {
			$value = get_theme_mod($name);
		} else {
			$value = $this->get_option_default($name);
		}

		return apply_filters('dimax_get_option', $value, $name);
	}

	/**
	 * Get default option values
	 *
	 * @since 1.0.0
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public static function get_option_default($name) {
		if (empty(self::$dimax_customize)) {
			return false;
		}

		return self:: $dimax_customize->get_option_default($name);
	}

	/**
	 * Get categories
	 *
	 * @since 1.0.0
	 *
	 * @param $taxonomies
	 * @param $default
	 *
	 * @return array
	 */
	public function get_categories($taxonomies, $default = false) {
		if (!taxonomy_exists($taxonomies)) {
			return [];
		}

		if (!is_admin()) {
			return [];
		}

		$output = [];

		if ($default) {
			$output[0] = esc_html__('Select Category', 'dimax');
		}

		global $wpdb;
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy                            = '%s'",
				$taxonomies
			),
			ARRAY_A
		);

		if (is_array($post_meta_infos) && !empty($post_meta_infos)) {
			foreach ($post_meta_infos as $value) {
				$output[$value['slug']] = $value['name'];
			}
		}

		return $output;
	}

	/**
	 * Options of footer items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function footer_items_option() {
		return apply_filters('dimax_footer_items_option', array(
			'copyright' => esc_html__('Copyright', 'dimax'),
			'menu'      => esc_html__('Menu', 'dimax'),
			'text'      => esc_html__('Custom text', 'dimax'),
			'payment'   => esc_html__('Payments', 'dimax'),
			'social'    => esc_html__('Socials', 'dimax'),
			'logo'      => esc_html__('Logo', 'dimax'),
		));
	}

	/**
	 * Options of header items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function header_items_option() {
		return apply_filters('dimax_header_items_option', array(
			'0'              => esc_html__('Select a item', 'dimax'),
			'logo'           => esc_html__('Logo', 'dimax'),
			'menu-primary'   => esc_html__('Primary Menu', 'dimax'),
			'menu-secondary' => esc_html__('Secondary Menu', 'dimax'),
			'hamburger'      => esc_html__('Hamburger Icon', 'dimax'),
			'search'         => esc_html__('Search Icon', 'dimax'),
			'cart'           => esc_html__('Cart Icon', 'dimax'),
			'wishlist'       => esc_html__('Wishlist Icon', 'dimax'),
			'account'        => esc_html__('Account Icon', 'dimax'),
			'languages'      => esc_html__('Languages', 'dimax'),
			'currencies'     => esc_html__('Currencies', 'dimax'),
			'department'     => esc_html__('Department', 'dimax'),
			'socials'        => esc_html__('Socials', 'dimax'),
			'text'     => esc_html__('Custom Text', 'dimax'),
		));
	}

	/**
	 * Options of topbar items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function topbar_items_option() {
		return apply_filters('dimax_topbar_items_option', array(
			'menu'     => esc_html__('Menu', 'dimax'),
			'currency' => esc_html__('Currency Switcher', 'dimax'),
			'language' => esc_html__('Language Switcher', 'dimax'),
			'social'   => esc_html__('Socials', 'dimax'),
			'text'     => esc_html__('Custom Text', 'dimax'),
			'close'    => esc_html__('Close Icon', 'dimax'),
		));
	}

		/**
	 * Options of navigation bar items
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function navigation_bar_items_option() {
		return apply_filters( 'dimax_navigation_bar_items_option', array(
			'home'     => esc_html__( 'Home', 'dimax' ),
			'menu'     => esc_html__( 'Menu', 'dimax' ),
			'search'   => esc_html__( 'Search', 'dimax' ),
			'cart'     => esc_html__( 'Cart', 'dimax' ),
			'wishlist' => esc_html__( 'Wishlist', 'dimax' ),
			'account'  => esc_html__( 'Account', 'dimax' ),
		) );
	}


	/**
	 * Options of mobile header icons
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function mobile_header_icons_option() {
		return apply_filters( 'dimax_mobile_header_icons_option', array(
			'cart'     => esc_html__( 'Cart Icon', 'dimax' ),
			'wishlist' => esc_html__( 'Wishlist Icon', 'dimax' ),
			'account'  => esc_html__( 'Account Icon', 'dimax' ),
			'menu'     => esc_html__( 'Menu Icon', 'dimax' ),
			'search'   => esc_html__( 'Search Icon', 'dimax' ),
			'text'     => esc_html__('Custom Text', 'dimax'),
		) );
	}

	/**
	 * Get customize settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function customize_settings() {
		$settings = array(
			'theme' => 'dimax',
		);

		$panels = array(
			'general' => array(
				'priority' => 10,
				'title'    => esc_html__('General', 'dimax'),
			),

			// Typography
			'typography' => array(
				'priority' => 30,
				'title'    => esc_html__( 'Typography', 'dimax' ),
			),

			// Header
			'header'  => array(
				'title'      => esc_html__('Header', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 30,
			),

			'page'   => array(
				'title'      => esc_html__('Page', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 40,
			),

			// Blog
			'blog'   => array(
				'title'      => esc_html__('Blog', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 50,
			),

			// Footer
			'footer' => array(
				'title'      => esc_html__('Footer', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 60,
			),
			// Footer
			'mobile' => array(
				'title'      => esc_html__('Mobile', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 60,
			),

		);

		$sections = array(
			// Maintenance
			'maintenance'  => array(
				'title'      => esc_html__('Maintenance', 'dimax'),
				'priority'   => 10,
				'capability' => 'edit_theme_options',
			),
			// Boxed
			'boxed_layout' => array(
				'title'       => esc_html__('Boxed Layout', 'dimax'),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'general',
			),

			'general_backtotop' => array(
				'title'       => esc_html__('Back To Top', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'general',
				'priority'    => 20,
			),
			'colors'            => array(
				'title'    => esc_html__( 'Colors', 'dimax' ),
				'priority' => 20,
			),

			// Typography
			'typo_main'         => array(
				'title'    => esc_html__( 'Main', 'dimax' ),
				'panel'    => 'typography',
			),
			'typo_headings'     => array(
				'title'    => esc_html__( 'Headings', 'dimax' ),
				'panel'    => 'typography',
			),
			'typo_header'       => array(
				'title'    => esc_html__( 'Header', 'dimax' ),
				'panel'    => 'typography',
			),
			'typo_page'         => array(
				'title'    => esc_html__( 'Page', 'dimax' ),
				'panel'    => 'typography',
			),
			'typo_posts'        => array(
				'title'    => esc_html__( 'Blog', 'dimax' ),
				'panel'    => 'typography',
			),
			'typo_widget'       => array(
				'title'    => esc_html__( 'Widgets', 'dimax' ),
				'panel'    => 'typography',
			),
			'typo_footer'       => array(
				'title'    => esc_html__( 'Footer', 'dimax' ),
				'panel'    => 'typography',
			),

			// Newsletter
			'newsletter_popup'  => array(
				'title'      => esc_html__('Newsletter Popup', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 20,
			),

			'preloader'         => array(
				'title'    => esc_html__( 'Preloader', 'dimax' ),
				'priority' => 20,
				'panel'    => 'general',
			),

			// Header
			'header_top'        => array(
				'title' => esc_html__('Topbar', 'dimax'),
				'panel' => 'header',
			),
			'header_topbar_bg'  => array(
				'title' => esc_html__('Topbar Background', 'dimax'),
				'panel' => 'header',
			),
			'header_layout'     => array(
				'title' => esc_html__('Header Layout', 'dimax'),
				'panel' => 'header',
			),
			'header_main'       => array(
				'title' => esc_html__('Header Main', 'dimax'),
				'panel' => 'header',
			),
			'header_bottom'     => array(
				'title' => esc_html__('Header Bottom', 'dimax'),
				'panel' => 'header',
			),
			'header_background' => array(
				'title' => esc_html__('Header Background', 'dimax'),
				'panel' => 'header',
			),
			'header_campaign'   => array(
				'title' => esc_html__('Campaign Bar', 'dimax'),
				'panel' => 'header',
			),
			'header_logo'       => array(
				'title' => esc_html__('Logo', 'dimax'),
				'panel' => 'header',
			),
			'header_search'     => array(
				'title' => esc_html__('Search', 'dimax'),
				'panel' => 'header',
			),
			'header_account'    => array(
				'title' => esc_html__('Account', 'dimax'),
				'panel' => 'header',
			),
			'header_wishlist'   => array(
				'title' => esc_html__('Wishlist', 'dimax'),
				'panel' => 'header',
			),
			'header_cart'       => array(
				'title' => esc_html__('Cart', 'dimax'),
				'panel' => 'header',
			),
			'header_primary_menu'  => array(
				'title' => esc_html__('Primary Menu', 'dimax'),
				'panel' => 'header',
			),
			'header_hamburger'  => array(
				'title' => esc_html__('Hamburger Menu', 'dimax'),
				'panel' => 'header',
			),
			'header_department' => array(
				'title' => esc_html__('Department', 'dimax'),
				'panel' => 'header',
			),
			'header_custom_text' => array(
				'title' => esc_html__('Custom Text', 'dimax'),
				'panel' => 'header',
			),

			// Page
			'page_header'       => array(
				'title'       => esc_html__('Page Header', 'dimax'),
				'description' => '',
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'panel'       => 'page',
			),

			// Blog
			'blog_page'         => array(
				'title'       => esc_html__('Blog Page', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),

			'page_header_blog'  => array(
				'title'       => esc_html__('Blog Page Header', 'dimax'),
				'description' => '',
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),

			// Single Post
			'single_post'       => array(
				'title'       => esc_html__('Single Post', 'dimax'),
				'description' => '',
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'panel'       => 'blog',
			),

			// Footer
			'footer_layout'     => array(
				'title'       => esc_html__('Footer Layout', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_newsletter' => array(
				'title'       => esc_html__('Footer Newsletter', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_widget'		=> array(
				'title'       => esc_html__('Footer Widget', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_extra'      => array(
				'title'       => esc_html__('Footer Extra', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_main'       => array(
				'title'       => esc_html__('Footer Main', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_background' => array(
				'title'       => esc_html__('Footer Background', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_copyright'  => array(
				'title'       => esc_html__('Copyright', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_menu'  => array(
				'title'       => esc_html__('Menu', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_text'       => array(
				'title'       => esc_html__('Custom Text', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_payment'    => array(
				'title'       => esc_html__('Payments', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),
			'footer_logo'       => array(
				'title'       => esc_html__('Logo', 'dimax'),
				'description' => '',
				'capability'  => 'edit_theme_options',
				'panel'       => 'footer',
			),

			'recently_viewed'  => array(
				'title'      => esc_html__('Recently Viewed', 'dimax'),
				'capability' => 'edit_theme_options',
				'priority'   => 50,
			),
			// Mobile
			'mobile_newsletter_popup' => array(
				'title' => esc_html__( 'Newsletter Popup', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_topbar'           => array(
				'title' => esc_html__( 'Topbar', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_header'           => array(
				'title' => esc_html__( 'Header', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_header_campaign'  => array(
				'title' => esc_html__( 'Campaign Bar', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_footer'           => array(
				'title' => esc_html__( 'Footer', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_panel'           => array(
				'title' => esc_html__( 'Panel', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_page'             => array(
				'title' => esc_html__( 'Page', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_blog'             => array(
				'title' => esc_html__( 'Blog', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_single_blog'             => array(
				'title' => esc_html__( 'Single Blog', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_product_catalog'  => array(
				'title' => esc_html__( 'Product Catalog', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_single_product'  => array(
				'title' => esc_html__( 'Single Product', 'dimax' ),
				'panel' => 'mobile',
			),
			'mobile_version'          => array(
				'priority' => 50,
				'title'    => esc_html__( 'Mobile Version', 'dimax' ),
				'panel'    => 'mobile',
			)

		);

		$fields = array(
			'color_scheme_title'  => array(
				'type'  => 'custom',
				'section'     => 'colors',
				'label' => esc_html__( 'Color Scheme', 'dimax' ),
			),
			'color_scheme'        => array(
				'type'            => 'color-palette',
				'default'         => '#ff6F61',
				'choices'         => array(
					'colors' => array(
						'#ff6F61',
						'#053399',
						'#3f51b5',
						'#7b1fa2',
						'#009688',
						'#388e3c',
						'#e64a19',
						'#b8a08d',
					),
					'style'  => 'round',
				),
				'section'     => 'colors',
				'active_callback' => array(
					array(
						'setting'  => 'color_scheme_custom',
						'operator' => '!=',
						'value'    => true,
					),
				),
			),
			'color_scheme_custom' => array(
				'type'      => 'checkbox',
				'label'     => esc_html__( 'Pick my favorite color', 'dimax' ),
				'default'   => false,
				'section'     => 'colors',
			),
			'color_scheme_color'  => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Custom Color', 'dimax' ),
				'default'         => '#161619',
				'section'     => 'colors',
				'active_callback' => array(
					array(
						'setting'  => 'color_scheme_custom',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'preloader_enable'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Preloader', 'dimax' ),
				'description' => esc_html__( 'Show a waiting screen when page is loading', 'dimax' ),
				'default'     => false,
				'section'     => 'preloader',
				'transport'   => 'postMessage',
			),
			'preloader_background_color' => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Background Color', 'dimax' ),
				'default'         => 'rgba(255,255,255,1)',
				'section'     => 'preloader',
				'choices'         => array(
					'alpha' => true,
				),
				'active_callback' => array(
					array(
						'setting'  => 'preloader_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'transport'   => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '#preloader',
						'property' => 'background-color',
					),
				),
			),
			'preloader'                  => array(
				'type'            => 'radio',
				'label'           => esc_html__( 'Preloader', 'dimax' ),
				'default'         => 'default',
				'section'     => 'preloader',
				'choices'         => array(
					'default'  => esc_attr__( 'Default Icon', 'dimax' ),
					'image'    => esc_attr__( 'Upload custom image', 'dimax' ),
					'external' => esc_attr__( 'External image URL', 'dimax' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'preloader_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'transport'       => 'postMessage',
			),
			'preloader_image'            => array(
				'type'            => 'image',
				'description'     => esc_html__( 'Preloader Image', 'dimax' ),
				'section'     => 'preloader',
				'active_callback' => array(
					array(
						'setting'  => 'preloader_enable',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'preloader',
						'operator' => '==',
						'value'    => 'image',
					),
				),
				'transport'       => 'postMessage',
			),
			'preloader_url'              => array(
				'type'            => 'text',
				'description'     => esc_html__( 'Preloader URL', 'dimax' ),
				'choices'         => array(
					'default'  => esc_attr__( 'Default Icon', 'dimax' ),
					'image'    => esc_attr__( 'Upload custom image', 'dimax' ),
					'external' => esc_attr__( 'External image URL', 'dimax' ),
				),
				'section'     => 'preloader',
				'active_callback' => array(
					array(
						'setting'  => 'preloader_enable',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'preloader',
						'operator' => '==',
						'value'    => 'external',
					),
				),
				'transport'       => 'postMessage',
			),
			// Popup
			'newsletter_popup_enable' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable Popup', 'dimax'),
				'description' => esc_html__('Show a newsletter popup after website loaded.', 'dimax'),
				'section'     => 'newsletter_popup',
				'default'     => false,
				'transport'   => 'postMessage',
			),

			'newsletter_popup_layout' => array(
				'type'      => 'radio-buttonset',
				'label'     => esc_html__('Popup Layout', 'dimax'),
				'default'   => '2-columns',
				'transport' => 'postMessage',
				'choices'   => array(
					'1-column'  => esc_attr__('1 Column', 'dimax'),
					'2-columns' => esc_attr__('2 Columns', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'newsletter_popup',
			),

			'newsletter_popup_image' => array(
				'type'            => 'image',
				'label'           => esc_html__('Image', 'dimax'),
				'description'     => esc_html__('This image will be used as background of the popup if the layout is 1 Column', 'dimax'),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'newsletter_popup',
			),

			'newsletter_popup_content' => array(
				'type'            => 'editor',
				'label'           => esc_html__('Popup Content', 'dimax'),
				'description'     => esc_html__('Enter popup content. HTML and shortcodes are allowed.', 'dimax'),
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'transport'       => 'postMessage',
				'section' => 'newsletter_popup',
			),

			'newsletter_popup_form' => array(
				'type'            => 'textarea',
				'label'           => esc_html__('NewsLetter Form', 'dimax'),
				'default'         => '',
				'description'     => sprintf(wp_kses_post('Enter the shortcode of MailChimp form . You can edit your sign - up form in the <a href= "%s" > MailChimp for WordPress form settings </a>.', 'dimax'), admin_url('admin.php?page=mailchimp-for-wp-forms')),
				'section'         => 'newsletter_popup',
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'transport'       => 'postMessage',

			),

			'newsletter_popup_frequency' => array(
				'type'        => 'number',
				'label'       => esc_html__('Frequency', 'dimax'),
				'description' => esc_html__('Do NOT show the popup to the same visitor again until this much days has passed.', 'dimax'),
				'default'     => 1,
				'choices'     => array(
					'min'  => 0,
					'step' => 1,
				),
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'transport' => 'postMessage',
				'section'   => 'newsletter_popup',
			),

			'newsletter_popup_visible' => array(
				'type'        => 'select',
				'label'       => esc_html__('Popup Visible', 'dimax'),
				'description' => esc_html__('Select when the popup appear', 'dimax'),
				'default'     => 'loaded',
				'choices'     => array(
					'loaded' => esc_html__('Right after page loads', 'dimax'),
					'delay'  => esc_html__('Wait for seconds', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'transport' => 'postMessage',
				'section'   => 'newsletter_popup',
			),

			'newsletter_popup_visible_delay' => array(
				'type'        => 'number',
				'label'       => esc_html__('Delay Time', 'dimax'),
				'description' => esc_html__('The time (in seconds) before the popup is displayed, after the page loaded.', 'dimax'),
				'default'     => 5,
				'choices'     => array(
					'min'  => 0,
					'step' => 1,
				),
				'active_callback' => array(
					array(
						'setting'  => 'newsletter_popup_enable',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'newsletter_popup_visible',
						'operator' => '==',
						'value'    => 'delay',
					),
				),
				'transport' => 'postMessage',
				'section'   => 'newsletter_popup',
			),

			// Maintenance
			'maintenance_enable'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable Maintenance Mode', 'dimax'),
				'description' => esc_html__('Put your site into maintenance mode', 'dimax'),
				'default'     => false,
				'section'     => 'maintenance',
			),
			'maintenance_mode'               => array(
				'type'        => 'radio',
				'label'       => esc_html__('Mode', 'dimax'),
				'description' => esc_html__('Select the correct mode for your site', 'dimax'),
				'tooltip'     => wp_kses_post(sprintf(__('If you are putting your site into maintenance mode for a longer perior of time, you should set this to "Coming Soon". Maintenance will return HTTP 503, Comming Soon will set HTTP to 200. <a href="%s" target="_blank">Learn more</a>', 'dimax'), 'https://yoast.com/http-503-site-maintenance-seo/')),
				'default'     => 'maintenance',
				'choices'     => array(
					'maintenance' => esc_attr__('Maintenance', 'dimax'),
					'coming_soon' => esc_attr__('Coming Soon', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'maintenance_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'maintenance',
			),
			'maintenance_page'               => array(
				'type'            => 'dropdown-pages',
				'label'           => esc_html__('Maintenance Page', 'dimax'),
				'default'         => 0,
				'active_callback' => array(
					array(
						'setting'  => 'maintenance_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'maintenance',
			),

			// Typography
			'typo_body'                      => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Body', 'dimax' ),
				'description' => esc_html__( 'Customize the body font', 'dimax' ),
				'section' 	  => 'typo_main',
				'default'     => array(
					'font-family' => '',
					'variant'     => '400',
					'font-size'   => '16px',
					'line-height' => '1.5',
					'color'       => '#525252',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'body',
					),
				),
			),

			'typo_h1'                        => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Heading 1', 'dimax' ),
				'description' => esc_html__( 'Customize the H1 font', 'dimax' ),
				'section' 	  => 'typo_headings',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '60px',
					'line-height'    => '1.33',
					'color'          => '#111',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'h1, .h1',
					),
				),
			),
			'typo_h2'                        => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Heading 2', 'dimax' ),
				'description' => esc_html__( 'Customize the H2 font', 'dimax' ),
				'section' 	  => 'typo_headings',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '40px',
					'line-height'    => '1.33',
					'color'          => '#111',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'h2, .h2',
					),
				),
			),
			'typo_h3'                        => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Heading 3', 'dimax' ),
				'description' => esc_html__( 'Customize the H3 font', 'dimax' ),
				'section' 	  => 'typo_headings',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '32px',
					'line-height'    => '1.33',
					'color'          => '#111',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'h3, .h3',
					),
				),
			),
			'typo_h4'                        => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Heading 4', 'dimax' ),
				'description' => esc_html__( 'Customize the H4 font', 'dimax' ),
				'section' 	  => 'typo_headings',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '24px',
					'line-height'    => '1.33',
					'color'          => '#111',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'h4, .h4',
					),
				),
			),
			'typo_h5'                        => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Heading 5', 'dimax' ),
				'description' => esc_html__( 'Customize the H5 font', 'dimax' ),
				'section' 	  => 'typo_headings',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '20px',
					'line-height'    => '1.33',
					'color'          => '#111',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'h5, .h5',
					),
				),
			),
			'typo_h6'                        => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Heading 6', 'dimax' ),
				'description' => esc_html__( 'Customize the H6 font', 'dimax' ),
				'section' 	  => 'typo_headings',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '16px',
					'line-height'    => '1.33',
					'color'          => '#111',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => 'h6, .h6',
					),
				),
			),

			'typo_menu'                      => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Menu', 'dimax' ),
				'description' => esc_html__( 'Customize the menu font', 'dimax' ),
				'section' 	  => 'typo_header',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '16px',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.main-navigation a, .hamburger-navigation a',
					),
				),
			),
			'typo_submenu'                   => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Sub-Menu', 'dimax' ),
				'description' => esc_html__( 'Customize the sub-menu font', 'dimax' ),
				'section'     => 'typo_header',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '15px',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.main-navigation ul ul a, .hamburger-navigation ul ul a',
					),
				),
			),

			'typo_page_title'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Page Title', 'dimax' ),
				'description' => esc_html__( 'Customize the page title font', 'dimax' ),
				'section'     => 'typo_page',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '32px',
					'line-height'    => '1.33',
					'text-transform' => 'none',
					'color'          => '#111',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.page-header__title',
					),
				),
			),

			'typo_blog_header_title'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Blog Header Title', 'dimax' ),
				'description' => esc_html__( 'Customize the font of blog header', 'dimax' ),
				'section'     => 'typo_posts',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '32px',
					'line-height'    => '1.33',
					'text-transform' => 'none',
					'color'          => '#111',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.blog .page-header__title',
					),
				),
			),
			'typo_blog_post_title'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Blog Post Title', 'dimax' ),
				'description' => esc_html__( 'Customize the font of blog post title', 'dimax' ),
				'section'     => 'typo_posts',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '500',
					'font-size'      => '24px',
					'line-height'    => '1.33',
					'text-transform' => 'none',
					'color'          => '#111',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.hentry .entry-title a',
					),
				),
			),
			'typo_blog_post_excerpt'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Blog Post Excerpt', 'dimax' ),
				'description' => esc_html__( 'Customize the font of blog post excerpt', 'dimax' ),
				'section'     => 'typo_posts',
				'default'     => array(
					'font-family'    => '',
					'variant'        => 'regular',
					'font-size'      => '16px',
					'line-height'    => '1.5',
					'text-transform' => 'none',
					'color'          => '#525252',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.blog-wrapper .entry-content',
					),
				),
			),

			'typo_widget_title'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Widget Title', 'dimax' ),
				'description' => esc_html__( 'Customize the widget title font', 'dimax' ),
				'section'     => 'typo_widget',
				'default'     => array(
					'font-family'    => '',
					'variant'        => '600',
					'font-size'      => '16px',
					'text-transform' => 'uppercase',
					'color'          => '#111',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.widget-title, .footer-widgets .widget-title',
					),
				),
			),

			'typo_footer_extra'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Footer Extra', 'dimax' ),
				'description' => esc_html__( 'Customize the font of footer extra', 'dimax' ),
				'section'     => 'typo_widget',
				'default'     => array(
					'font-family'    => '',
					'variant'        => 'regular',
					'font-size'      => '16px',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.footer-extra',
					),
				),
			),
			'typo_footer_widgets'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Footer Widgets', 'dimax' ),
				'description' => esc_html__( 'Customize the font of footer widgets', 'dimax' ),
				'section'     => 'typo_widget',
				'default'     => array(
					'font-family'    => '',
					'variant'        => 'regular',
					'font-size'      => '16px',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.footer-widgets',
					),
				),
			),
			'typo_footer_main'              => array(
				'type'        => 'typography',
				'label'       => esc_html__( 'Footer Main', 'dimax' ),
				'description' => esc_html__( 'Customize the font of footer main', 'dimax' ),
				'section'     => 'typo_widget',
				'default'     => array(
					'font-family'    => '',
					'variant'        => 'regular',
					'font-size'      => '14px',
					'text-transform' => 'none',
					'subsets'        => array( 'latin-ext' ),
				),
				'transport' => 'postMessage',
				'js_vars'      => array(
					array(
						'element' => '.footer-main',
					),
				),
			),

			// topbar
			'topbar'                         => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Topbar', 'dimax'),
				'default'     => '0',
				'section'     => 'header_top',
				'description' => esc_html__('Check this option to enable a topbar above the site header', 'dimax'),
			),

			'topbar_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'header_top',
				'default' => '<hr/>',
			),

			'topbar_height' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Height', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '45',
				'choices'   => array(
					'min' => 0,
					'max' => 240,
				),
				'js_vars'   => array(
					array(
						'element'  => '.topbar',
						'property' => 'height',
						'units'    => 'px',
					),
				),
				'section' => 'header_top',
			),

			'topbar_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'header_top',
				'default' => '<hr/>',
			),

			'topbar_left'  => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Left Items', 'dimax'),
				'description' => esc_html__('Control items on the left side of the topbar', 'dimax'),
				'transport'   => 'postMessage',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'      => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->topbar_items_option(),
					),
				),
				'section' => 'header_top',
			),
			'topbar_right' => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Right Items', 'dimax'),
				'description' => esc_html__('Control items on the right side of the topbar', 'dimax'),
				'transport'   => 'postMessage',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'      => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->topbar_items_option(),
					),
				),
				'section' => 'header_top',
			),

			'topbar_custom_field_5' => array(
				'type'    => 'custom',
				'section' => 'header_top',
				'default' => '<hr/>',
			),

			'topbar_menu_item'       => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Menu', 'dimax' ),
				'section'         => 'header_top',
				'default'         => '',
				'choices'         => $this->get_navigation_bar_get_menus(),

			),

			'topbar_custom_field_4' => array(
				'type'    => 'custom',
				'section' => 'header_top',
				'default' => '<hr/>',
			),

			'topbar_svg_code' => array(
				'type'              => 'textarea',
				'label'             => esc_html__('Custom Text SVG', 'dimax'),
				'description'       => esc_html__('The SVG before your text', 'dimax'),
				'default'           => '',
				'sanitize_callback' => '\Dimax\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.dimax-topbar__text .dimax-svg-icon',
					),
				),
				'section' => 'header_top',
			),

			'topbar_text'             => array(
				'type'        => 'editor',
				'label'       => esc_html__('Custom Text', 'dimax'),
				'description' => esc_html__('The content of Custom Text item', 'dimax'),
				'default'     => '',
				'section'     => 'header_top',
			),


			// topbar bg
			'topbar_bg_custom_color'  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Custom Color', 'dimax'),
				'section' => 'header_topbar_bg',
				'default' => 0,
			),
			'topbar_bg_color'         => array(
				'label'           => esc_html__('Background Color', 'dimax'),
				'type'            => 'color',
				'default'         => '',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'topbar_bg_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.topbar',
						'property' => 'background-color',
					),
				),
				'section' => 'header_topbar_bg',
			),
			'topbar_text_color'       => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'dimax'),
				'transport'       => 'postMessage',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'topbar_bg_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.topbar',
						'property' => '--rz-color-dark',
					),
					array(
						'element'  => '.topbar',
						'property' => '--rz-icon-color',
					)
				),
				'section' => 'header_topbar_bg',
			),
			'topbar_text_color_hover' => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Hover Color', 'dimax'),
				'transport'       => 'postMessage',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'topbar_bg_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.topbar',
						'property' => '--rz-color-primary',
					),
				),
				'section' => 'header_topbar_bg',
			),
			'topbar_bg_border_custom_field_1'              => array(
				'type'    => 'custom',
				'section' => 'header_topbar_bg',
				'default' => '<hr/>',
			),
			'topbar_bg_border'  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Border Line', 'dimax'),
				'section' => 'header_topbar_bg',
				'default' => 0,
			),
			'topbar_bg_border_color' => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color', 'dimax'),
				'transport'       => 'postMessage',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'topbar_bg_border',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.topbar',
						'property' => 'border-color',
					),
				),
				'section' => 'header_topbar_bg',
			),

			// Header Layout
			'header_type'             => array(
				'type'        => 'radio',
				'label'       => esc_html__('Header Type', 'dimax'),
				'description' => esc_html__('Select a default header or custom header', 'dimax'),
				'section'     => 'header_layout',
				'default'     => 'default',
				'choices'     => array(
					'default' => esc_html__('Use pre-build header', 'dimax'),
					'custom'  => esc_html__('Build my own', 'dimax'),
				),
			),
			'header_layout'           => array(
				'type'    => 'select',
				'label'   => esc_html__('Header Layout', 'dimax'),
				'section' => 'header_layout',
				'default' => 'v1',
				'choices' => array(
					'v1' => esc_html__('Header v1', 'dimax'),
					'v2' => esc_html__('Header v2', 'dimax'),
					'v3' => esc_html__('Header v3', 'dimax'),
					'v4' => esc_html__('Header v4', 'dimax'),
					'v5' => esc_html__('Header v5', 'dimax'),
					'v6' => esc_html__('Header v6', 'dimax'),
					'v7' => esc_html__('Header v7', 'dimax'),
					'v8' => esc_html__('Header v8', 'dimax'),
					'v9' => esc_html__('Header v9', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
				),
			),
			'header_search_icon'  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Search', 'dimax'),
				'section' => 'header_layout',
				'default' => 1,
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
					array(
						'setting'  => 'header_layout',
						'operator' => 'in',
						'value'    => array( 'v1', 'v2', 'v3', 'v5', 'v6', 'v8', 'v9' ),
					),
				),
			),
			'header_account_icon'  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Account', 'dimax'),
				'section' => 'header_layout',
				'default' => 1,
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
					array(
						'setting'  => 'header_layout',
						'operator' => 'in',
						'value'    => array( 'v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v8', 'v9' ),
					),
				),
			),
			'header_wishlist_icon'  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Wishlist', 'dimax'),
				'section' => 'header_layout',
				'default' => 1,
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
					array(
						'setting'  => 'header_layout',
						'operator' => 'in',
						'value'    => array( 'v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v8', 'v9' ),
					),
				),
			),
			'header_cart_icon'  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Cart', 'dimax'),
				'section' => 'header_layout',
				'default' => 1,
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
					array(
						'setting'  => 'header_layout',
						'operator' => 'in',
						'value'    => array( 'v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7', 'v8', 'v9' ),
					),
				),
			),
			'header_layout_custom_field_1'              => array(
				'type'    => 'custom',
				'section' => 'header_layout',
				'default' => '<hr/>',
			),

			// Header Sticky
			'header_sticky'                             => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Sticky Header', 'dimax'),
				'default' => 0,
				'section' => 'header_layout',
			),
			'header_width'	=> array(
				'type'    => 'select',
				'label'   => esc_html__('Header Width', 'dimax'),
				'section' => 'header_layout',
				'default' => '',
				'choices' => array(
					''      => esc_html__('Normal', 'dimax'),
					'large' => esc_html__('Large', 'dimax'),
					'wide'  => esc_html__('Wide', 'dimax'),
				),
			),
			'header_sticky_el'                          => array(
				'type'     => 'multicheck',
				'label'    => esc_html__('Sticky Header Elements', 'dimax'),
				'section'  => 'header_layout',
				'default'  => array('header_main'),
				'priority' => 10,
				'choices'  => array(
					'header_main'   => esc_html__('Header Main', 'dimax'),
					'header_bottom' => esc_html__('Header Bottom', 'dimax'),
				),
				'active_callback' => function() {
					return $this->display_header_sticky();
				},
			),

			// Header main
			'header_main_left'                          => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Left Items', 'dimax'),
				'description' => esc_html__('Control items on the left side of header main', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_main_center'                        => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Center Items', 'dimax'),
				'description' => esc_html__('Control items at the center of header main', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_main_right'                         => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Right Items', 'dimax'),
				'description' => esc_html__('Control items on the right of header main', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_main_hr'                            => array(
				'type'    => 'custom',
				'section' => 'header_main',
				'default' => '<hr>',
			),
			'header_main_height'                        => array(
				'type'      => 'slider',
				'label'     => esc_html__('Header Height', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'header_main',
				'default'   => '90',
				'choices'   => array(
					'min' => 50,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '#site-header .header-main',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),
			'sticky_header_main_height'                 => array(
				'type'        => 'slider',
				'label'       => esc_html__('Sticky Header Height', 'dimax'),
				'description' => esc_html__('Adjust Header Main height when the Sticky Header enabled', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_main',
				'default'     => '90',
				'choices'     => array(
					'min' => 50,
					'max' => 500,
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_sticky',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-sticky #site-header.minimized .header-main',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),

			// Header Bottom
			'header_bottom_left'                        => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Left Items', 'dimax'),
				'description' => esc_html__('Control items on the left side of header bottom', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_bottom_center'                      => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Center Items', 'dimax'),
				'description' => esc_html__('Control items at the center of header bottom', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_bottom_right'                       => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Right Items', 'dimax'),
				'description' => esc_html__('Control items on the right of header bottom', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->header_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_bottom_hr'                          => array(
				'type'    => 'custom',
				'section' => 'header_bottom',
				'default' => '<hr>',
			),
			'header_bottom_height'                      => array(
				'type'      => 'slider',
				'label'     => esc_html__('Header Height', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'header_bottom',
				'default'   => '50',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '#site-header .header-bottom',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),
			'sticky_header_bottom_height'               => array(
				'type'        => 'slider',
				'label'       => esc_html__('Sticky Header Height', 'dimax'),
				'description' => esc_html__('Adjust Header Bottom height when the Sticky Header enabled', 'dimax'),
				'transport'   => 'postMessage',
				'section'     => 'header_bottom',
				'default'     => '50',
				'choices'     => array(
					'min' => 0,
					'max' => 500,
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_sticky',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-sticky #site-header.minimized .header-bottom',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),

			// Header Backgound
			'header_main_background'                    => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Main Custom Color', 'dimax'),
				'section' => 'header_background',
				'default' => 0,
			),
			'header_main_background_color'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-main, .site-header .header-mobile',
						'property' => 'background-color',
					),
					array(
						'element'  => '.header-v6 .header-main .dimax-header-container',
						'property' => '--rz-background-color-light',
					),
				),
			),
			'header_main_background_text_color'         => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-main, .site-header .header-mobile',
						'property' => '--rz-header-color-dark',
					),
					array(
						'element'  => '.site-header .header-main, .site-header .header-mobile',
						'property' => '--rz-stroke-svg-dark',
					),
				),
			),
			'header_main_background_text_color_hover'   => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Hover Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-main',
						'property' => '--rz-color-hover-primary',
					),
				),
			),
			'header_main_background_border_color'       => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-main',
						'property' => 'border-color',
					),
				),
			),
			'header_background_hr'                      => array(
				'type'    => 'custom',
				'section' => 'header_background',
				'default' => '<hr>',
			),
			'header_bottom_background'                  => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Header Bottom Custom Color', 'dimax'),
				'section' => 'header_background',
				'default' => 0,
			),
			'header_bottom_background_color'            => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-bottom',
						'property' => 'background-color',
					),
				),
			),
			'header_bottom_background_text_color'       => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-bottom',
						'property' => '--rz-header-color-dark',
					),
					array(
						'element'  => '.header-v3 .header-bottom .main-navigation > ul > li > a',
						'property' => '--rz-header-color-dark',
					),
					array(
						'element'  => '.site-header .header-bottom',
						'property' => '--rz-stroke-svg-dark',
					),
				),
			),
			'header_bottom_background_text_color_hover' => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Hover Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-bottom',
						'property' => '--rz-color-hover-primary',
					),
				),
			),
			'header_bottom_background_border_color'     => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_background',
				'active_callback' => array(
					array(
						'setting'  => 'header_bottom_background',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.site-header .header-bottom',
						'property' => 'border-color',
					),
				),
			),
			'header_menu_hr'                      => array(
				'type'    => 'custom',
				'section' => 'header_background',
				'default' => '<hr>',
			),

			'header_active_primary_menu_color'                  => array(
				'type'    => 'select',
				'label'   => esc_html__('Active Primary Menu Color', 'dimax'),
				'section' => 'header_background',
				'default' => 'primary',
				'choices' => array(
					'primary'  => esc_html__('Primary Color', 'dimax'),
					'current' => esc_html__('Current Color', 'dimax'),
				),
			),

			// Header Campain
			'campaign_bar'                              => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Campaign Bar', 'dimax'),
				'section'     => 'header_campaign',
				'description' => esc_html__('Display a bar bellow site header. This bar will be hidden when the header background is transparent.', 'dimax'),
				'default'     => false,
			),
			'campaign_bar_position'                     => array(
				'type'    => 'select',
				'label'   => esc_html__('Position', 'dimax'),
				'section' => 'header_campaign',
				'default' => 'after',
				'choices' => array(
					'after'  => esc_html__('After Header', 'dimax'),
					'before' => esc_html__('Before Header', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'campaign_bar_height'                       => array(
				'type'      => 'slider',
				'label'     => esc_html__('Height', 'dimax'),
				'section'   => 'header_campaign',
				'default'   => '50',
				'transport' => 'postMessage',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '#campaign-bar',
						'property' => 'height',
						'units'    => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'campaign_bar_custom_field_1'               => array(
				'type'            => 'custom',
				'section'         => 'header_campaign',
				'default'         => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'campaign_items'                            => array(
				'type'      => 'repeater',
				'label'     => esc_html__('Campaigns', 'dimax'),
				'section'   => 'header_campaign',
				'transport' => 'postMessage',
				'default'   => array(),
				'row_label' => array(
					'type'  => 'field',
					'value' => esc_attr__('Campaign', 'dimax'),
				),
				'fields'          => array(
					'text'    => array(
						'type'  => 'textarea',
						'label' => esc_html__('Text', 'dimax'),
					),
					'image'   => array(
						'type'  => 'image',
						'label' => esc_html__('Background Image', 'dimax'),
					),
					'bgcolor' => array(
						'type'  => 'color',
						'label' => esc_html__('Background Color', 'dimax'),
					),
					'color'   => array(
						'type'  => 'color',
						'label' => esc_html__('Color', 'dimax'),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'campaign_bar',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			// Logo
			'logo_type'                                 => array(
				'type'    => 'radio',
				'label'   => esc_html__('Logo Type', 'dimax'),
				'default' => 'text',
				'section' => 'header_logo',
				'choices' => array(
					'image' => esc_html__('Image', 'dimax'),
					'svg'   => esc_html__('SVG', 'dimax'),
					'text'  => esc_html__('Text', 'dimax'),
				),
			),
			'logo_svg'                                  => array(
				'type'              => 'textarea',
				'label'             => esc_html__('Logo SVG', 'dimax'),
				'section'           => 'header_logo',
				'description'       => esc_html__('Paste SVG code of your logo here', 'dimax'),
				'sanitize_callback' => '\Dimax\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.site-branding .logo',
					),
				),
				'active_callback'   => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'svg',
					),
				),
			),
			'logo'                                      => array(
				'type'            => 'image',
				'label'           => esc_html__('Logo', 'dimax'),
				'default'         => '',
				'section'         => 'header_logo',
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),
			'logo_svg_light'                            => array(
				'type'              => 'textarea',
				'label'             => esc_html__('Logo Light SVG', 'dimax'),
				'section'           => 'header_logo',
				'description'       => esc_html__('Paste SVG code of your logo here', 'dimax'),
				'sanitize_callback' => '\Dimax\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.site-branding .logo',
					),
				),
				'active_callback'   => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'svg',
					),
				),
			),
			'logo_light'                                => array(
				'type'            => 'image',
				'label'           => esc_html__('Logo Light', 'dimax'),
				'default'         => '',
				'section'         => 'header_logo',
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),
			'logo_text'                                 => array(
				'type'    => 'textarea',
				'label'   => esc_html__('Logo Text', 'dimax'),
				'default' => 'Dimax.',
				'section' => 'header_logo',
				'output'  => array(
					array(
						'element' => '.site-branding .logo',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'logo_dimension'                            => array(
				'type'    => 'dimensions',
				'label'   => esc_html__('Logo Dimension', 'dimax'),
				'default' => array(
					'width'  => '',
					'height' => '',
				),
				'section'         => 'header_logo',
				'active_callback' => array(
					array(
						'setting'  => 'logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),

			// Header Search
			'header_search_style'                       => array(
				'type'    => 'select',
				'label'   => esc_html__('Search Layout', 'dimax'),
				'default' => 'icon',
				'section' => 'header_search',
				'choices' => array(
					'form-cat' => esc_html__('Icon and categories', 'dimax'),
					'form'     => esc_html__('Icon and search field', 'dimax'),
					'icon'     => esc_html__('Icon only', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_search_form_style'                  => array(
				'type'    => 'select',
				'label'   => esc_html__('Search Style', 'dimax'),
				'default' => 'boxed',
				'section' => 'header_search',
				'choices' => array(
					'boxed'      => esc_html__('Boxed', 'dimax'),
					'full-width' => esc_html__('Full Width', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'form',
					),
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_search_form_skin'                  => array(
				'type'    => 'select',
				'label'   => esc_html__('Search Skin', 'dimax'),
				'default' => 'dark',
				'section' => 'header_search',
				'choices' => array(
					'dark' 		=> esc_html__('Dark', 'dimax'),
					'light'      => esc_html__('Light', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'form',
					),
					array(
						'setting'  => 'header_search_form_style',
						'operator' => '==',
						'value'    => 'boxed',
					),
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'header_search_type'                        => array(
				'type'    => 'select',
				'label'   => esc_html__('Search For', 'dimax'),
				'default' => '',
				'section' => 'header_search',
				'choices' => array(
					''        => esc_html__('Search for everything', 'dimax'),
					'product' => esc_html__('Search for products', 'dimax'),
				),
			),
			'header_search_custom_field_1'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
				'active_callback' => function() {
					return $this->display_header_search_panel();
				},
			),
			'header_search_text'                        => array(
				'type'            => 'text',
				'label'           => esc_html__('Panel Search Title', 'dimax'),
				'section'         => 'header_search',
				'default'         => '',
				'active_callback' => function() {
					return $this->display_header_search_panel();
				},
			),
			'header_search_custom_field_2'              => array(
				'type'    => 'custom',
				'section' => 'header_search',
				'default' => '<hr/>',
			),
			'header_search_placeholder'                 => array(
				'type'    => 'text',
				'label'   => esc_html__('Placeholder', 'dimax'),
				'section' => 'header_search',
				'default' => '',
			),
			'header_search_custom_field_3'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
			'header_search_ajax'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__('AJAX Search', 'dimax'),
				'section'     => 'header_search',
				'default'     => 0,
				'description' => esc_html__('Check this option to enable AJAX search in the header', 'dimax'),
			),
			'header_search_number'                      => array(
				'type'            => 'number',
				'label'           => esc_html__('AJAX Product Numbers', 'dimax'),
				'default'         => 3,
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_ajax',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'header_search_custom_field_5'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
			),
			'header_search_quick_links' => array(
				'type'            => 'toggle',
				'section'         => 'header_search',
				'label'           => esc_html__( 'Quick links', 'dimax' ),
				'description'     => esc_html__( 'Display a list of links bellow the search field', 'dimax' ),
				'default'         => false,
			),
			'header_search_links'       => array(
				'type'            => 'repeater',
				'section'         => 'header_search',
				'label'           => esc_html__( 'Links', 'dimax' ),
				'description'     => esc_html__( 'Add custom links of the quick links popup', 'dimax' ),
				'transport'       => 'postMessage',
				'default'         => array(),
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Link', 'dimax' ),
					'field' => 'text',
				),
				'fields'          => array(
					'text' => array(
						'type'  => 'text',
						'label' => esc_html__( 'Text', 'dimax' ),
					),
					'url'  => array(
						'type'  => 'text',
						'label' => esc_html__( 'URL', 'dimax' ),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_search_quick_links',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'header_search_custom_field_4'              => array(
				'type'            => 'custom',
				'section'         => 'header_search',
				'default'         => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'form',
					),
					array(
						'setting'  => 'header_search_form_style',
						'operator' => '==',
						'value'    => 'boxed',
					),
				),
			),
			'header_search_custom_color'                => array(
				'type'            => 'toggle',
				'label'           => esc_html__('Custom Color', 'dimax'),
				'section'         => 'header_search',
				'default'         => 0,
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '!=',
						'value'    => 'icon',
					),
				),
			),
			'header_search_background_color'            => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '!=',
						'value'    => 'icon',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-search.search-form-type .search-fields',
						'property' => 'background-color',
					),
					array(
						'element'  => '.header-search.search-form-type .product-cat-label',
						'property' => 'background-color',
					),
					array(
						'element'  => '#site-header .header-search .form-search .search-field',
						'property' => 'background-color',
					),
				),
			),
			'header_search_text_color'                  => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '!=',
						'value'    => 'icon',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '#site-header .header-search .form-search .search-field',
						'property' => '--rz-header-color-light',
					),
					array(
						'element'  => '#site-header .header-search .form-search .search-field::placeholder',
						'property' => '--rz-color-placeholder',
					),
					array(
						'element'  => '#site-header .header-search .form-search .search-field',
						'property' => '--rz-header-color-darker',
					),
					array(
						'element'  => '.header-search.search-type-form-cat .product-cat-label',
						'property' => '--rz-header-text-color-gray',
					),
				),
			),
			'header_search_button_color'                => array(
				'type'            => 'color',
				'label'           => esc_html__('Icon Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '!=',
						'value'    => 'icon',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '#site-header .header-search .form-search .search-submit',
						'property' => 'color',
					),
					array(
						'element'  => '#site-header .header-search .form-search .search-submit .dimax-svg-icon',
						'property' => 'color',
					),
				),
			),
			'header_search_border_color'                => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '!=',
						'value'    => 'icon',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-search .form-search',
						'property' => 'border-color',
					),
					array(
						'element'  => '.header-search .form-search',
						'property' => '--rz-border-color-light',
					),
					array(
						'element'  => '.header-search .form-search',
						'property' => '--rz-border-color-dark',
					),
					array(
						'element'  => '#site-header .header-search .form-search .search-field',
						'property' => 'border-color',
					),
				),
			),
			'header_search_border_color_hover'          => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color Hover', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'form-cat',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '#site-header .header-search .form-search .search-field:focus',
						'property' => 'border-color',
					),
					array(
						'element'  => '#site-header .header-search .form-search .search-field:focus',
						'property' => '--rz-border-color-dark',
					),
					array(
						'element'  => '.header-search .border-color-dark',
						'property' => '--rz-border-color-dark',
					),
				),
			),
			'header_search_bg_button_color'             => array(
				'type'            => 'color',
				'label'           => esc_html__('Button Background Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'form-cat',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '#site-header .search-type-form-cat .form-search .search-submit',
						'property' => 'background-color',
					),
				),
			),
			'header_search_border_button_color'         => array(
				'type'            => 'color',
				'label'           => esc_html__('Button Border Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_search',
				'active_callback' => array(
					array(
						'setting'  => 'header_search_style',
						'operator' => '==',
						'value'    => 'form-cat',
					),
					array(
						'setting'  => 'header_search_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '#site-header .search-type-form-cat .form-search .search-submit',
						'property' => '--rz-border-color-light',
					),
				),
			),

			// Header Account
			'header_account_behaviour'                  => array(
				'type'    => 'radio',
				'label'   => esc_html__('Account Icon Behaviour', 'dimax'),
				'default' => 'panel',
				'section' => 'header_account',
				'choices' => array(
					'panel' => esc_attr__('Open the account panel', 'dimax'),
					'link'  => esc_attr__('Open the account page', 'dimax'),
				),
			),

			// Header Wishlist
			'header_wishlist_link'                      => array(
				'type'    => 'text',
				'label'   => esc_html__('Custom Wishlist Link', 'dimax'),
				'section' => 'header_wishlist',
				'default' => '',
			),

			// Header Cart
			'header_cart_behaviour'                     => array(
				'type'    => 'radio',
				'label'   => esc_html__('Cart Icon Behaviour', 'dimax'),
				'default' => 'panel',
				'section' => 'header_cart',
				'choices' => array(
					'panel' => esc_attr__('Open the cart panel', 'dimax'),
					'link'  => esc_attr__('Open the cart page', 'dimax'),
				),
			),
			'header_cart_custom_field_1'                => array(
				'type'    => 'custom',
				'section' => 'header_cart',
				'default' => '<hr/>',
			),
			'cart_icon_source'      => array(
				'type'    => 'radio',
				'label'   => esc_html__( 'Cart Icon', 'dimax' ),
				'default' => 'icon',
				'section' => 'header_cart',
				'choices' => array(
					'icon'  => esc_attr__( 'Built-in Icon', 'dimax' ),
					'svg'   => esc_attr__( 'SVG Code', 'dimax' ),
				),
			),
			'cart_icon'             => array(
				'type'    => 'radio-image',
				'default' => 'cart',
				'section' => 'header_cart',
				'choices' => array(
					'cart'       => get_template_directory_uri() . '/assets/svg/cart.svg',
					'shop-bag'   => get_template_directory_uri() . '/assets/svg/shop-bag.svg',
					'shop-bag-2' => get_template_directory_uri() . '/assets/svg/shop-bag-2.svg',
					'shop-cart'  => get_template_directory_uri() . '/assets/svg/shop-cart.svg',
				),
				'active_callback' => array(
					array(
						'setting'  => 'cart_icon_source',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
			'cart_icon_svg'         => array(
				'type'              => 'textarea',
				'description'       => esc_html__( 'Icon SVG code', 'dimax' ),
				'sanitize_callback' => '\Dimax\Icon::sanitize_svg',
				'section'           => 'header_cart',
				'active_callback'   => array(
					array(
						'setting'  => 'cart_icon_source',
						'operator' => '==',
						'value'    => 'svg',
					),
				),
			),
			'cart_hr_1'          => array(
				'type'    => 'custom',
				'section' => 'header_cart',
				'default' => '<hr>',
			),
			'header_cart_custom_color'                  => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Custom Color Counter', 'dimax'),
				'section'     => 'header_cart',
				'default'     => 0,
				'description' => esc_html__('Change color button counter cart', 'dimax'),
			),
			'header_cart_background_color'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_cart',
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-cart .counter',
						'property' => '--rz-background-color-primary',
					),
				),
			),
			'header_cart_text_color'                    => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_cart',
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-cart .counter',
						'property' => '--rz-background-text-color-primary',
					),
				),
			),
			'header_cart_border_color'                  => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_cart',
				'active_callback' => array(
					array(
						'setting'  => 'header_cart_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-cart .counter',
						'property' => '--rz-border-color-lighter',
					),
				),
			),
			'header_cart_custom_field_2'                => array(
				'type'    => 'custom',
				'section' => 'header_cart',
				'default' => '<hr/>',
			),
			'header_mini_cart_buttons'              => array(
				'type'            => 'multicheck',
				'label'           => esc_html__('Mini Cart Buttons', 'dimax'),
				'default'         => array('cart', 'checkout'),
				'section'         => 'header_cart',
				'choices' => array(
					'cart' => esc_attr__('Cart Button', 'dimax'),
					'checkout'  => esc_attr__('Checkout Button', 'dimax'),
				),
			),

			// Header Primary Menu
			'primary_menu_show_arrow'                      => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Show Arrow Icon', 'dimax'),
				'default'     => 1,
				'section'     => 'header_primary_menu',
			),
			'primary_menu_style'                       => array(
				'type'    => 'select',
				'label'   => esc_html__('Justify Content', 'dimax'),
				'section' => 'header_primary_menu',
				'default' => 'space-between',
				'choices' => array(
					'space-between'  => esc_html__('Space Between', 'dimax'),
					'flex-start' => esc_html__('Left', 'dimax'),
					'flex-end' => esc_html__('Right', 'dimax'),
					'center' => esc_html__('Center', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_type',
						'operator' => '==',
						'value'    => 'default',
					),
					array(
						'setting'  => 'header_layout',
						'operator' => '==',
						'value'    => 'v9',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-v9 .main-navigation .nav-menu',
						'property' => 'justify-content',
					),
				),
			),

			// Header Menu Hamburger
			'hamburger_side_type'                       => array(
				'type'    => 'select',
				'label'   => esc_html__('Show Menu', 'dimax'),
				'section' => 'header_hamburger',
				'default' => 'side-right',
				'choices' => array(
					'side-left'  => esc_html__('Side to right', 'dimax'),
					'side-right' => esc_html__('Side to left', 'dimax'),
				),
			),
			'hamburger_click_item'                      => array(
				'type'    => 'select',
				'label'   => esc_html__('Show Sub-Menus', 'dimax'),
				'section' => 'header_hamburger',
				'default' => 'click-item',
				'choices' => array(
					'click-item' => esc_html__('Click to item', 'dimax'),
					'click-icon' => esc_html__('Click to icon', 'dimax'),
				),
			),
			'hamburger_show_arrow'                      => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Show Arrow Icon', 'dimax'),
				'default'     => '0',
				'section'     => 'header_hamburger',
			),
			'header_hamburger_custom_field_1'           => array(
				'type'    => 'custom',
				'section' => 'header_hamburger',
				'default' => '<hr/>',
			),
			'hamburger_show_socials'                    => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Show Socials', 'dimax'),
				'default'     => '0',
				'section'     => 'header_hamburger',
				'description' => esc_html__('Display social menu', 'dimax'),
			),
			'header_hamburger_custom_field_2'           => array(
				'type'    => 'custom',
				'section' => 'header_hamburger',
				'default' => '<hr/>',
			),
			'hamburger_show_copyright'                  => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Show Copyright', 'dimax'),
				'default'     => '0',
				'section'     => 'header_hamburger',
				'description' => esc_html__('Display copyright', 'dimax'),
			),

			// Header Department
			'header_department_hr'                      => array(
				'type'        => 'custom',
				'section'     => 'header_department',
				'default'     => '<hr>',
				'description' => esc_html__('Go to Appearance > Menus > create a new menu and check to Department Menu location', 'dimax'),
			),
			'header_department_text'                    => array(
				'type'    => 'text',
				'label'   => esc_html__('Text', 'dimax'),
				'section' => 'header_department',
				'default' => '',
			),
			'header_department_custom_field_1'          => array(
				'type'    => 'custom',
				'section' => 'header_department',
				'default' => '<hr/>',
			),
			'header_department_custom_color'            => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Custom Color', 'dimax'),
				'section'     => 'header_department',
				'default'     => 0,
				'description' => esc_html__('Change color header department', 'dimax'),
			),
			'header_department_background_color'        => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_department',
				'active_callback' => array(
					array(
						'setting'  => 'header_department_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-department',
						'property' => '--rz-header-background-color-dark',
					),
				),
			),
			'header_department_text_color'              => array(
				'type'            => 'color',
				'label'           => esc_html__('Text Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_department',
				'active_callback' => array(
					array(
						'setting'  => 'header_department_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-department',
						'property' => '--rz-header-color-light',
					),
				),
			),
			'header_department_border_color'            => array(
				'type'            => 'color',
				'label'           => esc_html__('Border Color', 'dimax'),
				'default'         => '',
				'transport'       => 'postMessage',
				'section'         => 'header_department',
				'active_callback' => array(
					array(
						'setting'  => 'header_department_custom_color',
						'operator' => '==',
						'value'    => '1',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.header-department',
						'property' => 'border-color',
					),
				),
			),
			'header_custom_text' => array(
				'type'        => 'textarea',
				'label'       => esc_html__('Custom Text', 'dimax'),
				'description' => esc_html__('The content of the Custom Text item', 'dimax'),
				'section'     => 'header_custom_text',
			),

			// Blog
			'blog_type'                                 => array(
				'type'        => 'radio',
				'label'       => esc_html__('Blog Type', 'dimax'),
				'description' => esc_html__('The layout of blog posts', 'dimax'),
				'default'     => 'listing',
				'choices'     => array(
					'listing' => esc_attr__('Listing', 'dimax'),
					'grid'    => esc_attr__('Grid', 'dimax'),
				),
				'section' => 'blog_page',
			),

			'blog_columns' => array(
				'type'     => 'select',
				'label'    => esc_html__('Columns', 'dimax'),
				'section'  => 'blog_page',
				'default'  => '3',
				'priority' => 10,
				'choices'  => array(
					'2' => esc_html__('2 Columns', 'dimax'),
					'3' => esc_html__('3 Columns', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_type',
						'operator' => '==',
						'value'    => 'grid',
					),
				),
			),

			'blog_layout' => array(
				'type'        => 'select',
				'label'       => esc_html__('Blog Layout', 'dimax'),
				'section'     => 'blog_page',
				'default'     => 'content-sidebar',
				'tooltip'     => esc_html__('Go to appearance > widgets find to blog sidebar to edit your sidebar', 'dimax'),
				'priority'    => 10,
				'description' => esc_html__('Select default sidebar for the single post page.', 'dimax'),
				'choices'     => array(
					'content-sidebar' => esc_html__('Right Sidebar', 'dimax'),
					'sidebar-content' => esc_html__('Left Sidebar', 'dimax'),
					'full-content'    => esc_html__('Full Content', 'dimax'),
				),
			),

			'excerpt_length' => array(
				'type'     => 'number',
				'label'    => esc_html__('Excerpt Length', 'dimax'),
				'section'  => 'blog_page',
				'default'  => 20,
				'priority' => 10,
			),

			'blog_view_more_text'                   => array(
				'type'     => 'text',
				'label'    => esc_html__('Loading Text', 'dimax'),
				'section'  => 'blog_page',
				'default'  => '',
				'priority' => 10,
			),

			// Categories Filter
			'blog_categories_filter_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'blog_page',
				'default' => '<hr/><h2>' . esc_html__('Categories Filter', 'dimax') . '</h2>',
			),
			'show_blog_cats'                        => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable', 'dimax'),
				'section'     => 'blog_page',
				'default'     => 0,
				'description' => esc_html__('Display categories list above posts list', 'dimax'),
				'priority'    => 10,
			),

			'custom_blog_cats' => array(
				'type'     => 'toggle',
				'label'    => esc_html__('Custom Categories List', 'dimax'),
				'section'  => 'blog_page',
				'default'  => 0,
				'priority' => 10,
			),
			'blog_cats_slug'   => array(
				'type'            => 'select',
				'section'         => 'blog_page',
				'label'           => esc_html__('Custom Categories', 'dimax'),
				'description'     => esc_html__('Select product categories you want to show.', 'dimax'),
				'default'         => '',
				'multiple'        => 999,
				'priority'        => 10,
				'choices'         => $this->get_categories('category'),
				'active_callback' => array(
					array(
						'setting'  => 'custom_blog_cats',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'blog_cats_view_all'     => array(
				'type'     => 'text',
				'label'    => esc_html__('View All Text', 'dimax'),
				'section'  => 'blog_page',
				'default'  => '',
				'priority' => 10,
			),
			'blog_cats_orderby'      => array(
				'type'     => 'select',
				'label'    => esc_html__('Categories Orderby', 'dimax'),
				'section'  => 'blog_page',
				'default'  => 'count',
				'priority' => 10,
				'choices'  => array(
					'count' => esc_html__('Count', 'dimax'),
					'title' => esc_html__('Title', 'dimax'),
					'id'    => esc_html__('ID', 'dimax'),
				),
			),
			'blog_cats_order'        => array(
				'type'     => 'select',
				'label'    => esc_html__('Categories Order', 'dimax'),
				'section'  => 'blog_page',
				'default'  => 'DESC',
				'priority' => 10,
				'choices'  => array(
					'DESC' => esc_html__('DESC', 'dimax'),
					'ASC'  => esc_html__('ASC', 'dimax'),
				),
			),
			'blog_cats_number'       => array(
				'type'     => 'number',
				'label'    => esc_html__('Categories Number', 'dimax'),
				'section'  => 'blog_page',
				'default'  => 6,
				'priority' => 10,
			),

			// Single Post
			'single_post_breadcrumb' => array(
				'type'     => 'toggle',
				'default'  => 1,
				'label'    => esc_html__('Enable breadcrumb', 'dimax'),
				'section'  => 'single_post',
				'priority' => 10,
			),

			'single_post_featured' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable featured image', 'dimax'),
				'default'     => '1',
				'section'     => 'single_post',
				'description' => esc_html__('Display the featured image on the post', 'dimax'),
			),

			'single_post_layout' => array(
				'type'        => 'select',
				'label'       => esc_html__('Layout', 'dimax'),
				'section'     => 'single_post',
				'default'     => 'full-content',
				'tooltip'     => esc_html__('Go to appearance > widgets find to blog sidebar to edit your sidebar', 'dimax'),
				'priority'    => 10,
				'description' => esc_html__('Select default sidebar for the single post page.', 'dimax'),
				'choices'     => array(
					'content-sidebar' => esc_html__('Right Sidebar', 'dimax'),
					'sidebar-content' => esc_html__('Left Sidebar', 'dimax'),
					'full-content'    => esc_html__('Full Content', 'dimax'),
				),
			),

			'post_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'single_post',
				'default' => '<hr/>',
			),

			'post_socials_toggle' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Socials Share', 'dimax'),
				'description' => esc_html__('Check this option to show socials share in the single post.', 'dimax'),
				'section'     => 'single_post',
				'default'     => 0,
				'priority'    => 10,
			),

			'post_socials_share'           => array(
				'type'    => 'multicheck',
				'label'   => esc_html__('Socials List', 'dimax'),
				'section' => 'single_post',
				'default' => array('facebook', 'twitter', 'googleplus', 'tumblr'),
				'choices' => array(
					'facebook'   => esc_html__('Facebook', 'dimax'),
					'twitter'    => esc_html__('Twitter', 'dimax'),
					'googleplus' => esc_html__('Google Plus', 'dimax'),
					'tumblr'     => esc_html__('Tumblr', 'dimax'),
					'pinterest'  => esc_html__('Pinterest', 'dimax'),
					'linkedin'   => esc_html__('Linkedin', 'dimax'),
					'reddit'     => esc_html__('Reddit', 'dimax'),
					'telegram'   => esc_html__('Telegram', 'dimax'),
					'pocket'     => esc_html__('Pocket', 'dimax'),
					'email'      => esc_html__('Email', 'dimax'),
				),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'post_socials_toggle',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Related Posts
			'related_posts_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'single_post',
				'default' => '<hr/><h2>' . esc_html__('Related Posts', 'dimax') . '</h2>',
			),

			'related_posts'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Enable', 'dimax'),
				'description' => esc_html__('Check this option to show related posts.', 'dimax'),
				'section'     => 'single_post',
				'default'     => 0,
				'priority'    => 10,
			),
			'related_posts_title'       => array(
				'type'     => 'text',
				'label'    => esc_html__('Title', 'dimax'),
				'section'  => 'single_post',
				'default'  => '',
				'priority' => 10,

			),
			'related_posts_numbers'     => array(
				'type'        => 'number',
				'label'       => esc_html__('Numbers', 'dimax'),
				'description' => esc_html__('How many related posts would you like to show', 'dimax'),
				'section'     => 'single_post',
				'default'     => 3,
				'priority'    => 10,

			),
			'related_posts_columns'     => array(
				'type'    => 'select',
				'label'   => esc_html__('Columns', 'dimax'),
				'section' => 'single_post',
				'default' => '3',
				'choices' => array(
					'4' => esc_html__('4 Columns', 'dimax'),
					'3' => esc_html__('3 Columns', 'dimax'),
					'2' => esc_html__('2 Columns', 'dimax'),
				),
				'priority' => 10,

			),

			// Footer Layout
			'footer_sections'           => array(
				'type'        => 'sortable',
				'label'       => esc_html__('Footer Sections', 'dimax'),
				'description' => esc_html__('Select and order footer contents', 'dimax'),
				'default'     => '',
				'choices'     => array(
					'newsletter' => esc_attr__('Newsletter', 'dimax'),
					'extra'      => esc_attr__('Extra Content', 'dimax'),
					'widgets'    => esc_attr__('Footer Widgets', 'dimax'),
					'main'       => esc_attr__('Footer Main', 'dimax'),
				),
				'section' => 'footer_layout',
			),
			'footer_layout_custom_hr_1' => array(
				'type'    => 'custom',
				'default' => '<hr/>',
				'section' => 'footer_layout',
			),
			'footer_container'          => array(
				'type'        => 'select',
				'label'       => esc_html__('Footer Width', 'dimax'),
				'description' => esc_html__('Select the width of footer container', 'dimax'),
				'default'     => 'container',
				'choices'     => array(
					'container'       => esc_attr__('Normal', 'dimax'),
					'dimax-container' => esc_attr__('Large', 'dimax'),
					'dimax-container-wide'  => esc_html__('Wide', 'dimax'),
				),
				'section' => 'footer_layout',
			),
			'footer_layout_custom_hr_2' => array(
				'type'    => 'custom',
				'default' => '<hr/>',
				'section' => 'footer_layout',
			),
			'footer_section_border_top'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Border Top', 'dimax'),
				'description' => esc_html__('Display a divide line on top', 'dimax'),
				'section'     => 'footer_layout',
				'default'     => '',
			),
			'footer_section_border_color' => array(
				'label'     => esc_html__('Border Color', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '.site-footer.has-divider',
						'property' => 'border-color',
					),
				),
				'section' => 'footer_layout',
				'active_callback' => array(
					array(
						'setting'  => 'footer_section_border',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			// Footer Widget
			'footer_widgets_layout'     => array(
				'type'        => 'select',
				'tooltip'     => esc_html__('Go to appearance > widgets find to footer sidebar to edit your sidebar', 'dimax'),
				'label'       => esc_html__('Layout', 'dimax'),
				'description' => esc_html__('Select number of columns for displaying widgets', 'dimax'),
				'default'     => '4-columns',
				'choices'     => array(
					'2-columns'      => esc_html__('2 Columns', 'dimax'),
					'3-columns'      => esc_html__('3 Columns', 'dimax'),
					'4-columns'      => esc_html__('4 Columns', 'dimax'),
					'5-columns'      => esc_html__('5 Columns', 'dimax'),
				),
				'section' => 'footer_widget',
			),

			'footer_widget_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'footer_widget',
				'default' => '<hr/>',
			),

			'footer_widget_border'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Border Line', 'dimax'),
				'description' => esc_html__('Display a divide line on top', 'dimax'),
				'default'     => true,
				'section'     => 'footer_widget',
			),
			'footer_widget_border_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Border Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.footer-widgets.has-divider',
						'property' => '--rz-footer-widget-border-color',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'footer_widget_border',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'footer_widget',
			),

			'footer_widget_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'footer_widget',
				'default' => '<hr/><h2>' . esc_html__('Custom Padding', 'dimax') . '</h2>',
			),

			'footer_widget_padding_top' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Top', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_widget',
				'default'   => '64',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.footer-widgets',
						'property' => '--rz-footer-widget-top-spacing',
						'units'    => 'px',
					),
				),
			),

			'footer_widget_padding_bottom' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Bottom', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_widget',
				'default'   => '64',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.footer-widgets',
						'property' => '--rz-footer-widget-bottom-spacing',
						'units'    => 'px',
					),
				),
			),

			// Footer Main
			'footer_main_left'             => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Left Items', 'dimax'),
				'description' => esc_html__('Control left items of the footer', 'dimax'),
				'transport'   => 'postMessage',
				'default'     => array(array('item' => 'copyright')),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->footer_items_option(),
					),
				),
				'section' => 'footer_main',
			),
			'footer_main_center'           => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Center Items', 'dimax'),
				'description' => esc_html__('Control center items of the footer', 'dimax'),
				'transport'   => 'postMessage',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->footer_items_option(),
					),
				),
				'section' => 'footer_main',
			),
			'footer_main_right'            => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Right Items', 'dimax'),
				'description' => esc_html__('Control right items of the footer', 'dimax'),
				'transport'   => 'postMessage',
				'default'     => array(array('item' => 'menu')),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'default' => 'copyright',
						'choices' => $this->footer_items_option(),
					),
				),
				'section' => 'footer_main',
			),
			'footer_main_divide_1'         => array(
				'type'    => 'custom',
				'default' => '<hr>',
				'section' => 'footer_main',
			),
			'footer_main_border'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Border Line', 'dimax'),
				'description' => esc_html__('Display a divide line on top', 'dimax'),
				'default'     => true,
				'section'     => 'footer_main',
			),
			'footer_main_border_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Border Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.footer-main.has-divider',
						'property' => '--rz-footer-main-border-color',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'footer_main_border',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'footer_main',
			),

			'footer_main_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'footer_main',
				'default' => '<hr/><h2>' . esc_html__('Custom Padding', 'dimax') . '</h2>',
			),

			'footer_main_padding_top' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Top', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_main',
				'default'   => '22',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.footer-main',
						'property' => '--rz-footer-main-top-spacing',
						'units'    => 'px',
					),
				),
			),

			'footer_main_padding_bottom' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Bottom', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_main',
				'default'   => '22',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.footer-main',
						'property' => '--rz-footer-main-bottom-spacing',
						'units'    => 'px',
					),
				),
			),

			// Footer Item
			'footer_copyright'           => array(
				'type'        => 'textarea',
				'label'       => esc_html__('Footer Copyright', 'dimax'),
				'description' => esc_html__('Display copyright info on the left side of footer', 'dimax'),
				'default'     => sprintf('%s %s ' . esc_html__('All rights reserved', 'dimax'), '&copy;' . date('Y'), get_bloginfo('name')),
				'section'     => 'footer_copyright',
			),

			'footer_menu'       => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Menu', 'dimax' ),
				'section'         => 'footer_menu',
				'default'         => '',
				'choices'         => $this->get_navigation_bar_get_menus(),

			),

			'footer_main_text' => array(
				'type'        => 'textarea',
				'label'       => esc_html__('Custom Text', 'dimax'),
				'description' => esc_html__('The content of the Custom Text item', 'dimax'),
				'section'     => 'footer_text',
			),

			'footer_main_payment_images' => array(
				'type'      => 'repeater',
				'label'     => esc_html__('Payment Images', 'dimax'),
				'section'   => 'footer_payment',
				'row_label' => array(
					'type'  => 'text',
					'value' => esc_html__('Image', 'dimax'),
				),
				'fields'    => array(
					'image' => array(
						'type'    => 'image',
						'label'   => esc_html__('Image', 'dimax'),
						'default' => '',
					),
					'link'  => array(
						'type'    => 'text',
						'label'   => esc_html__('Link', 'dimax'),
						'default' => '',
					),
				),
			),
			'footer_logo_type'       => array(
				'type'    => 'radio',
				'label'   => esc_html__('Logo Type', 'dimax'),
				'default' => 'text',
				'section' => 'footer_logo',
				'choices' => array(
					'image' => esc_html__('Image', 'dimax'),
					'svg'   => esc_html__('SVG', 'dimax'),
					'text'  => esc_html__('Text', 'dimax'),
				),
			),

			'footer_logo_svg'        => array(
				'type'              => 'textarea',
				'label'             => esc_html__('Logo SVG', 'dimax'),
				'section'           => 'footer_logo',
				'description'       => esc_html__('Paste SVG code of your logo here', 'dimax'),
				'sanitize_callback' => '\Dimax\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.footer-branding .logo',
					),
				),
				'active_callback'   => array(
					array(
						'setting'  => 'footer_logo_type',
						'operator' => '==',
						'value'    => 'svg',
					),
				),
			),
			'footer_logo'            => array(
				'type'            => 'image',
				'label'           => esc_html__('Logo', 'dimax'),
				'default'         => '',
				'section'         => 'footer_logo',
				'active_callback' => array(
					array(
						'setting'  => 'footer_logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),
			'footer_logo_text'       => array(
				'type'    => 'textarea',
				'label'   => esc_html__('Logo Text', 'dimax'),
				'section' => 'footer_logo',
				'output'  => array(
					array(
						'element' => '.footer-branding .logo',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'footer_logo_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'footer_logo_text_color' => array(
				'type'            => 'color',
				'label'           => esc_html__('Color', 'dimax'),
				'transport'       => 'postMessage',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'footer_logo_type',
						'operator' => '==',
						'value'    => 'text',
					),
				),
				'js_vars'         => array(
					array(
						'element'  => '.footer-branding',
						'property' => '--rz-color-dark',
					),
				),
				'section' => 'footer_logo',
			),
			'footer_logo_dimension'  => array(
				'type'    => 'dimensions',
				'label'   => esc_html__('Logo Dimension', 'dimax'),
				'default' => array(
					'width'  => '',
					'height' => '',
				),
				'section'         => 'footer_logo',
				'active_callback' => array(
					array(
						'setting'  => 'footer_logo_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),

			'general_backtotop'    => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Back To Top', 'dimax'),
				'section'     => 'general_backtotop',
				'description' => esc_html__('Check this to show back to top.', 'dimax'),
				'default'     => true,
			),

			// Footer Extra
			'footer_extra_content' => array(
				'type'        => 'repeater',
				'label'       => esc_html__('Items', 'dimax'),
				'description' => esc_html__('Control items of the extra footer', 'dimax'),
				'transport'   => 'postMessage',
				'default'     => array(array('item' => 'copyright')),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__('Item', 'dimax'),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->footer_items_option(),
					),
				),
				'section' => 'footer_extra',
			),

			'footer_extra_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'footer_extra',
				'default' => '<hr/>',
			),

			'footer_extra_border'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Border Line', 'dimax'),
				'description' => esc_html__('Display a divide line on top', 'dimax'),
				'default'     => false,
				'section'     => 'footer_extra',
			),
			'footer_extra_border_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Border Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.footer-extra.has-divider',
						'property' => '--rz-footer-extra-border-color',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'footer_extra_border',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'footer_extra',
			),

			'footer_extra_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'footer_extra',
				'default' => '<hr/><h2>' . esc_html__('Custom Padding', 'dimax') . '</h2>',
			),

			'footer_extra_padding_top' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Top', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_extra',
				'default'   => '105',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.footer-extra',
						'property' => '--rz-footer-extra-top-spacing',
						'units'    => 'px',
					),
				),
			),

			'footer_extra_padding_bottom' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Bottom', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_extra',
				'default'   => '112',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'   => array(
					array(
						'element'  => '.footer-extra',
						'property' => '--rz-footer-extra-bottom-spacing',
						'units'    => 'px',
					),
				),
			),
			// Newsletter
			'footer_newsletter_layout'       => array(
				'type'    => 'radio',
				'label'   => esc_html__('Layout', 'dimax'),
				'default' => 'v1',
				'section' => 'footer_newsletter',
				'choices' => array(
					'v1' => esc_html__('Layout 1', 'dimax'),
					'v2' => esc_html__('Layout 2', 'dimax'),
				),
			),

			'footer_newsletter_text' => array(
				'type'            => 'text',
				'label'           => esc_html__('Title', 'dimax'),
				'section'         => 'footer_newsletter',
				'default'         => '',
			),

			'footer_newsletter_form' => array(
				'type'            => 'textarea',
				'label'           => esc_html__('Form', 'dimax'),
				'description'     => esc_html__('Enter the shortcode of MailChimp form', 'dimax'),
				'section'         => 'footer_newsletter',
				'default'         => '',
			),

			'custom_newsletter_link_to_form' => array(
				'type'            => 'custom',
				'section'         => 'footer_newsletter',
				'default'         => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=mailchimp-for-wp-forms'), esc_html__('Go to MailChimp form', 'dimax')),
			),

			'footer_newsletter_custom_field_1' => array(
				'type'    => 'custom',
				'section' => 'footer_newsletter',
				'default' => '<hr/>',
			),

			'footer_newsletter_border'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Border Line', 'dimax'),
				'description' => esc_html__('Display a divide line on top', 'dimax'),
				'default'     => false,
				'section'     => 'footer_newsletter',
			),
			'footer_newsletter_border_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Border Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '.footer-newsletter.has-divider',
						'property' => '--rz-footer-newsletter-border-color',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'footer_newsletter_border',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section' => 'footer_newsletter',
			),

			'footer_newsletter_custom_field_2' => array(
				'type'            => 'custom',
				'section'         => 'footer_newsletter',
				'default'         => '<hr/><h2>' . esc_html__('Custom Padding', 'dimax') . '</h2>',
			),

			'footer_newsletter_padding_top' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Top', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_newsletter',
				'default'   => '110',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '.footer-newsletter',
						'property' => 'padding-top',
						'units'    => 'px',
					),
				),
			),

			'footer_newsletter_padding_bottom' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Bottom', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'footer_newsletter',
				'default'   => '41',
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '.footer-newsletter',
						'property' => 'padding-bottom',
						'units'    => 'px',
					),
				),
			),
			// Background
			'footer_background_scheme'       => array(
				'type'    => 'select',
				'label'   => esc_html__('Background Scheme', 'dimax'),
				'default' => 'dark',
				'section' => 'footer_background',
				'choices' => array(
					'dark'   => esc_html__('Dark', 'dimax'),
					'light'  => esc_html__('Light', 'dimax'),
					'gray'   => esc_html__('Gray', 'dimax'),
					'custom' => esc_html__('Custom', 'dimax'),
				),
			),
			'footer_bg'                        => array(
				'type'    => 'image',
				'label'   => esc_html__('Background Image', 'dimax'),
				'default' => '',
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_bg_color' => array(
				'label'     => esc_html__('Background Color', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer',
						'property' => 'background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_bg_heading_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Heading Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .newsletter-title, #site-footer .widget-title, #site-footer .logo .logo-text',
						'property' => '--rz-color-lighter',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_bg_text_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer',
						'property' => '--rz-text-color-gray',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_bg_text_color_hover' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Hover Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer',
						'property' => '--rz-text-color-hover',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_bg_custom_field_2' => array(
				'type'    => 'custom',
				'section' => 'footer_background',
				'default' => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_extra_bg_enable' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('FOOTER EXTRA', 'dimax'),
				'section'     => 'footer_background',
				'default'     => false,
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_extra_bg' => array(
				'type'    => 'image',
				'label'   => esc_html__('Background Image', 'dimax'),
				'default' => '',
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_extra_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_extra_bg_color' => array(
				'label'     => esc_html__('Background Color', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-extra',
						'property' => 'background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_extra_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_extra_text_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-extra',
						'property' => '--rz-text-color-gray',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_extra_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_extra_text_color_hover' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Hover Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-extra',
						'property' => '--rz-text-color-hover',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_extra_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_custom_field_10' => array(
				'type'    => 'custom',
				'section' => 'footer_background',
				'label'   => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_newsletter_bg_enable' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('FOOTER NEWSLETTER', 'dimax'),
				'section'     => 'footer_background',
				'default'     => false,
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_newsletter_bg' => array(
				'type'    => 'image',
				'label'   => esc_html__('Background Image', 'dimax'),
				'default' => '',
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_bg_color' => array(
				'label'     => esc_html__('Background Color', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter',
						'property' => 'background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_heading_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Heading Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .newsletter-title',
						'property' => '--rz-color-lighter',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_form_border_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Border Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter.layout-v2',
						'property' => '--rz-textbox-bg-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_newsletter_layout',
						'operator' => '==',
						'value'    => 'v2',
					),
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_text_field_bgcolor' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Field Background Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter.layout-v1',
						'property' => '--rz-textbox-bg-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_newsletter_layout',
						'operator' => '==',
						'value'    => 'v1',
					),
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_text_field_placeholder_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Field Placehoder Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter .mc4wp-form-fields',
						'property' => '--rz-textbox-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_text_field_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Field Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter',
						'property' => '--rz-textbox-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_submit_bg_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Button Background Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter.layout-v1',
						'property' => '--rz-button-bg-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_newsletter_layout',
						'operator' => '==',
						'value'    => 'v1',
					),
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_newsletter_submit_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Button Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-newsletter',
						'property' => '--rz-button-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_newsletter_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'footer_widgets_custom_field_10' => array(
				'type'    => 'custom',
				'section' => 'footer_background',
				'label'   => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_widgets_bg_enable' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('FOOTER WIDGETS', 'dimax'),
				'section'     => 'footer_background',
				'default'     => false,
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'footer_widget_bg' => array(
				'type'    => 'image',
				'label'   => esc_html__('Background Image', 'dimax'),
				'default' => '',
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_widgets_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_widget_bg_color' => array(
				'label'     => esc_html__('Background Color', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-widgets',
						'property' => 'background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_widgets_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_widget_dropdown_border_color' => array(
				'label'     => esc_html__('Border Color Dropdown', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-widgets .widget.dropdown',
						'property' => 'border-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_widgets_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_widget_heading_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Heading Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-widgets .widget-title, #site-footer .footer-widgets .logo-text',
						'property' => '--rz-color-lighter',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_widgets_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_widget_text_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-widgets',
						'property' => '--rz-text-color-gray',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_widgets_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_widget_text_color_hover' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Hover Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-widgets',
						'property' => '--rz-text-color-hover',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_widgets_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_main_custom_field_10' => array(
				'type'    => 'custom',
				'section' => 'footer_background',
				'label'   => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),

			'footer_main_bg_enable' => array(
				'type'        => 'toggle',
				'label'       => esc_html__('FOOTER MAIN', 'dimax'),
				'section'     => 'footer_background',
				'default'     => false,
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'footer_main_bg' => array(
				'type'    => 'image',
				'label'   => esc_html__('Background Image', 'dimax'),
				'default' => '',
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_main_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_main_bg_color' => array(
				'label'     => esc_html__('Background Color', 'dimax'),
				'type'      => 'color',
				'default'   => '',
				'transport' => 'postMessage',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-main',
						'property' => 'background-color',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_main_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_main_text_color' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-main',
						'property' => '--rz-text-color-gray',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_main_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			'footer_main_text_color_hover' => array(
				'type'      => 'color',
				'label'     => esc_html__('Text Hover Color', 'dimax'),
				'transport' => 'postMessage',
				'default'   => '',
				'js_vars'   => array(
					array(
						'element'  => '#site-footer .footer-main',
						'property' => '--rz-text-color-hover',
					),
				),
				'section' => 'footer_background',
				'active_callback' => array(
					array(
						'setting'  => 'footer_background_scheme',
						'operator' => '==',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'footer_main_bg_enable',
						'operator' => '==',
						'value'    => true,
					),
				),
			),


			'page_header'             => array(
				'type'        => 'toggle',
				'default'     => 1,
				'label'       => esc_html__('Enable Page Header', 'dimax'),
				'section'     => 'page_header',
				'description' => esc_html__('Enable to show a page header for the page below the site header', 'dimax'),
				'priority'    => 10,
			),

			'page_header_custom_field_1' => array(
				'type'            => 'custom',
				'section'         => 'page_header',
				'default'         => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'page_header',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),

			'page_header_els' => array(
				'type'     => 'multicheck',
				'label'    => esc_html__('Page Header Elements', 'dimax'),
				'section'  => 'page_header',
				'default'  => array('breadcrumb', 'title'),
				'priority' => 10,
				'choices'  => array(
					'breadcrumb' => esc_html__('BreadCrumb', 'dimax'),
					'title'      => esc_html__('Title', 'dimax'),
				),
				'description'     => esc_html__('Select which elements you want to show.', 'dimax'),
				'active_callback' => array(
					array(
						'setting'  => 'page_header',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'page_header_custom_field_2' => array(
				'type'            => 'custom',
				'section'         => 'page_header',
				'default'         => '<hr/><h3>' . esc_html__('Custom Title', 'dimax') . '</h3>',
				'active_callback' => array(
					array(
						'setting'  => 'page_header',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'page_header_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),

			'page_header_padding_top' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Padding Top', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'page_header',
				'default'   => 50,
				'priority'  => 20,
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '#page-header .page-header__title',
						'property' => 'padding-top',
						'units'    => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'page_header',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'page_header_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),

			'page_header_padding_bottom'  => array(
				'type'      => 'slider',
				'label'     => esc_html__('Padding Bottom', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'page_header',
				'default'   => 50,
				'priority'  => 20,
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '#page-header .page-header__title',
						'property' => 'padding-bottom',
						'units'    => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'page_header',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'page_header_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),

			// Boxed Layout
			'boxed_layout'                => array(
				'type'     => 'toggle',
				'label'    => esc_html__('Boxed Layout', 'dimax'),
				'section'  => 'boxed_layout',
				'default'  => 0,
				'priority' => 10,
			),
			'boxed_background_color'      => array(
				'type'            => 'color',
				'label'           => esc_html__('Background Color', 'dimax'),
				'default'         => '',
				'section'         => 'boxed_layout',
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'boxed_background_image'      => array(
				'type'            => 'image',
				'label'           => esc_html__('Background Image', 'dimax'),
				'default'         => '',
				'section'         => 'boxed_layout',
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'boxed_background_horizontal' => array(
				'type'     => 'select',
				'label'    => esc_html__('Background Horizontal', 'dimax'),
				'section'  => 'boxed_layout',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					''       => esc_html__('None', 'dimax'),
					'left'   => esc_html__('Left', 'dimax'),
					'center' => esc_html__('Center', 'dimax'),
					'right'  => esc_html__('Right', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'boxed_background_vertical'   => array(
				'type'     => 'select',
				'label'    => esc_html__('Background Vertical', 'dimax'),
				'section'  => 'boxed_layout',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					''       => esc_html__('None', 'dimax'),
					'top'    => esc_html__('Top', 'dimax'),
					'center' => esc_html__('Center', 'dimax'),
					'bottom' => esc_html__('Bottom', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'boxed_background_repeat'     => array(
				'type'     => 'select',
				'label'    => esc_html__('Background Repeat', 'dimax'),
				'section'  => 'boxed_layout',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					''          => esc_html__('None', 'dimax'),
					'no-repeat' => esc_html__('No Repeat', 'dimax'),
					'repeat'    => esc_html__('Repeat', 'dimax'),
					'repeat-y'  => esc_html__('Repeat Vertical', 'dimax'),
					'repeat-x'  => esc_html__('Repeat Horizontal', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'boxed_background_attachment' => array(
				'type'     => 'select',
				'label'    => esc_html__('Background Attachment', 'dimax'),
				'section'  => 'boxed_layout',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					''       => esc_html__('None', 'dimax'),
					'scroll' => esc_html__('Scroll', 'dimax'),
					'fixed'  => esc_html__('Fixed', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			'boxed_background_size'       => array(
				'type'     => 'select',
				'label'    => esc_html__('Background Size', 'dimax'),
				'section'  => 'boxed_layout',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					''        => esc_html__('None', 'dimax'),
					'auto'    => esc_html__('Auto', 'dimax'),
					'cover'   => esc_html__('Cover', 'dimax'),
					'contain' => esc_html__('Contain', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'boxed_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Blog
			'page_header_blog'            => array(
				'type'        => 'toggle',
				'default'     => 1,
				'label'       => esc_html__('Enable Page Header', 'dimax'),
				'section'     => 'page_header_blog',
				'description' => esc_html__('Enable to show a page header for the page below the site header', 'dimax'),
				'priority'    => 10,
			),

			'page_header_blog_custom_field_1' => array(
				'type'            => 'custom',
				'section'         => 'page_header_blog',
				'default'         => '<hr/>',
				'active_callback' => array(
					array(
						'setting'  => 'page_header_blog',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),

			'page_header_blog_els' => array(
				'type'     => 'multicheck',
				'label'    => esc_html__('Page Header Elements', 'dimax'),
				'section'  => 'page_header_blog',
				'default'  => array('breadcrumb', 'title'),
				'priority' => 10,
				'choices'  => array(
					'breadcrumb' => esc_html__('BreadCrumb', 'dimax'),
					'title'      => esc_html__('Title', 'dimax'),
				),
				'description'     => esc_html__('Select which elements you want to show.', 'dimax'),
				'active_callback' => array(
					array(
						'setting'  => 'page_header_blog',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'page_header_blog_custom_field_2' => array(
				'type'            => 'custom',
				'section'         => 'page_header_blog',
				'default'         => '<hr/><h3>' . esc_html__('Custom Title', 'dimax') . '</h3>',
				'active_callback' => array(
					array(
						'setting'  => 'page_header_blog',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'page_header_blog_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),

			'page_header_blog_padding_top' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Padding Top', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'page_header_blog',
				'default'   => 50,
				'priority'  => 20,
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '.dimax-blog-page #page-header .page-header__title',
						'property' => 'padding-top',
						'units'    => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'page_header_blog',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'page_header_blog_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),

			'page_header_blog_padding_bottom' => array(
				'type'      => 'slider',
				'label'     => esc_html__('Padding Bottom', 'dimax'),
				'transport' => 'postMessage',
				'section'   => 'page_header_blog',
				'default'   => 50,
				'priority'  => 20,
				'choices'   => array(
					'min' => 0,
					'max' => 500,
				),
				'js_vars'         => array(
					array(
						'element'  => '.dimax-blog-page #page-header .page-header__title',
						'property' => 'padding-bottom',
						'units'    => 'px',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'page_header_blog',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'page_header_blog_els',
						'operator' => 'in',
						'value'    => 'title',
					),
				),
			),

			// Recently viewed
			'recently_viewed_enable'          => array(
				'type'        => 'toggle',
				'label'       => esc_html__('Recently Viewed', 'dimax'),
				'section'     => 'recently_viewed',
				'default'     => 0,
				'description' => esc_html__('Check this option to show the recently viewed products above the footer.', 'dimax'),
			),

			'recently_viewed_ajax' => array(
				'type'    => 'toggle',
				'label'   => esc_html__('Load With Ajax', 'dimax'),
				'section' => 'recently_viewed',
				'default' => 0,
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),

			'recently_viewed_empty' => array(
				'type'            => 'toggle',
				'label'           => esc_html__('Hide Empty Products', 'dimax'),
				'section'         => 'recently_viewed',
				'default'         => 1,
				'description'     => esc_html__('Check this option to hide the recently viewed products when empty.', 'dimax'),
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),

			'recently_viewed_display_page' => array(
				'type'     => 'multicheck',
				'label'    => esc_html__('Display On Page', 'dimax'),
				'section'  => 'recently_viewed',
				'default'  => array('single'),
				'priority' => 10,
				'choices'  => array(
					'single'   => esc_html__('Single Product', 'dimax'),
					'catalog'  => esc_html__('Catalog Page', 'dimax'),
					'cart'     => esc_html__('Cart Page', 'dimax'),
					'checkout' => esc_html__('Checkout Page', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'recently_viewed_custom' => array(
				'type'    => 'custom',
				'section' => 'recently_viewed',
				'default' => '<hr/>',
			),

			'recently_viewed_layout' => array(
				'type'    => 'select',
				'label'   => esc_html__('Layout', 'dimax'),
				'section' => 'recently_viewed',
				'default' => 'default',
				'choices' => array(
					'default' => esc_html__('Default', 'dimax'),
					'effect'  => esc_html__('Effect Hover', 'dimax'),
				),
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'recently_viewed_title' => array(
				'type'            => 'text',
				'label'           => esc_html__('Title', 'dimax'),
				'section'         => 'recently_viewed',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'recently_viewed_button_text' => array(
				'type'            => 'text',
				'label'           => esc_html__('Button Text', 'dimax'),
				'section'         => 'recently_viewed',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'recently_viewed_button_link' => array(
				'type'            => 'text',
				'label'           => esc_html__('Button Link', 'dimax'),
				'section'         => 'recently_viewed',
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			'recently_viewed_columns' => array(
				'type'            => 'number',
				'label'           => esc_html__('Columns', 'dimax'),
				'section'         => 'recently_viewed',
				'default'         => 4,
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'description' => esc_html__('Specify how many numbers of recently viewed products you want to show on page', 'dimax'),
			),

			'recently_viewed_numbers' => array(
				'type'            => 'number',
				'label'           => esc_html__('Numbers', 'dimax'),
				'section'         => 'recently_viewed',
				'default'         => 6,
				'active_callback' => array(
					array(
						'setting'  => 'recently_viewed_enable',
						'operator' => '==',
						'value'    => 1,
					),
				),
				'description' => esc_html__('Specify how many numbers of recently viewed products you want to show on page', 'dimax'),
			),
			// Topbar Mobile
			'mobile_topbar' => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Topbar', 'dimax' ),
				'description' => esc_html__( 'Display topbar on mobile', 'dimax' ),
				'default'     => false,
				'section'     => 'mobile_topbar',
			),

			'mobile_topbar_items'               => array(
				'type'            => 'repeater',
				'label'           => esc_html__( 'List Items', 'dimax' ),
				'description'     => esc_html__( 'Control items on the topbar mobile', 'dimax' ),
				'transport'       => 'postMessage',
				'default'         => array(),
				'section'         => 'mobile_topbar',
				'row_label'       => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'dimax' ),
					'field' => 'item',
				),
				'fields'          => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->topbar_items_option(),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'mobile_topbar',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'mobile_topbar_custom_field_1'      => array(
				'type'    => 'custom',
				'section' => 'mobile_topbar',
				'default' => '<hr/>',
			),
			'mobile_topbar_svg_code'            => array(
				'type'              => 'textarea',
				'label'             => esc_html__( 'SVG code', 'dimax' ),
				'section'           => 'mobile_topbar',
				'description'       => esc_html__( 'The SVG before your text', 'dimax' ),
				'default'           => '',
				'sanitize_callback' => '\Dimax\Icon::sanitize_svg',
				'output'            => array(
					array(
						'element' => '.dimax-topbar__text .dimax-svg-icon',
					),
				),
				'active_callback'   => array(
					array(
						'setting'  => 'mobile_topbar',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'mobile_topbar_text'                => array(
				'type'            => 'editor',
				'label'           => esc_html__( 'Custom Text', 'dimax' ),
				'section'         => 'mobile_topbar',
				'description'     => esc_html__( 'The content of Custom Text item', 'dimax' ),
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'mobile_topbar',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'mobile_header_height'              => array(
				'type'      => 'slider',
				'label'     => esc_html__( 'Header Height', 'dimax' ),
				'section'   => 'mobile_header',
				'transport' => 'postMessage',
				'default'   => '60',
				'choices'   => array(
					'min' => 40,
					'max' => 300,
				),
				'js_vars'   => array(
					array(
						'element'  => '.header-mobile',
						'property' => 'height',
						'units'    => 'px',
					),
				),
			),
			'mobile_header_custom_field_2'      => array(
				'type'    => 'custom',
				'section' => 'mobile_header',
				'default' => '<hr/>',
			),
			'mobile_header_icons'               => array(
				'type'        => 'repeater',
				'label'       => esc_html__( 'Header Icons', 'dimax' ),
				'section'     => 'mobile_header',
				'description' => esc_html__( 'Control icons on the right side of mobile header', 'dimax' ),
				'transport'   => 'postMessage',
				'default'     => array( array( 'item' => 'search' ), array( 'item' => 'cart' ) ),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'dimax' ),
					'field' => 'item',
				),
				'fields'      => array(
					'item' => array(
						'type'    => 'select',
						'choices' => $this->mobile_header_icons_option(),
					),
				),
			),

				// Page
				'mobile_page_header_hr'             => array(
					'type'    => 'custom',
					'section' => 'mobile_page',
					'default' => '<hr/><h2>' . esc_html__( 'Page Header', 'dimax' ) . '</h2>',
				),
				'mobile_page_header'                => array(
					'type'        => 'toggle',
					'default'     => 1,
					'label'       => esc_html__( 'Enable Page Header', 'dimax' ),
					'section'     => 'mobile_page',
					'description' => esc_html__( 'Enable to show a page header for the page below the site header', 'dimax' ),
				),
				'mobile_page_header_els'            => array(
					'type'            => 'multicheck',
					'label'           => esc_html__( 'Page Header Elements', 'dimax' ),
					'section'         => 'mobile_page',
					'default'         => array( 'breadcrumb', 'title' ),
					'priority'        => 10,
					'choices'         => array(
						'breadcrumb' => esc_html__( 'BreadCrumb', 'dimax' ),
						'title'      => esc_html__( 'Title', 'dimax' ),
					),
					'description'     => esc_html__( 'Select which elements you want to show.', 'dimax' ),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_page_header',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),

				// Blog
				'mobile_blog_page_header_hr'        => array(
					'type'    => 'custom',
					'section' => 'mobile_blog',
					'default' => '<hr/><h2>' . esc_html__( 'Page Header', 'dimax' ) . '</h2>',
				),
				'mobile_blog_page_header'           => array(
					'type'        => 'toggle',
					'default'     => 1,
					'label'       => esc_html__( 'Enable Page Header', 'dimax' ),
					'section'     => 'mobile_blog',
					'description' => esc_html__( 'Enable to show a page header for the page below the site header', 'dimax' ),
				),
				'mobile_blog_page_header_els'       => array(
					'type'            => 'multicheck',
					'label'           => esc_html__( 'Page Header Elements', 'dimax' ),
					'section'         => 'mobile_blog',
					'default'         => array( 'breadcrumb', 'title' ),
					'priority'        => 10,
					'choices'         => array(
						'breadcrumb' => esc_html__( 'BreadCrumb', 'dimax' ),
						'title'      => esc_html__( 'Title', 'dimax' ),
					),
					'description'     => esc_html__( 'Select which elements you want to show.', 'dimax' ),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_page_header',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),

				// Single Post
				'mobile_single_post_breadcrumb'    => array(
					'type'    => 'toggle',
					'default' => 1,
					'label'   => esc_html__( 'Enable breadcrumb', 'dimax' ),
					'section' => 'mobile_single_blog',
				),

				// Mobile Footer
				'mobile_footer_newsletter'          => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Footer Newsletter', 'dimax' ),
					'description' => esc_html__( 'Display footer newsletter on mobile', 'dimax' ),
					'default'     => true,
					'section'     => 'mobile_footer',
				),
				'mobile_footer_newsletter_padding_top' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Top', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 30,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-newsletter',
							'property' => '--rz-footer-newsletter-top-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_newsletter',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
				'mobile_footer_newsletter_padding_bottom' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Bottom', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 40,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-newsletter',
							'property' => '--rz-footer-newsletter-bottom-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_newsletter',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),

				'mobile_footer_custom_field_1' => array(
					'type'            => 'custom',
					'section'         => 'mobile_footer',
					'default' 		  => '<hr/>',
				),

				'mobile_footer_widget'              => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Footer Widget', 'dimax' ),
					'description' => esc_html__( 'Display footer widget on mobile', 'dimax' ),
					'default'     => true,
					'section'     => 'mobile_footer',
				),
				'mobile_footer_widget_padding_top' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Top', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 30,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-widgets',
							'property' => '--rz-footer-widget-top-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_widget',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
				'mobile_footer_widget_padding_bottom' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Bottom', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 40,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-widgets',
							'property' => '--rz-footer-widget-bottom-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_widget',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),

				'mobile_footer_custom_field_2' => array(
					'type'            => 'custom',
					'section'         => 'mobile_footer',
					'default' 		  => '<hr/>',
				),

				'mobile_footer_main'                => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Footer Main', 'dimax' ),
					'description' => esc_html__( 'Display footer main on mobile', 'dimax' ),
					'default'     => true,
					'section'     => 'mobile_footer',
				),
				'mobile_footer_main_padding_top' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Top', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 30,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-main',
							'property' => '--rz-footer-main-top-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_main',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
				'mobile_footer_main_padding_bottom' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Bottom', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 40,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-main',
							'property' => '--rz-footer-main-bottom-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_main',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),

				'mobile_footer_custom_field_3' => array(
					'type'            => 'custom',
					'section'         => 'mobile_footer',
					'default' 		  => '<hr/>',
				),

				'mobile_footer_extra'               => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Footer Extra', 'dimax' ),
					'description' => esc_html__( 'Display footer extra on mobile', 'dimax' ),
					'default'     => true,
					'section'     => 'mobile_footer',
				),
				'mobile_footer_extra_padding_top' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Top', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 30,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-extra',
							'property' => '--rz-footer-extra-top-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_extra',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
				'mobile_footer_extra_padding_bottom' => array(
					'type'      => 'slider',
					'label'     => esc_html__('Padding Bottom', 'dimax'),
					'transport' => 'postMessage',
					'section'   => 'mobile_footer',
					'default'   => 40,
					'choices'   => array(
						'min' => 0,
						'max' => 500,
					),
					'js_vars'         => array(
						array(
							'element'  => '.site-footer .footer-extra',
							'property' => '--rz-footer-extra-bottom-spacing',
							'units'    => 'px',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_footer_extra',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),

				// Mobile Logo
				'mobile_menu_left'                  => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Show Menu Left', 'dimax' ),
					'section'     => 'mobile_header',
					'default'     => true,
				),
				'mobile_header_history_back'            => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Back to history', 'dimax' ),
					'section' => 'mobile_header',
					'description' => esc_html__( 'Show back icon in the inner pages', 'dimax' ),
					'default' => 0,
				),
				'mobile_logo_custom_field_20'        => array(
					'type'    => 'custom',
					'section' => 'mobile_header',
					'default' => '<hr/>',
				),
				'mobile_custom_logo'                => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Mobile Logo', 'dimax' ),
					'section'     => 'mobile_header',
					'description' => esc_html__( 'Use a different logo on mobile', 'dimax' ),
					'default'     => false,
				),
				'mobile_logo'                       => array(
					'type'            => 'image',
					'section'         => 'mobile_header',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_custom_logo',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
				'mobile_logo_light'                       => array(
					'label'           => esc_html__( 'Logo Light', 'dimax' ),
					'type'            => 'image',
					'section'         => 'mobile_header',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_custom_logo',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
				'mobile_logo_dimension'                            => array(
					'type'    => 'dimensions',
					'label'   => esc_html__('Logo Dimension', 'dimax'),
					'default' => array(
						'width'  => '',
						'height' => '',
					),
					'section'         => 'mobile_header',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_custom_logo',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
				'mobile_logo_custom_field_2'        => array(
					'type'    => 'custom',
					'section' => 'mobile_header',
					'default' => '<hr/>',
				),
				// Mobile Menu
				'mobile_menu_custom_field_1'        => array(
					'type'    => 'custom',
					'section' => 'mobile_header',
					'default' => '<h2>' . esc_html__( 'Header Menu Panel', 'dimax' ) . '</h2>',
				),
				'mobile_menu_click_item'            => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Show Sub-Menus', 'dimax' ),
					'section' => 'mobile_header',
					'default' => 'click-item',
					'choices' => array(
						'click-item' => esc_html__( 'Click to item', 'dimax' ),
						'click-icon' => esc_html__( 'Click to icon', 'dimax' ),
					),
				),
				'mobile_menu_show_socials'          => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Show Socials', 'dimax' ),
					'default' => '0',
					'section' => 'mobile_header',
				),
				'mobile_menu_show_copyright'        => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Show Copyright', 'dimax' ),
					'default' => '0',
					'section' => 'mobile_header',
				),
				'mobile_campaign_bar'            => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Campaign Bar', 'dimax' ),
					'section' => 'mobile_header_campaign',
					'default' => false,
				),
				'mobile_newsletter_popup'        => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Newsletter_Popup', 'dimax' ),
					'section' => 'mobile_newsletter_popup',
					'default' => true,
				),
				// Panel
				'mobile_panel_custom_logo'                => array(
					'type'        => 'toggle',
					'label'       => esc_html__( 'Custom Logo', 'dimax' ),
					'section'     => 'mobile_panel',
					'description' => esc_html__( 'Use a different logo on mobile panel', 'dimax' ),
					'default'     => false,
				),
				'mobile_panel_logo'                       => array(
					'type'            => 'image',
					'section'         => 'mobile_panel',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_panel_custom_logo',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
				'mobile_panel_logo_dimension'                            => array(
					'type'    => 'dimensions',
					'label'   => esc_html__('Logo Dimension', 'dimax'),
					'default' => array(
						'width'  => '',
						'height' => '',
					),
					'section'         => 'mobile_panel',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_panel_custom_logo',
							'operator' => '==',
							'value'    => true,
						),
					),
				),
				// Catalog
				'mobile_catalog_product_loop_hr' => array(
					'type'            => 'custom',
					'section'         => 'mobile_product_catalog',
					'default'         => '<hr/><h2>' . esc_html__( 'Product Loop', 'dimax' ) . '</h2>',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => 'in',
							'value'    => array( '1', '2', '3', '4', '6', '7', '8', '9' ),
						),
					),
				),
				'mobile_product_featured_icons'  => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Always Show Featured Icons', 'dimax' ),
					'default'         => '0',
					'section'         => 'mobile_product_catalog',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => 'in',
							'value'    => array( '1', '2', '3', '4', '6', '7', '8', '9' ),
						),
					),
				),
				'mobile_product_loop_atc'        => array(
					'type'            => 'toggle',
					'label'           => esc_html__( 'Show Add To Cart Button', 'dimax' ),
					'default'         => '0',
					'section'         => 'mobile_product_catalog',
					'active_callback' => array(
						array(
							'setting'  => 'product_loop_layout',
							'operator' => 'in',
							'value'    => array( '1', '2', '4', '7' ),
						),
					),
				),

				'shop_products_hr_4' => array(
					'type'    => 'custom',
					'default' => '<hr/><h2>' . esc_html__( 'Product Columns', 'dimax' ) . '</h2>',
					'section' => 'mobile_product_catalog',
				),

				'mobile_landscape_product_columns'     => array(
					'label'   => esc_html__( 'Mobile Landscape(767px)', 'dimax' ),
					'section' => 'mobile_product_catalog',
					'type'    => 'select',
					'default' => '3',
					'choices' => array(
						'1' => esc_attr__( '1 Column', 'dimax' ),
						'2' => esc_attr__( '2 Columns', 'dimax' ),
						'3' => esc_attr__( '3 Columns', 'dimax' ),
					),
				),
				'mobile_portrait_product_columns'      => array(
					'label'   => esc_html__( 'Mobile Portrait(479px)', 'dimax' ),
					'section' => 'mobile_product_catalog',
					'type'    => 'select',
					'default' => '2',
					'choices' => array(
						'1' => esc_attr__( '1 Column', 'dimax' ),
						'2' => esc_attr__( '2 Columns', 'dimax' ),
						'3' => esc_attr__( '3 Columns', 'dimax' ),
					),
				),
				'mobile_catalog_page_header_hr'        => array(
					'type'    => 'custom',
					'section' => 'mobile_product_catalog',
					'default' => '<hr/><h2>' . esc_html__( 'Page Header', 'dimax' ) . '</h2>',
				),
				'mobile_catalog_page_header'           => array(
					'type'        => 'toggle',
					'default'     => 1,
					'label'       => esc_html__( 'Enable Page Header', 'dimax' ),
					'section'     => 'mobile_product_catalog',
					'description' => esc_html__( 'Enable to show a page header for the shop page below the site header', 'dimax' ),
				),
				'mobile_catalog_page_header_els'       => array(
					'type'            => 'multicheck',
					'label'           => esc_html__( 'Page Header Elements', 'dimax' ),
					'section'         => 'mobile_product_catalog',
					'default'         => array( 'breadcrumb', 'title' ),
					'priority'        => 10,
					'choices'         => array(
						'breadcrumb' => esc_html__( 'BreadCrumb', 'dimax' ),
						'title'      => esc_html__( 'Title', 'dimax' ),
					),
					'description'     => esc_html__( 'Select which elements you want to show.', 'dimax' ),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_catalog_page_header',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),
				'shop_products_hr_5' => array(
					'type'    => 'custom',
					'default' => '<hr/><h2>' . esc_html__( 'Catalog Toolbar', 'dimax' ) . '</h2>',
					'section' => 'mobile_product_catalog',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_navigation_bar',
							'operator' => 'in',
							'value'    => array( 'none', 'standard', 'simple' ),
						),
					),
				),
				'mobile_filter_label'    => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Filter Label', 'dimax' ),
					'section'         => 'mobile_product_catalog',
					'active_callback' => array(
						array(
							'setting'  => 'mobile_navigation_bar',
							'operator' => 'in',
							'value'    => array( 'none', 'standard', 'simple' ),
						),
					),
				),
				// Single Product
				'mobile_single_product_breadcrumb'    => array(
					'type'        => 'toggle',
					'default'     => 1,
					'label'       => esc_html__( 'Enable Breadcrumb', 'dimax' ),
					'section'     => 'mobile_single_product',
					'description' => esc_html__( 'Enable to show a page header for the single product page below the site header', 'dimax' ),
				),
				'mobile_product_tabs_status'           => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Product Tabs Status', 'dimax' ),
					'default' => 'close',
					'section' => 'mobile_single_product',
					'choices' => array(
						'close' => esc_html__( 'Close all tabs', 'dimax' ),
						'first' => esc_html__( 'Open first tab', 'dimax' ),
					),
				),

				'mobile_version'                        => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Mobile Version', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 'yes',
				),
				'mobile_version_custom_1'               => array(
					'type'    => 'custom',
					'section' => 'mobile_version',
					'default' => '<hr/>',
				),
				'custom_mobile_homepage'                       => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Custom Homepage', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 0,
				),
				'mobile_homepage_id'                       => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Homepage', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 'homepage-mobile',
					'choices' => class_exists( 'Kirki_Helper' ) && is_admin() ? \Kirki_Helper::get_posts( array(
						'posts_per_page' => - 1,
						'post_type'      => 'page',
					) ) : '',
					'active_callback' => array(
						array(
							'setting'  => 'custom_mobile_homepage',
							'operator' => '==',
							'value'    => 1,
						),
					),
				),
				'mobile_version_custom_2'               => array(
					'type'    => 'custom',
					'section' => 'mobile_version',
					'default' => '<hr/>',
				),
				'mobile_navigation_bar'                 => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Navigation Bar', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 'none',
					'choices' => array(
						'none'              => esc_html__( 'None', 'dimax' ),
						'simple'            => esc_html__( 'Simple', 'dimax' ),
						'simple_adoptive'   => esc_html__( 'Simple Adaptive', 'dimax' ),
						'standard'          => esc_html__( 'Standard', 'dimax' ),
						'standard_adoptive' => esc_html__( 'Standard Adaptive', 'dimax' ),
					),
				),
				'mobile_navigation_bar_items'           => array(
					'type'            => 'sortable',
					'label'           => esc_html__( 'Items', 'dimax' ),
					'section'         => 'mobile_version',
					'default'         => array( 'home', 'menu', 'search', 'account' ),
					'choices'         => $this->navigation_bar_items_option(),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_navigation_bar',
							'operator' => 'in',
							'value'    => array( 'standard', 'standard_adoptive' ),
						),
					),

				),
				'mobile_navigation_bar_item'            => array(
					'type'            => 'select',
					'label'           => esc_html__( 'Item', 'dimax' ),
					'section'         => 'mobile_version',
					'default'         => 'menu',
					'choices'         => $this->navigation_bar_items_option(),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_navigation_bar',
							'operator' => 'in',
							'value'    => array( 'simple', 'simple_adoptive' ),
						),
					),

				),
				'mobile_navigation_bar_item_align'      => array(
					'type'            => 'select',
					'label'           => esc_html__( 'Align Item', 'dimax' ),
					'section'         => 'mobile_version',
					'default'         => 'right',
					'choices'         => array(
						'left'   => esc_html__( 'Left', 'dimax' ),
						'right'  => esc_html__( 'Right', 'dimax' ),
						'center' => esc_html__( 'Center', 'dimax' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'mobile_navigation_bar',
							'operator' => 'in',
							'value'    => array( 'simple', 'simple_adoptive' ),
						),
					),
				),
				'mobile_navigation_bar_menu_item'       => array(
					'type'            => 'select',
					'label'           => esc_html__( 'Menu', 'dimax' ),
					'section'         => 'mobile_version',
					'default'         => '',
					'choices'         => $this->get_navigation_bar_get_menus(),

				),
				'mobile_navigation_bar_menu_side_type'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Show Menu', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 'side-left',
					'choices' => array(
						'side-left'  => esc_html__( 'Side to right', 'dimax' ),
						'side-right' => esc_html__( 'Side to left', 'dimax' ),
					),
				),
				'mobile_navigation_bar_menu_click_item' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Show Sub-Menus', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 'click-item',
					'choices' => array(
						'click-item' => esc_html__( 'Click to item', 'dimax' ),
						'click-icon' => esc_html__( 'Click to icon', 'dimax' ),
					),
				),
				'mobile_floating_action_button' => array(
					'type'    => 'toggle',
					'label'   => esc_html__( 'Floating Action Button', 'dimax' ),
					'section' => 'mobile_version',
					'default' => 0,
					'active_callback' => array(
						array(
							'setting'  => 'mobile_navigation_bar',
							'operator' => 'in',
							'value'    => array( 'standard_adoptive', 'simple_adoptive' ),
						),
					),
				),
		);

		$settings['panels']   = apply_filters('dimax_customize_panels', $panels);
		$settings['sections'] = apply_filters('dimax_customize_sections', $sections);
		$settings['fields']   = apply_filters('dimax_customize_fields', $fields);

		return $settings;
	}

	/**
	 * Get nav menus
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_navigation_bar_get_menus() {
		if ( ! is_admin() ) {
			return [];
		}

		$menus = wp_get_nav_menus();
		if ( ! $menus ) {
			return [];
		}

		$output = array(
			0 => esc_html__( 'Select Menu', 'dimax' ),
		);
		foreach ( $menus as $menu ) {
			$output[ $menu->slug ] = $menu->name;
		}

		return $output;
	}

	/**
	 * Display header sticky
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_header_sticky() {
		if( empty(get_theme_mod( 'header_sticky' )) ) {
			return false;
		}

		if ( 'default' == get_theme_mod( 'header_type' ) ) {
			if( ! in_array( get_theme_mod( 'header_layout' ), array( 'v3', 'v4', 'v9' ) ) ) {
				return false;
			}

			return true;
		} else {
			return true;
		}
	}

	/**
	 * Display header search panel
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_header_search_panel() {
		if ( 'custom' == get_theme_mod( 'header_type' ) ) {
			if( get_theme_mod( 'header_search_style' ) != 'icon') {
				return false;
			}

			return true;
		} else {
			if( ! in_array( get_theme_mod( 'header_layout' ), array( 'v1', 'v2', 'v5', 'v8' ) ) ) {
				return false;
			}

			return true;
		}
	}
}
