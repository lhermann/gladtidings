<?php
/*------------------------------------*\
                NODE EXAM
\*------------------------------------*/

global $node;
?>
<li class="nl__item nl__item--<?= $node->position ?> nl__item--<?= $node->type ?> nl__item--<?= $node->status ?>">
	<?= $link = h_node_link( $node ) ?>
		<article class="nl__article">
			<header class="nl__article__header">
				<h3 class="nl__article__title"><?= $node->title ?> <?= h_node_button( $node, "u-hidden-palm" ) ?></h3>
				<?php if( h_node_meta( $node ) ): ?>
					<small class="nl__article__meta t-second-text"><?= h_node_meta( $node ) ?></small>
				<?php endif; ?>
			</header>
		</article>
	<?= $link ? '</a>' : '' ?>
	<div class="nl__node nl__node--big">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__link-inner"></div>
		<div class="nl__node__inner <?= $node->status == 'publish' ? 't-main-text t-main-border' : '' ?>"></div>
	</div>
</li>
