<?php
/**
 * Template file for displaying mobile header v1
 *
 * @package dimax
 */

?>

<?php
\Dimax\Markup::instance()->open('header_mobile',[
	'attr' => [
		'class' => 'header-mobile',
	],
	'actions' => false,
]);
?>

<?php do_action( 'dimax_header_mobile_content' ); ?>

<?php \Dimax\Markup::instance()->close('header_mobile');  ?>
