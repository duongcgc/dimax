<?php
/**
 * Page Header
 */

	\Dimax\Markup::instance()->open('page_header',[
		'attr' => [
			'id'    => 'page-header',
			'class' => 'page-header',
		],
		'actions' => 'after',
	]);
?>

<?php do_action( 'dimax_page_header_content_item' ); ?>

<?php \Dimax\Markup::instance()->close('page_header');  ?>
