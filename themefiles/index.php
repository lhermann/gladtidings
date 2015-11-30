<?php 
	get_header(); 
?>

<?php
//var_dump($userdata);
?>

	<header id="page-header" class="page-hero u-header-image u-header-color">

		<nav class="top-bar top-bar--layover owl--off">
			<ul class="top-bar__right">
				
				<li class="top-bar__item top-bar__item--link"><a href="#" title=""> </a></li>
				<?php
					if( $userdata ) {
						print( '<li class="top-bar__item top-bar__item--avatar">Hello '.$userdata->data->display_name.' <a class="top-bar__avatar" href="" title="Your Profile"><img src="'.get_bloginfo('template_directory').'/img/avatar-300.jpg" alt="User Avatar" height="36" width="36"></a></li>' );
					} else {
						// print( '<li class="top-bar__item top-bar__item--btn">'.wp_login_form().'</li>' );
						print( '<li class="top-bar__item top-bar__item--btn"><a class="top-bar__btn btn btn--small" href="'.wp_login_url( $_SERVER['REQUEST_URI'] ).'">Log In</a></li>' );
					}
				?>

			</ul>
		</nav>

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
					<p class="lede text--center">A short description of the course so people know what this website is about. This is a course about the ancient writings of the prophets and applicability today.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">
					
					<?php
						if ( have_posts() ) :
							while ( have_posts() ) : the_post();

								get_template_part( 'partials/teaser', $post->post_type );

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
