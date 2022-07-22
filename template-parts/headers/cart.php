<?php
/**
 * Template part for displaying the cart icon
 *
 * @package Dimax
 */

use Dimax\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}
?>

<div class="header-cart">
	<a href="<?php echo esc_url( wc_get_cart_url() ) ?>" data-toggle="<?php echo 'panel' == Helper::get_option( 'header_cart_behaviour' ) ? 'modal' : 'link'; ?>" data-target="cart-modal">
		<?php
			echo \Dimax\WooCommerce\Helper::get_cart_icon();
		?>
		<span class="counter cart-counter <?php echo intval( WC()->cart->get_cart_contents_count() ) == 0 ? 'hidden' : ''; ?>"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	</a>
</div>
