<?php
/**
 * Template part for display department
 *
 * @package Dimax
 */

use Dimax\Helper;

$class = '';
if ( empty( Helper::get_option( 'header_department_text' ) ) ) {
	$class = 'text-empty';
}

if ( get_post_meta( \Dimax\Helper::get_post_ID(), 'rz_department_menu_display', true ) == 'onpageload' ) {
	$class .= ' show_menu_department';
}
?>
<div class="header-department <?php echo esc_attr( $class ) ?>">
	<span class="department-icon">
		<?php echo \Dimax\Icon::get_svg( 'hamburger' ); ?>
		<?php
		if ( ! empty( Helper::get_option( 'header_department_text' ) ) ) {
			echo '<span class="department-text">' . esc_html( Helper::get_option( "header_department_text" ) ) . '</span>';
		}
		?>
	</span>
    <div class="department-content">
        <nav id="department-menu" class="department-menu main-navigation">
			<?php
			if ( has_nav_menu( 'department' ) ) {
				if ( class_exists( '\Dimax\Addons\Modules\Mega_Menu\Walker' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'department',
						'container'      => null,
						'menu_class'     => 'menu nav-menu',
						'walker' 		=> new \Dimax\Addons\Modules\Mega_Menu\Walker()
					));
				} else {
					wp_nav_menu( array(
						'theme_location' => 'department',
						'container'      => null,
						'menu_class'     => 'menu nav-menu',
					));
				}
			}
			?>
        </nav>
    </div>
</div><!-- .header-department -->
