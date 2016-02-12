<?php
	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'exam' ); ?>

		<div class="page-hero page-hero--skinny shadow--receive t-header-image">
			<div class="wrapper">

				<div class="nl__item nl__item--exam nl__item--hero nl__item--<?= $post->status ?>">
					<article class="nl__article">
						<header class="nl__article__header owl--off">
							<h1 class="nl__article__title"><span class="label label--small shadow--strong t-second-text"><?= __( 'Exam', 'gladtidings') ?></span> <span class="shadow--strong-text"><?= $post->title ?></span></h1>
						</header>
						<footer class="nl__article__footer shadow--strong t-comp-text">
							<p><?= __( 'Course', 'gladtidings' ) ?>: <?= $post->parent()->link_to(); ?> <?= h_node_meta( $post ) ?></p>
						</footer>
					</article>
					<div class="nl__node nl__node--big">
						<div class="nl__node__border t-second-border"></div>
						<div class="nl__node__inner <?= $post->status == 'publish' ? 't-main-text t-main-border' : '' ?>"></div>
					</div>
				</div>

			</div><!-- /.wrapper -->
		</div>

	</header>

	<main class="wrapper" role="main">

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

	</main>

<?php get_footer(); ?>
