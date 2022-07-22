<?php
/**
 * Template part for displaying the topbar
 *
 * @package dimax
 */

use Dimax\Helper;

$left_items   = array_filter( (array) Helper::get_option( 'topbar_left' ) );
$right_items  = array_filter( (array) Helper::get_option( 'topbar_right' ) );
$topbar_class = ['hidden-xs hidden-sm'];

$container_width = 'container';
if ( Helper::get_option( 'header_width' ) == 'large' ) {
	$container_width = 'dimax-container';
}elseif ( Helper::get_option( 'header_width' ) == 'wide' ) {
	$container_width = 'dimax-container-wide';
}

?>
<div id="topbar" class="topbar <?php echo esc_attr( implode( ' ', (array) apply_filters( 'dimax_topbar_class', $topbar_class ) ) ); ?>">
	<div class="dimax-container-fluid <?php echo esc_attr( apply_filters( 'dimax_topbar_container_class', $container_width) ); ?>">
		<?php if ( ! empty( $left_items ) ) : ?>
			<div class="topbar-items topbar-left-items">
				<?php \Dimax\Theme::instance()->get('topbar')->topbar_items( $left_items ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $right_items ) ) : ?>
			<div class="topbar-items topbar-right-items">
				<?php \Dimax\Theme::instance()->get('topbar')->topbar_items( $right_items ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>