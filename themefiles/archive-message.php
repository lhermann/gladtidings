<?php
	get_header();
	// global $user;
?>

	<div id="content-body" class="content-body wrapper">

		<div class="layout u-spacing--top">

			<div class="layout__item u-1/3 u-hidden-palm">

				<ul class="user-menu user-menu--touch panel owl--offall">

					<?php get_template_part( 'templates/user-menu' ); ?>

				</ul>

			</div><!--

			--><div class="layout__item u-2/3-lap-and-up">

				<h3>List of Messages</h3>
				<hr class="ui">
				<ul class="no-ui">
					<li class="message message--compact owl--offall">
						<header class="message__header">
							<?= get_avatar( $user->ID, 36, null, null, array('class' => 'message__header__item message__avatar') ) ?>
							<span class="message__header__item message__name">Lukas</span>
							<span class="message__header__item message__date">12:43</span>
							<button class="message__header__item message__edit btn btn--grayline btn--square" title="Edit Message"><span class="fi fi-pencil"></span></button>
						</header>
						<div class="message__content">
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam ex optio consectetur perferendis, vero distinctio repudiandae sit cumque voluptas, aspernatur quaerat nam. Consectetur, cupiditate. ...</p>
							<button class="message__more btn btn--anchor">Read all</button>
						</div>
						<footer class="message__footer">
							<button class="message__footer__item message__reply"><span class="fi fi-comment"></span> Reply</button><!--
							--><button class="message__footer__item message__unread" title="Mark as read">
								<span class="fi fi-megaphone"></span> unread
							</button><!--
							--><button class="message__footer__item message__expand" title="Show full conversation">
								<span class="badge badge--primary">3</span>
							</button>
						</footer>
					</li>
					<li class="message message--compact owl--offall">
						<header class="message__header">
							<?= get_avatar( $user->ID, 36, null, null, array('class' => 'message__header__item message__avatar') ) ?>
							<span class="message__header__item message__name">Lukas</span>
							<span class="message__header__item message__date">28/04/16</span>
							<button class="message__header__item message__edit btn btn--grayline btn--square" title="Edit Message"><span class="fi fi-pencil"></span></button>
						</header>
						<div class="message__content">
							<p>Laudantium ipsam cupiditate porro est cumque iste quidem. Dolore adipisci illum, voluptates. Ullam beatae corporis quis. ...</p>
							<button class="message__more btn btn--anchor">Read all</button>
						</div>
						<footer class="message__footer">
							<button class="message__footer__item message__reply"><span class="fi fi-comment"></span> Reply</button><!--
							--><button class="message__footer__item message__read" title="Mark as unread">
								<span class="fi fi-check"></span> read
							</button><!--
							--><button class="message__footer__item message__expand" title="Show full conversation">
								<span class="badge badge--primary">1</span>
							</button>
						</footer>
					</li>
				</ul>

			</div>

		</div><!-- /.layout -->

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
