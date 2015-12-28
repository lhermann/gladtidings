<?php 
	global $meta, $unit, $_gt;

	$_gt->lesson_init( $post );

	// var_dump($_gt);

	// get variables
	$meta 	= get_post_meta( $post->ID, '', true );
	$unit 	= get_unit( $post->ID );

	// Built Inline Theme CSS Styles
	add_filter( 'theme_css', 'add_theme_color', 10 );
	add_filter( 'container_class', function( $classes ){ $classes[] = 'flyout'; return $classes; } );

	// var_dump($GLOBALS);
	
	get_header();
?>

	<header id="page-header" class="shadow--drop">

		<?php get_template_part( 'templates/navigation', 'lesson' ); ?>

	</header>

	<div id="content" class="wrapper wrapper--desk no-owl">
		<div class="layout layout--flush layout--rev layout--spacehack">
			<main class="layout__item u-3/4-desk u-3/4-lap" role="main">
		
		
				<div class="wrapper wrapper--paddingless">

					<?php get_template_part( 'templates/content', 'embed' ); ?>
				
				</div><!-- /.wrapper -->
				<div class="wrapper">
		
					<?php get_template_part( 'templates/content', 'lesson' ); ?>

				</div>
				<div class="wrapper t-text--right">
					
					<?php get_template_part( 'templates/continue', 'lesson' ); ?>

				</div><!-- /.wrapper -->
			
		
			</main>
			<aside class="layout__item u-1/4-lap-and-up no-owl u-flyout-palm" role="complementary">
				
				<div class="wrapper u-spacing--top">
					
					<h2 class="u-h--60"><span class="label label--small label--theme"><?= __( 'Unit', 'gladtidings' ).' '.$unit->unit_order ?></span> <a class="a--bodycolor" href="<?= get_term_link( $unit ) ?>" title="<?= __('Unit Overview', 'gladtidings') ?>"><?= $unit->name ?></a></h2>
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
