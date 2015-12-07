<?php 
	get_header(); 
?>

<?php
//var_dump($userdata);
?>

	<header id="page-header" class="page-hero u-header-image u-header-color">

		<?php 
			global $nav_overlay;
			$nav_overlay = true;
			get_template_part( 'templates/navigation', 'home' );
		?>

		<div class="wrapper">
			<div class="page-hero__frame">
				<h1 class="page-hero__title">The Glad Tidings</h1>
				<div class="page-hero__hr u-header-color"></div>
				<p class="page-hero__subtitle">What the Prophets of old want you to know</p>
			</div>
		</div><!-- /.wrapper -->

	</header>


	<main id="page-content">
		<div class="wrapper">
			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">
					<p class="lede t-text--center">A short description of the course so people know what this website is about. This is a course about the ancient writings of the prophets and applicability today.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">
					
					<?php
						if ( have_posts() ) :
							while ( have_posts() ) : the_post();

								get_template_part( 'templates/teaser', $post->post_type );

							endwhile;
						else :
							_e( 'Sorry, no posts matched your criteria.' );
						endif;
					?>

				</section>
				<aside class="layout__item u-1/3-lap-and-up">
					


				</aside>
			</div>
		</div><!-- /.wrapper -->
	</main>

<?php get_footer(); ?>
