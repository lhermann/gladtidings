<?php
/*------------------------------------*\
                NODE UNIT
\*------------------------------------*/
global $node;

?>
<li class="nl__item nl__item--<?= $node->position ?> nl__item--<?= $node->type ?> nl__item--<?= $node->status ?>">
	<?= $link = h_node_link( $node ) ?>
		<article class="nl__article">
			<header class="nl__article__header">
				<h3 class="nl__article__title"><?= $node->title ?> <?= h_node_button( $node ) ?></h3>
				<small class="nl__article__meta t-second-text"><?= __( 'Unit', 'gladtidings').' '.$node->order ?> <?= h_node_meta( $node ) ?></small>
			</header>
			<footer class="nl__article__footer">
				<p class="t-comp-text"><?= ''//$_gt->node_footer() ?></p>
			</footer>
		</article>
	<?= $link ? '</a>' : '' ?>
	<div class="nl__node nl__node--big">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__link-inner"></div>
		<div class="nl__node__inner <?= $node->status == 'publish' ? 't-main-text t-main-border' : '' ?>">
			<?php if( isset($node->progress) ): ?>
				<div class="nl__node__progress" style="width: <?= $node->progress ?>%"></div>
				<div class="nl__node__progress-text"><?= $node->progress ?>%</div>
			<?php endif; ?>
		</div>
	</div>
</li>
