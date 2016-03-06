<?php
global $user;
?>
<aside id="flyout-right" class="flyout flyout__right u-spacing--off" role="complementary">

	<div class="wrapper u-spacing--top">

		<h2 class="u-screen-reader-text">User Menu</h2>
		<ul class="user-menu user-menu--touch owl--off">
			<li class="user-menu__item user-menu__identity">
				<?= get_avatar( $user->ID, 120 ) ?>
				<div class="user-menu__name"><?= $user->first_name . ' ' . $user->last_name ?></div>
				<div class="user-menu__role"><?= __( 'Student', 'gladtidings' ) ?></div>
			</li>
			<li class="user-menu__item"><?= $user->link_to( 'dashboard' ) ?></li>
			<li class="user-menu__item"><?= $user->link_to( 'settings' ) ?></li>
			<li class="user-menu__item"><?= $user->link_to( 'contact' ) ?></li>
			<li class="user-menu__item"><?php wp_loginout( $_SERVER['REQUEST_URI'] ); ?></li>
		</ul>


	</div><!-- /.wrapper -->

</aside>
