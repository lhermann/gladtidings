<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry' ); ?>>
	<header class="hgroup">
		<h1 class="hgroup__title"><span class="label label--small label--theme"><?= $post->order ?></span> <?= $post->title ?></h1>
	</header>
	<div class="e-content">
		<p><?= get_field( 'description' ) ?></p>
		<p><?= h_attachment_link( $post ) ?></p>
	</div>
	<footer class="u-text--right">
		<?= h_continue_btn( $post ); ?>
	</footer>
</article>
