<?php
/**
 * Display the lesson content
 * This template should be called inside the loop of post_type 'lesson'
 */
global $meta, $unit, $_gt;

// prepare elements
// $label_order = 			sprintf( '<span class="label label--small">%s %s</span>', __( 'Lesson', 'gladtidings' ), $meta['order_nr'][0] );
$label_order = 			sprintf( '<span class="label label--small label--theme">%s</span>', $meta['order_nr'][0] );
$button_attachment = 	( $url = wp_get_attachment_url( $meta['video_attachment'][0] ) ? '<a class="btn btn--small btn--primary" href="'.$url.'" target="_blank"><i class="fi fi-download"></i> Download Script</a>' : '' );

//print
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'h-entry' ); ?>>
	<header class="hgroup">
		<h1 class="hgroup__title"><span class="label label--small label--theme"><?= $post->order ?></span> <?php the_title(); ?></h1>
	</header>
	<div class="e-content">
		<p><?= $_gt->get_description() ?></p>
		<p><?php $_gt->print_attachment_link() ?></p>
	</div>
	<footer class="u-text--right">
		<?php //$_gt->print_continue_btn(); ?>
	</footer>
</article>