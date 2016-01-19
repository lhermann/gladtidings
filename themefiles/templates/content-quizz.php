<?php
global $_gt;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry u-spacing--top' ); ?>>
	
	<?php //$_gt->quizz_print_hgroup(); ?>

	<header class="hgroup">
		<h1 class="hgroup__title"><span class="label label--small label--theme"><?= $post->order ?></span> <?php the_title() ?></h1>
		<h2 class="hgroup__subtitle"><?= $_gt->get_subtitle() ?></h2>
	</header>

	<?php get_template_part( 'templates/content-quizz-view', get_query_var( 'view' ) ); ?>
	
	<footer class="u-text--right">
		<?php 
			if( get_query_var( 'view' ) !== 'question' ) {
				$_gt->print_continue_btn( $post, ( $_gt->is_done( $post ) ? '' : 'disabled' ) );
			}
		?>
	</footer>
</article>