<?php
	// var_dump( $post );
	add_filter( 'container_class', function( $classes ){ $classes[] = 'flyout'; return $classes; } );

	get_header();
?>

	<header id="page-header" class="shadow--drop">

		<?php get_template_part( 'templates/navigation', 'quizz' ); ?>

	</header>

	<div class="wrapper wrapper--desk t-margin-reset--top">
		<div id="page-content" class="layout layout--flush layout--rev layout--spacehack">
			<main class="layout__item u-3/4-desk u-3/4-lap" role="main">

				<div class="wrapper">

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry u-spacing--top' ); ?>>

						<header class="hgroup">
							<h1 class="hgroup__title"><span class="label label--small label--theme"><?= $post->order ?></span> <?= $post->title ?></h1>
							<h2 class="hgroup__subtitle"><?= $post->subtitle() ?></h2>
						</header>

						<?php get_template_part( 'templates/content-quizz', get_query_var( 'action' ) ); ?>

						<footer class="u-text--right">
							<?php if( !get_query_var( 'action' ) ) {
								echo h_quizz_continue_btn( $post, ( $post->is_done() ? '' : 'disabled' ) );
							} ?>
						</footer>
					</article>

				</div>

			</main>
			<aside class="layout__item u-1/4-lap-and-up u-spacing--off u-flyout-palm" role="complementary">

				<div class="wrapper u-spacing--top">

					<h2 class="u-text--1rem">
						<span class="label label--small label--theme"><?= __( 'Unit', 'gladtidings' ).' '.$post->parent()->order ?></span>
						<?=  $post->parent()->link_to( /*array( 'display' => __('Unit Overview', 'gladtidings') )*/ ) ?>
					</h2>
					<nav role="navigation">

						<ul class="nodelist nodelist--lesson">

							<?php
								//get all the units
								$siblings = $post->siblings();
								// var_dump( $siblings );

								if( $siblings ) {

									// loop through the items
									foreach ( $siblings as $node ) {

										$type = $node->type == 'headline' ? 'divider' : $node->type;

										get_template_part( 'templates/node', $type );

									}

								} else {

									_e( 'No Lessons!' );

								}
							?>

						</ul>

					</nav>

				</div><!-- /.wrapper -->

			</aside>
		</div><!-- /#page-content /.layout -->
	</div>

<?php get_footer(); ?>
