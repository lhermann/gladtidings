<?php
global $_gt;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry u-spacing--top' ); ?>>
	
	<?php $_gt->quizz_print_hgroup(); ?>

	<?php get_template_part( 'templates/content-quizz-view', get_query_var( 'view' ) ); ?>
	
	<footer class="u-text--right">
		<?php 
			if( get_query_var( 'view' ) !== 'question' ) {
				$_gt->quizz_print_continue_btn();
			}
		?>
	</footer>
</article>