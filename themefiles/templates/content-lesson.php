<?php
/**
 * Display the lesson content
 * This template should be called inside the loop of post_type 'lesson'
 */
global $meta, $course, $unit;

// get variables
$meta 	= get_post_meta( $post->ID, '', true );
$course = get_course( $post->ID );
$unit 	= get_unit( $post->ID );

// prepare elements
$badge_order = 			sprintf( '<span class="badge badge--tiny">%s</span>', ($unit->unit_order).'.'.$meta['order_nr'][0] );
$button_attachment = 	( $url = wp_get_attachment_url( $meta['video_attachment'][0] ) ? '<a class="btn btn--small" href="'.$url.'" target="_blank"><i class="fi fi-download"></i> Download Script</a>' : '' );

//print
?>
<h4><?= $badge_order ?> <?php the_title(); ?></h4>
<p><?= $meta['video_description'][0] ?></p>
<p>
	<?= $button_attachment ?>
	<!-- <a href="#" class="btn btn--small"><i class="fi fi-social-facebook"></i> Share</a> -->
</p>
<hr>
<div class="breadcrumb flex">
	<div class="flex__item">
		<?php get_template_part( 'templates/breadcrumbs', 'lesson' ); ?>
	</div>
	<div class="flex__item">
		<a class="btn btn--success" href="#" title="View Next Lesson">Continue <i class="fi fi-arrow-right"></i></a>
	</div>
</div>