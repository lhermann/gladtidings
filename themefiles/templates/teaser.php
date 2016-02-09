<?php

	// var_dump( $post );

	// $print = array();
	// $print['img_course_badge'] = gt_get_course_batch( $post, 'full' );
	// $print['course_description'] = get_field( 'course_description' );
	// // limit description leangth
	// if( strlen( $print['course_description'] ) > 140 ) {
	// 	preg_match( '/^[^\b]{1,140}(?=\b\s)/', $print['course_description'], $matches );
	// 	$print['course_description'] = $matches[0].' ...';
	// }
	// $print['color_main'] = textsave_hex( get_field( 'color_main' ) );
?>
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
