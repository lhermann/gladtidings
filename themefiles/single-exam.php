<?php
	global $_gt;
	// var_dump( $_gt );

	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'exam' ); ?>

		<div class="page-hero page-hero--skinny shadow--receive t-header-image">
			<div class="wrapper">

				<div class="nl__item nl__item--exam nl__item--hero nl__item--<?= $post->post_status ?>">
					<article class="nl__article">
						<header class="nl__article__header owl--off">
							<h1 class="nl__article__title"><span class="label label--small shadow--strong t-second-text"><?= __( 'Exam', 'gladtidings') ?></span> <span class="shadow--strong-text"><?php the_title() ?></span></h1>
						</header>
						<footer class="nl__article__footer shadow--strong t-comp-text">
							<p><?= __( 'Course', 'gladtidings' ) ?>: <?= $_gt->print_link_to( 'course' ); ?> <?= $_gt->hero_meta() ?></p>
						</footer>
					</article>
					<div class="nl__node nl__node--big">
						<div class="nl__node__border t-second-border"></div>
						<div class="nl__node__inner <?= $post->post_status == 'publish' ? 't-main-text t-main-border' : '' ?>"></div>
					</div>
				</div>

			</div><!-- /.wrapper -->
		</div>

	</header>

	<main class="wrapper" role="main">

		<?php get_template_part( 'templates/content', 'exam' ); ?>

	</main>

<?php get_footer(); ?>
