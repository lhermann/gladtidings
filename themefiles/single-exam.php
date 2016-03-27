<?php get_header(); ?>

	<header id="content-header" class="content-header page-hero page-hero--skinny shadow--receive t-header-image">

		<div class="wrapper">

			<?php get_template_part( 'templates/node', 'hero' ); ?>

		</div><!-- /.wrapper -->

	</header>

	<div id="content-body" class="content-body wrapper">

		<article id="post-<?= $post->ID ?>" <?php post_class( 'h-entry u-spacing--top' ); ?>>

			<header class="hgroup">
				<h2 class="t-second-text"><?= h_subtitle( $post ) ?></h2>
			</header>

			<?php get_template_part( 'templates/content-quizz', get_query_var( 'action' ) ); ?>

			<footer class="u-text--right">
				<?php if( !get_query_var( 'action' ) ): ?>
					<?= h_continue_btn( $post, ( $post->is_done() ? '' : 'disabled' ) ); ?>
				<?php endif; ?>
			</footer>
		</article>

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
