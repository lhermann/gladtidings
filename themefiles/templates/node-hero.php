<div class="nl__item nl__item--<?= $post->type ?> nl__item--hero nl__item--<?= $post->status ?>">
	<article class="nl__article">
		<header class="nl__article__header">
			<h1 class="nl__article__title">
				<span class="label label--small shadow--strong t-second-text">
					<?php if( $post->type == 'unit' ): ?>
						<?= __( 'Unit', 'gladtidings').' '.$post->order ?>
					<?php else: ?>
						<?= __( 'Exam', 'gladtidings') ?>
					<?php endif; ?>
				</span>
				<span class="shadow--strong-text"><?= $post->title ?></span>
			</h1>
		</header>
		<footer class="nl__article__footer shadow--strong t-comp-text">
			<p>
				<?= h_node_footer( $post, true ) ?>
				<?= __( 'Course', 'gladtidings' ) ?>: <?= $post->parent()->link_to(); ?>
				<?= h_node_meta( $post, true ) ?>
			</p>
		</footer>
	</article>
	<div class="nl__node nl__node--big">
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner <?= $post->status == 'publish' ? 't-main-text t-main-border' : '' ?>">
			<?php if( $post->status == 'active' ): ?>
				<div class="nl__node__progress" style="width: <?= $post->progress() ?>%"></div>
				<div class="nl__node__progress-text"><?= $post->progress() ?>%</div>
			<?php endif; ?>
		</div>
	</div>
</div>
