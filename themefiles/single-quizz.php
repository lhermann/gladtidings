<?php get_header(); ?>

	<div id="content-body" class="content-body wrapper">

		<div class="layout layout--flush layout--rev layout--spacehack">

			<section class="layout__item u-3/4-desk u-3/4-lap">

				<article id="post-<?= $post->ID ?>" <?php post_class( 'wrapper h-entry u-spacing--top' ); ?>>

					<header class="hgroup">
						<h1 class="hgroup__title"><span class="label label--small label--theme"><?= $post->order ?></span> <?= $post->title ?></h1>
						<h2 class="hgroup__subtitle"><?= h_subtitle( $post ) ?></h2>
					</header>

					<?php get_template_part( 'templates/content-quizz', get_query_var( 'action' ) ); ?>

					<footer class="u-text--right">
						<?php if( !get_query_var( 'action' ) ): ?>
							<?= h_continue_btn( $post, ( $post->is_done() ? '' : 'disabled' ) ); ?>
						<?php endif; ?>
					</footer>
				</article>

			</section>

			<aside class="layout__item u-1/4-lap-and-up u-spacing--off u-flyout-palm" role="complementary">

				<?php get_template_part( 'templates/content', 'flyout' ); ?>

			</aside>

		</div><!-- /.layout -->

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
