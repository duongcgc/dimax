<?php
/**
 * Template part for displaying the search icon
 *
 * @package Dimax
 */

use Dimax\Helper;

$search_class = isset($args['search_class']) ? $args['search_class'] : '';

$text_placeholder = esc_html__( 'Search for items', 'dimax' );
if ( ! empty( Helper::get_option( 'header_search_placeholder' ) ) ) {
	$text_placeholder = esc_html( Helper::get_option( 'header_search_placeholder' ) );
} else {
	if ( $args['search_type'] == 'product' ) {
		$text_placeholder = esc_html__( 'Search for items', 'dimax' );
	}
}

if( Helper::get_option( 'header_type' ) == 'custom' &&  $args['search_form_style'] == 'boxed') {
	$search_class .= ' form-skin-' . Helper::get_option( 'header_search_form_skin' );
}

?>

<div class="header-search <?php echo esc_attr( $search_class ); ?>">
	<?php if ( $args['search_style'] == 'icon' ) : ?>
        <span class="search-icon" data-toggle="modal" data-target="search-modal">
			<?php echo \Dimax\Icon::get_svg( 'search', '', 'shop' ); ?>
		</span>
	<?php elseif ( $args['search_style'] == 'form-cat' ) : ?>
        <form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ) ?>">
            <div class="search-fields">
                <input type="text" name="s" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>"
                       placeholder="<?php echo esc_attr( $text_placeholder ) ?>" autocomplete="off">
                <a href="#"
                   class="close-search-results"><?php echo \Dimax\Icon::get_svg( 'close' ); ?></a>
                <span class="dimax-loading"></span>
            </div>
			<?php if ( $args['search_type'] == 'product' ) : ?>
                <input type="hidden" name="post_type" value="<?php echo esc_attr( $args['search_type'] ) ?>">
			<?php endif; ?>
			<?php
			if ( $args['search_type'] == 'product' ) {
				$args_cat = array(
					'name'            => 'product_cat',
					'taxonomy'        => 'product_cat',
					'orderby'         => 'NAME',
					'hierarchical'    => 1,
					'hide_empty'      => 1,
					'echo'            => 0,
					'value_field'     => 'slug',
					'class'           => 'product-cat-dd',
					'show_option_all' => esc_html__( 'All Categories', 'dimax' ),
					'id'              => 'product-cat',
				);

				echo sprintf(
					'<div class="product-cat">' .
					'<div class="product-cat-label"><span class="label">%s</span>%s</div>' .
					'%s' .
					'</div>',
					esc_html__( 'All Categories', 'dimax' ),
					\Dimax\Icon::get_svg( 'chevron-bottom' ),
					wp_dropdown_categories( $args_cat )
				);
			}

			?>
            <button class="search-submit"
                    type="submit"><?php echo \Dimax\Icon::get_svg( 'search', '', 'shop' ); ?></button>
        </form>
	<?php else: ?>
        <form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ) ?>">
            <input type="text" name="s" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>"
                   placeholder="<?php echo esc_attr( $text_placeholder ) ?>" autocomplete="off">
			<?php if ( $args['search_type'] == 'product' ) : ?>
                <input type="hidden" name="post_type" value="<?php echo esc_attr( $args['search_type'] ) ?>">
			<?php endif; ?>
            <a href="#"
               class="close-search-results"><?php echo \Dimax\Icon::get_svg( 'close', '' ); ?></a>
            <button class="search-submit"
                    type="submit"><?php echo \Dimax\Icon::get_svg( 'search', '', 'shop' ); ?></button>
            <span class="dimax-loading"></span>
        </form>
	<?php endif; ?>
	<?php if ( $args['search_style'] != 'icon' ) : ?>
		<?php \Dimax\Header::search_quicklinks(); ?>
        <div class="search-results woocommerce"></div>
	<?php endif; ?>
</div>
