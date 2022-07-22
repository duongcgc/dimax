<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Dimax
 */

get_header();
?>
<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) { ?>
    <?php
    \Dimax\Markup::instance()->open('search_content',[
        'attr' => [
            'id'    => 'primary',
            'class' => 'content-area',
        ],
        'actions' => true,
    ]);
    ?>

		<?php if ( have_posts() ) :

			do_action( 'dimax_before_search_loop' );

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content/content' );

			endwhile;

			do_action( 'dimax_after_search_loop' );

		else :

			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>

    <?php \Dimax\Markup::instance()->close('search_content');  ?>

	<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>
