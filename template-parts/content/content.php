<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dimax
 */

?>

<?php
\Dimax\Markup::instance()->open('post_loop_content',[
	'tag' => 'article',
	'attr' => [
		'id'    => 'post-' . get_the_ID(),
		'class' => join( ' ', get_post_class( 'blog-wrapper', get_the_ID() ) ) ,
	],
	'actions' => 'after',
]);
?>

<?php \Dimax\Markup::instance()->close('post_loop_content');  ?>
