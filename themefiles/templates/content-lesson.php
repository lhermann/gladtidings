<?php
global $_gt;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry' ); ?>>
	<header class="hgroup">
		<h1 class="hgroup__title"><span class="label label--small label--theme"><?= $post->order ?></span> <?php the_title(); ?></h1>
	</header>
	<div class="e-content">
		<p><?= $_gt->get_description() ?></p>
		<p><?php $_gt->print_attachment_link() ?></p>
	</div>
	<footer class="u-text--right">
		<?php $_gt->print_continue_btn(); ?>
	</footer>
</article>
