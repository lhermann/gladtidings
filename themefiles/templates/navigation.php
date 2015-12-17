<?php
	global $nav_overlay;
?>
<nav class="top-bar <?php if( isset( $nav_overlay ) ) echo 'top-bar--overlay'; ?> owl--off" role="navigation">
	<ul class="top-bar__left">
		
		<li class="top-bar__item top-bar__item--title">
			<a class="tb__title__link" href="<?= esc_url( home_url( '/' ) ); ?>" rel="bookmark">
				<span class="tb__title__icon fi fi-book-bookmark"></span> 
				<span class="tb__title__text"><?php bloginfo( 'name' ); ?></span>
			</a>
		</li>

	</ul>

	<ul class="top-bar__right">
		
		<?php //echo '<li class="top-bar__item top-bar__item--link"><a href="index.html" title="Course Overview">Course</a></li>' ?>

		<?php
			$user = wp_get_current_user();

			if( $user->ID ) {
				print( '<li class="top-bar__item top-bar__item--avatar"><a class="tb__avatar__link" href="#" title="Your Profile"><span class="tb__avatar__name">Hello '.$user->data->display_name.'</span> <img class="tb__avatar__img" src="'.get_bloginfo('template_directory').'/img/avatar-300.jpg" alt="User Avatar" height="36" width="36"></a></li>' );
				print( '<li class="top-bar__item top-bar__item--button"><button id="flyout-button" class="btn btn--dark btn--small">Menu</button></li>' );
			} else {
				print( '<li class="top-bar__item top-bar__item--button"><a class="top-bar__btn btn btn--small btn--suppl" href="'.wp_login_url( $_SERVER['REQUEST_URI'] ).'">Log In</a></li>' );
			}
		?>

	</ul>
</nav>