<?php 
	add_filter( 'container_class', function( $classes ){ $classes[] = 'flyout'; return $classes; } );

	get_header();

	global $meta, $unit;

	// get variables
	$meta 	= get_post_meta( $post->ID, '', true );
	$unit 	= get_unit( $post->ID );

	// var_dump($GLOBALS);
?>

	<header id="page-header" class="shaddow--drop">
		
		<?php get_template_part( 'templates/navigation', 'lesson' ); ?>

	</header>

	<div class="wrapper wrapper--desk t-margin-reset--top">
		<div id="page-content" class="layout layout--flush layout--rev layout--spacehack">
			<main class="layout__item u-3/4-desk u-3/4-lap" role="main">
		

				<div class="wrapper">

					<?php var_dump($post); ?>
		
					<hr>
					
					<div class="layout layout--middle layout--rev">
						<div class="layout__item u-1/3-lap-and-up t-text--right">
							<?php get_template_part( 'templates/continue', 'lesson' ); ?>
						</div><div class="layout__item u-2/3-lap-and-up">
							<?php get_template_part( 'templates/breadcrumbs', 'lesson' ); ?>
						</div>
					</div>
				</div><!-- /.wrapper -->
			
		
			</main>
			<aside class="layout__item u-1/4-lap-and-up u-flyout-palm" role="complementary">
				
				<div class="wrapper u-spacing--top">
					
					<h5><span class="label label--small"><?= __( 'Unit', 'gladtidings' ).' '.$unit->unit_order ?></span> <?= $unit->name ?></h5>
					<nav role="navigation">
						<ul class="nodelist nodelist--lesson">
							<?php
								foreach( $unit->lesson_order as $lesson ) {
									global $lesson;
									get_template_part( 'templates/nodelist', 'lesson' );
								}
							?>
						</ul>
					</nav>
		
				</div><!-- /.wrapper -->
		
			</aside>
		</div><!-- /#page-content /.layout -->
	</div>

<?php get_footer(); ?>
