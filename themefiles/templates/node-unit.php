<?php
/*------------------------------------*\
                NODE UNIT
\*------------------------------------*/

global $_gt;
$_gt->setup_node( $post );

?>
<li class="<?= $_gt->node_classes() ?>">
	<?= $link = $_gt->node_link() ?>
		<article class="nl__article">
			<header class="nl__article__header">
				<h3 class="nl__article__title"><?php the_title() ?> <?= $_gt->node_button() ?></h3>
				<small class="nl__article__meta t-second-text"><?= __( 'Unit', 'gladtidings').' '.$post->order.' '.$_gt->node_meta() ?></small>
			</header>
			<footer class="nl__article__footer">
				<p class="t-comp-text"><?= $_gt->node_footer() ?></p>
			</footer>
		</article>
	<?= $link ? '</a>' : '' ?>
	<div class="nl__node nl__node--big">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__link-inner"></div>
		<div class="nl__node__inner <?= $_gt->node_status_num == 3 ? 't-main-text t-main-border' : '' ?>"><?= $_gt->node_progress() ?></div>
	</div>
</li>