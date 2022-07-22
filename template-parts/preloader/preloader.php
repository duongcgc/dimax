<?php
/**
 * Template part for displaying the preloader.
 *
 * @package Dimax
 */
?>
<div id="preloader" class="preloader preloader-<?php echo esc_attr( \Dimax\Helper::get_option( 'preloader' ) ) ?>">
	<?php
	switch ( \Dimax\Helper::get_option( 'preloader' ) ) {
		case 'image':
			$image = \Dimax\Helper::get_option( 'preloader_image' );
			break;

		case 'external':
			$image = \Dimax\Helper::get_option( 'preloader_url' );
			break;

		default:
			$image = apply_filters( 'dimax_preloader', false );
			break;
	}

	if ( ! $image ) {
		echo '<span class="preloader-icon spinner"></span>';
	} else {
		$image = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Preloader', 'dimax' ) . '">';
		echo '<span class="preloader-icon">' . $image . '</span>';
	}
	?>
</div>