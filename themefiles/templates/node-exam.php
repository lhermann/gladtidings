<?php
/*------------------------------------*\
                NODE EXAM
\*------------------------------------*/

global $_gt;
$_gt->setup_node( $post );

?>
<li class="<?= $_gt->node_classes() ?>">
	<?= $link = $_gt->node_link() ?>
		<article class="nl__article">
			<header class="nl__article__header">
				<h3 class="nl__article__title"><?php the_title() ?> <?= $_gt->node_button() ?></h3>
				<?php if( $output['meta'] ): ?><small class="nl__article__meta t-second-text"><?= $_gt->node_meta() ?></small><?php endif; ?>
			</header>
		</article>
	<?= $link ? '</a>' : '' ?>
	<div class="nl__node nl__node--big">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__link-inner"></div>
		<div class="nl__node__inner <?= $_gt->node_status_num == 3 ? 't-main-text t-main-border' : '' ?>"></div>
	</div>
</li>