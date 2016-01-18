<?php
/**
 * Tamplate to display a pseudo nodelist item in the page-header
 * This template works in the context of a term of the type 'tax-unit'
 */

global $_gt;
?>

<div class="nl__item nl__item--hero nl__item--<?= $post->post_status ?>">
	<article class="nl__article">
		<header class="nl__article__header owl--off">
			<h1 class="nl__article__title"><span class="label label--small shadow--strong t-second-text"><?= __( 'Unit', 'gladtidings').' '.$post->order ?></span> <span class="shadow--strong-text"><?php the_title() ?></span></h1>
		</header>
		<footer class="nl__article__footer shadow--strong t-comp-text">
			<p><?= $_gt->hero_footer() ?> &bull; <?= __( 'Course', 'gladtidings' ) ?>: <?= $_gt->get_link_to( 'course' ); ?> <?= $_gt->hero_meta() ?></p>
		</footer>
	</article>
	<div class="nl__node nl__node--bigger">
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner <?= $post->post_status == 'publish' ? 't-main-text t-main-border' : '' ?>">
			<?php if( isset($post->progress) ): ?>
				<div class="nl__node__progress" style="width: <?= $post->progress ?>%"></div>
				<div class="nl__node__progress-text"><?= $post->progress ?>%</div>
			<?php endif; ?>
		</div>
	</div>
</div>	