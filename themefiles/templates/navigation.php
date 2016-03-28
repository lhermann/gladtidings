<?php
	global $user;
?>
<nav id="page-navigation" class="top-bar owl--offall shadow--drop" role="navigation">

	<a class="skip-link u-screen-reader-text" href="#content"><?= __( 'Skip to content', 'gladtidings' ) ?></a>

	<ul class="top-bar__left">

		<li class="top-bar__item tb__title">
			<a class="tb__title__link" href="<?= esc_url( home_url( '/' ) ); ?>" rel="bookmark">
				<img class="tb__title__logo" src="<?= get_template_directory_uri() . '/img/gt-logo.svg' ?>" alt="Site Logo">
				<span class="tb__title__text"><?php bloginfo( 'name' ); ?></span>
			</a>
		</li>

	</ul>

	<ul class="top-bar__right">

		<?php if( is_user_logged_in() ): ?>
			<li class="top-bar__item top-bar__item--avatar">
				<a id="flyoutButton" class="tb__avatar__link flyoutButton" href="<?= $user->url_to() ?>" title="<?= __( 'Profile', 'gladtidings' ) ?>" data-flyout="right">
					<span class="tb__avatar__name"><?= sprintf( __( 'Hello %s', 'gladtidings' ), $user->name ) ?></span>
					<?= get_avatar( $user->ID, 36 ) ?>
				</a>
				<div id="user-menu" class="top-bar__user-menu">
					<ul class="user-menu user-menu--mouse">
						<?php get_template_part( 'templates/user-menu' ); ?>
					</ul>
				</div>
			</li>
		<?php else: ?>
			<li class="top-bar__item top-bar__item--button">
				<a class="top-bar__btn btn btn--small btn--suppl" href="<?= wp_login_url( $_SERVER['REQUEST_URI'] ) ?>">
					<?= __( 'Log In', 'gladtidings' ) ?>
				</a>
			</li>
		<?php endif; ?>

	</ul>
</nav>
