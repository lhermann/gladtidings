<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry u-spacing--top' ); ?>>

	<header class="hgroup">
		<h2 class="t-second-text"><?= $_gt->get_subtitle() ?></h2>
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
