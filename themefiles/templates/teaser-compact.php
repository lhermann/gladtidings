<li class="teaser teaser--compact owl--offall">
	<img class="teaser__badge" src="<?= $post->batch_src('thumbnail') ?>" alt=""><!--
	--><div class="teaser__inner">
		<h4 class="teaser__title"><?= $post->link_to() ?></h4>
		<div class="progress progress--meter progress--compact" title="<?= __( 'Progress:', 'gladtidings' ).' '.$post->progress().'%' ?>">
			<span class="progress__item t-comp-bg" style="width: <?= $post->progress().'%' ?>"></span>
		</div>
	</div>
</li>
