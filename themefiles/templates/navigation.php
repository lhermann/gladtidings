<?php
	global $nav_overlay, $_gt;
?>
<nav class="top-bar <?php if( isset( $nav_overlay ) ) echo 'top-bar--overlay'; ?> owl--off" role="navigation">

	<a class="skip-link u-screen-reader-text" href="#content">Skip to content</a>

	<ul class="top-bar__left">

		<li class="top-bar__item top-bar__item--title">
			<a class="tb__title__link" href="<?= esc_url( home_url( '/' ) ); ?>" rel="bookmark">
				<span class="tb__title__icon fi fi-book-bookmark"></span>
				<span class="tb__title__text"><?php bloginfo( 'name' ); ?></span>
			</a>
		</li>

	</ul>

	<ul class="top-bar__right">

		<?php if( is_user_logged_in() ): ?>
			<li class="top-bar__item top-bar__item--avatar">
				<a class="tb__avatar__link" href="#" title="Your Profile">
					<span class="tb__avatar__name"><?= sprintf( __( 'Hello %s', 'gladtidings' ), $_gt->user_name ) ?></span>
					<?= get_avatar( $_gt->user_id, 36 ) ?>
				</a>
			</li>
			<!-- <li class="top-bar__item top-bar__item--button">
				<button id="flyout-button" class="btn btn--dark btn--small">Menu</button>
			</li> -->
		<?php else: ?>
			<li class="top-bar__item top-bar__item--button">
				<a class="top-bar__btn btn btn--small btn--suppl" href="<?= wp_login_url( $_SERVER['REQUEST_URI'] ) ?>">Log In</a>
			</li>
		<?php endif; ?>

	</ul>
</nav>
