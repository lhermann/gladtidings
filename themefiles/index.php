<?php 
	get_header(); 
?>

<?php
//var_dump($userdata);
?>

	<header id="page-header" class="page-hero t-header-image">

		<?php 
			global $nav_overlay;
			$nav_overlay = true;
			get_template_part( 'templates/navigation', 'home' );
		?>

		<div class="wrapper">
			<div class="hero-frame hero-frame--box owl--narrow">
				<h1 class="hero-frame__title">The Glad Tidings</h1>
				<hr class="hero-frame__hr">
				<!-- <div class="ero-frame__hr u-header-color"></div> -->
				<p class="ero-frame__subtitle">What the Prophets of old want you to know</p>
			</div>
		</div><!-- /.wrapper -->

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
