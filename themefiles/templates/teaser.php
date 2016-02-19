<article class="teaser panel">
	<a class="teaser__badge panel" href="<?= gt_get_permalink( $post ) ?>" rel="bookmark" title="<?= __('Permalink to:', 'gladtidings') . ' ' . $post->title ?>">
		<img src="<?= $post->batch_src() ?>" alt="">
	</a>
	<h2 class="teaser__title">
		<?= $post->link_to() ?>
	</h2>
	<p class="teaser__description">
		<?= get_field( 'course_description' ) ?>
	</p>
	<p class="teaser__btn">
		<a class="btn btn--primary" href="<?= gt_get_permalink( $post ) ?>" rel="bookmark" title="<?= __('Permalink to:', 'gladtidings') . ' ' . $post->title ?>"><?= __( 'View Course', 'gladtidings' ) ?></a>
	</p>
</article>
