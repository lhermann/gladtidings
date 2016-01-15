<?php 
	global $_gt;
	get_header(); 
	var_dump($post);
?>

	<header id="page-header">
		
		<?php get_template_part( 'templates/navigation', 'unit' ); ?>

		<div class="page-hero page-hero--skinny shadow--receive t-header-image">
			<div class="wrapper">
			
				<?php get_template_part( 'templates/node', 'hero' ); ?>

			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">
			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">
					<p class="lede u-text--center">A short description of the course so people know what this website is about. This is a course about the ancient writings of the prophets and applicability today.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">
					
					<?php
						if ( have_posts() ) :
							while ( have_posts() ) : the_post();

								print( '<div class="layout__item u-1/3-lap-and-up no-owl-lap-and-up">' );
								get_template_part( 'templates/teaser', $post->post_type );
								print( '</div>' );

							endwhile;
						else :
							_e( 'Sorry, no posts matched your criteria.' );
						endif;
					?>

			</div>
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
