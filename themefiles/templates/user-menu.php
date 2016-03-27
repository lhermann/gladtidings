<?php
	global $user;
?>
<li class="user-menu__item user-menu__identity">
	<?= get_avatar( $user->ID, 120 ) ?>
	<div class="user-menu__name"><?= $user->first_name . ' ' . $user->last_name ?></div>
	<div class="user-menu__role"><?= __( 'Student', 'gladtidings' ) ?></div>
</li>
<li class="user-menu__item"><?= $user->link_to() ?></li>
<li class="user-menu__item"><?= $user->link_to( 'messages' ) ?></li>
<li class="user-menu__item"><?= $user->link_to( 'settings' ) ?></li>
<li class="user-menu__item"><?php wp_loginout( $_SERVER['REQUEST_URI'] ); ?></li>
