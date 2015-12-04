<?php
	global $nav_overlay;
?>
<nav class="top-bar <?php if( isset( $nav_overlay ) ) echo 'top-bar--overlay'; ?> owl--off" role="navigation">
	<ul class="top-bar__left">
		
		<li class="top-bar__item top-bar__item--title">
			<a href="<?= esc_url( home_url( '/' ) ); ?>" rel="bookmark"><?php bloginfo( 'name' ); ?></a>
		</li>

	</ul>

	<ul class="top-bar__right">
		
		<?php //echo '<li class="top-bar__item top-bar__item--link"><a href="index.html" title="Course Overview">Course</a></li>' ?>

		<?php
			if( $userdata ) {
				print( '<li class="top-bar__item top-bar__item--avatar"><a class="tb__avatar__link" href="#" title="Your Profile"><span class="tb__avatar__name">Hello '.$userdata->data->display_name.'</span> <img class="tb__avatar__img" src="'.get_bloginfo('template_directory').'/img/avatar-300.jpg" alt="User Avatar" height="36" width="36"></a></li>' );
				print( '<li class="top-bar__item top-bar__item--menu-icon"><a href="#">Menu</a></li>' );
			} else {
				print( '<li class="top-bar__item top-bar__item--btn"><a class="top-bar__btn btn btn--small" href="'.wp_login_url( $_SERVER['REQUEST_URI'] ).'">Log In</a></li>' );
			}
		?>

	</ul>
</nav>