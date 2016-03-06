<?php
// prepare flyout
add_filter( 'container_class', function( $classes ){
	return $classes + array( 'has-flyout', 'has-flyout--right' );
});

get_header();
global $user;
?>
	<header id="content-header" class="content-header">
		<h1 class="u-text--center">User Dashboard</h1>
	</header>

	<div id="content-body" class="content-body wrapper">

		<p class="lede u-text--center">User Dashboard goes here</p>


		<?php var_dump( $user ); ?>

	</div>

<?php get_footer(); ?>
