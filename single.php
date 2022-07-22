<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Dimax
 */

get_header();
?>
<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
	<?php
	\Dimax\Markup::instance()->open('single_post_content',[
		'attr' => [
			'id'    => 'primary',
			'class' => 'content-area',
		],
		'actions' => false,
	]);
	?>
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/post/content', 'single' );

		endwhile; // End of the loop.
		?>

	<?php \Dimax\Markup::instance()->close('single_post_content');  ?>


	<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>
