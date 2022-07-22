<?php
/**
 * The template part for displaying related posts
 *
 * @package Sober
 */

$related_posts = new WP_Query( $args );

if ( ! $related_posts->have_posts() ) {
	return;
}

?>
    <?php
    \Dimax\Markup::instance()->open('related_post_contents',[
        'attr' => [
            'class'    => 'dimax-posts__related',
        ],
        'actions' => true,
    ]);
    ?>
        <?php
            while ( $related_posts->have_posts() ) : $related_posts->the_post();

	            get_template_part( 'template-parts/content/content', get_post_type() );

            endwhile;
		?>
    <?php \Dimax\Markup::instance()->close('related_post_contents');  ?>
<?php
wp_reset_postdata();