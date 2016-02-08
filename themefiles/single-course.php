<?php
	// global $post;
	// var_dump( $post );

	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'course' ); ?>

		<div class="page-hero shadow--receive t-header-image">
			<div class="wrapper">
				<div class="hero-frame hero-frame--course">
					<div class="hero-frame__badge">
						<img src="<?= $post->batch_src() ?>" alt="<?= $post->title.' '.__( 'Badge', 'gladtidings' ); ?>">
					</div>
					<h1 class="hero-frame__title">
						<?php if( $post->is_done() ): ?>
							<span class="label label--success label--filled label--small shadow--strong"><span class="fi fi-check"></span></span>
						<?php endif; ?>
						<span class="shadow--strong-text"><?= $post->title ?></span>
					</h1>
				</div>
			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content" role="main">

		<section id="progress" class="wrapper">

				<h2 class="u-screen-reader-text"><?= __( 'Progress', 'gladtidings' ) ?></h2>

				<div class="progress progress--meter" title="<?= __( 'Progress:', 'gladtidings' ).' '.$post->progress() ?>">
					<span class="progress__item t-comp-bg" style="width: <?= $post->progress().'%' ?>"><?= $post->progress().'%' ?></span>
				</div>

				<p class="u-spacing--narrow t-second-text">
					<?= __( 'Lessons watched:', 'gladtidings' ) ?> <strong class="t-comp-text">0/<?= $post->num_lessons() ?></strong>
					&bull; <?= __( 'Quizzes passed:', 'gladtidings' ) ?> <strong class="t-comp-text">0/<?= $post->num_quizzes() ?></strong>
				</p>

		</section>

		<div class="wrapper">
			<div class="layout layout--spacehack">
				<section id="units" class="layout__item u-2/3-desk">

					<h2 class="t-second-text"><?= __( 'Units', 'gladtidings' ); ?></h2>

					<?php
						//get all the units
						$children = $post->children();

						// check if the repeater field has rows of data
						if( $children ) {

							print( '<ul class="nodelist nodelist--course">' );

							// loop through the units
							foreach ( $children as $key => $node ) {

								get_template_part( 'templates/node', $node->type );

							}

							print( '</ul><!-- /.nodelist -->' );

							// restore the original post
							wp_reset_postdata();

						} else {

							_e( 'No Units!' );

						}
					?>

				</section>
				<aside class="layout__item no-owl-desk u-1/3-desk">

					<?php
						$description = get_field( 'course_description' );
						if( $description ): ?>

						<h2 class="t-second-text"><?= __( 'Description', 'gladtidings' ) ?></h2>
						<p><?= $description ?></p>

					<?php endif; ?>

				</aside>
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
