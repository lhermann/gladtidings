<?php
	get_header();
?>
	<header id="content-header" class="content-header">

		<h1 class="u-text--center">User Dashboard</h1>

	</header>

	<div id="content-body" class="content-body wrapper">

		<?php get_template_part( 'templates/content-user', get_query_var( 'action' ) ); ?>

	</div>

<?php get_footer(); ?>
