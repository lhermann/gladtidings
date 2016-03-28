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
				<li class="message message--compact owl--offall">
					<a href="#" class="message__link a--unchanged">
						<p class="message__content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime, odit eos corporis rem ab. Obcaecati porro veniam consequatur nihil, illo quidem. Dolores tenetur dignissimos numquam asperiores veniam doloribus quidem necessitatibus consectetur dolorem.</p>
						<footer class="message__footer">
							<div class="message__id">#171</div>
							<div class="message__meta">
								<span class="badge badge--success">1</span> Answer  &bull; <strong>Unread Answer</strong>
							</div>
							<div class="message__controlls">
								<span class="fi fi-arrows-expand"></span>
							</div>
						</footer>
					</a>
				</li>
				<li class="message message--compact owl--offall">
					<a href="#" class="message__link a--unchanged">
						<p class="message__content">Laudantium ipsam cupiditate porro est cumque iste quidem. Dolore adipisci illum, voluptates. Ullam beatae corporis quis.</p>
						<footer class="message__footer">
							<div class="message__id">#102</div>
							<div class="message__meta">
								<span class="badge">3</span> Answers
							</div>
							<div class="message__controlls">
								<span class="fi fi-arrows-expand"></span>
							</div>
						</footer>
					</a>
				</li>

			</div>

		</div><!-- /.layout -->

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
