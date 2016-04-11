<?php get_header(); ?>

	<header id="content-header" class="content-header page-hero shadow--receive t-header-image">

			<div class="wrapper wrapper--hero owl">

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

			<div class="wrapper">

				<nav id="tabs" class="tabs">
					<ul>
						<li class="tabs__item active">
							<a href="#content-units" aria-controls="content-units" role="tab"><?= __( 'Units', 'gladtidings' ) ?></a>
						</li>
						<li class="tabs__item">
							<a href="#content-notes" aria-controls="content-notes" role="tab"><?= __( 'Course Notes', 'gladtidings' ) ?></a>
						</li>
					</ul>
				</nav>
			</div><!-- /.wrapper -->

	</header>


	<div id="content-units" class="content-body tab-content">

		<div class="wrapper owl">

			<section id="progress">

					<h2 class="u-screen-reader-text"><?= __( 'Progress', 'gladtidings' ) ?></h2>

					<div class="progress progress--meter no-owl" title="<?= __( 'Progress:', 'gladtidings' ).' '.$post->progress() ?>">
						<span class="progress__item t-comp-bg" style="width: <?= $post->progress().'%' ?>"><?= $post->progress().'%' ?></span>
					</div>

					<p class="progress__description t-second-text">
						<?php                            echo             __( 'Lessons watched:', 'gladtidings' ) . ' <strong class="t-comp-text">' . $post->num_lessons_done() . '/' . $post->num_lessons() . '</strong>'; ?>
						<?php if( $post->num_quizzes() ) echo '&bull; ' . __( 'Quizzes passed:', 'gladtidings' )  . ' <strong class="t-comp-text">' . $post->num_quizzes_done() . '/' . $post->num_quizzes() . '</strong>'; ?>
						<?php if( $post->num_exams()   ) echo '&bull; ' . __( 'Exams passed:', 'gladtidings' )    . ' <strong class="t-comp-text">' . $post->num_exams_done()   . '/' . $post->num_exams()   . '</strong>'; ?>
					</p>

			</section>

			<div class="layout">

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

						} else {

							_e( 'No Units!' );

						}
					?>

				</section><!--

				--><aside class="layout__item no-owl-desk u-1/3-desk">

					<?php
						$description = get_field( 'course_description' );
						if( $description ): ?>

						<h2 class="t-second-text"><?= __( 'Description', 'gladtidings' ) ?></h2>
						<p><?= $description ?></p>

					<?php endif; ?>

				</aside>

			</div><!-- /.layout -->

		</div><!-- /.wrapper -->

	</div><!-- /#content-units -->

	<div id="content-notes" class="content-body tab-content">

		<div class="wrapper owl--narrow body-copy">

			<?php $post->render_content(); ?>

		</div><!-- /.wrapper -->

	</div><!-- /#content-notes -->


<?php get_footer(); ?>
