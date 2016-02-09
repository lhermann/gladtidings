<?php
/*------------------------------------*\
               NODE LESSON
\*------------------------------------*/
global $node;

?>
<li class="nl__item nl__item--<?= $node->ID ?> nl__item--<?= $node->type ?> nl__item--<?= $node->status ?> <?= $node->ID === $post->ID ? 'nl__item--current t-second-border' : '' ?>">
	<article class="nl__article">
		<header class="nl__article__header">
			<h4 class="nl__article__title">
				<span class="label label--small label--fixed label--theme"><?= $node->order ?></span>
				<?= $node->link_to() ?>
			</h4>
		</header>
	</article>
	<div class="nl__node">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner t-second-text"></div>
	</div>
</li>
