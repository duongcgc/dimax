<?php
/**
 * Template part for modal cart
 *
 * @package Dimax
 */

?>
<div class="modal-header">
    <h3 class="modal-title"><?php esc_html_e( 'Your Cart', 'dimax' ) ?>
        <span class="cart-panel-counter">(<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
    </h3>
    <a href="#" class="close-account-panel button-close"><?php echo \Dimax\Icon::get_svg( 'close' ); ?></a>
</div>
<div class="modal-content">
    <div class="widget_shopping_cart_content">
		<?php woocommerce_mini_cart(); ?>
    </div>
</div>
