<?php
/**
 * Display the lesson content
 * This template should be called inside the loop of post_type 'lesson'
 */
global $meta, $unit;

// prepare elements
// $label_order = 			sprintf( '<span class="label label--small">%s %s</span>', __( 'Lesson', 'gladtidings' ), $meta['order_nr'][0] );
$label_order = 			sprintf( '<span class="label label--small label--theme">%s</span>', $meta['order_nr'][0] );
$button_attachment = 	( $url = wp_get_attachment_url( $meta['video_attachment'][0] ) ? '<a class="btn btn--small btn--primary" href="'.$url.'" target="_blank"><i class="fi fi-download"></i> Download Script</a>' : '' );

//print
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry' ); ?>>
	<header>
		<h4 class="p-name"><?= $label_order ?> <?php the_title(); ?></h4>
	</header>
	<div class="e-content">
		<p><?= $meta['video_description'][0] ?></p>
		<p>
			<?= $button_attachment ?>
		</p>
	</div>
</article>