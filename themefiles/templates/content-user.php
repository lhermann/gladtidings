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
		Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, iure inventore nulla!
	</div>
</div>
