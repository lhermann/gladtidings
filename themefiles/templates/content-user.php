<?php
	global $user;
?>

<div class="layout owl--offone-lap-and-up">
	<div class="layout__item u-1/2-lap-and-up">

		<h3>Courses</h3>
		<hr class="ui">
		<ul class="no-ui">
			<?php
				foreach( $user->courses() as $post ) {

					get_template_part( 'templates/teaser', 'compact' );

				}
			?>
		</ul>

	</div><!--
	--><div class="layout__item u-1/2-lap-and-up">
		<h3>Messages</h3>
		<hr class="ui">



		<ul class="no-ui">
			<li class="message message--excerpt owl--offall">
				<a href="#" class="a--unchanged">
					<header class="message__header">
						<?= get_avatar( $user->ID, 36, null, null, array('class' => 'message__header__item message__avatar') ) ?>
						<span class="message__header__item message__name">Lukas</span>
						<span class="message__header__item message__date">12:43</span>
						<span class="message__header__item message__unread label label--small label--success">Unread</span>
					</header>
					<div class="message__content">
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam ex optio ...</p>
					</div>
				</a>
			</li>
				<li class="message message--excerpt owl--offall">
				<a href="#" class="a--unchanged">
					<header class="message__header">
						<?= get_avatar( $user->ID, 36, null, null, array('class' => 'message__header__item message__avatar') ) ?>
						<span class="message__header__item message__name">Lukas</span>
						<span class="message__header__item message__date">28/04/16</span>
					</header>
					<div class="message__content">
						<p>Laudantium ipsam cupiditate porro est cumque iste quidem. Dolore adipisci ...</p>
					</div>
				</a>
			</li>
		</ul>



	</div>
</div>
