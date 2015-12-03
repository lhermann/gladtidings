<?php
/**
 * Tamplate to display a pseudo nodelist item in the page-header
 * This template works in the context of a term of the type 'tax-unit'
*/

// Get term and meta information
$term = get_queried_object();
$meta = get_term_meta( $term->term_id );

// Unit number
$unit = 'Unit '.( $meta['unit_order'][0]+1 );

// Footer
$footer = sprintf( '%s &nbsp; %s',
	( $meta['num_lesson_videos'][0] ? '<i class="fi fi-video"></i> '.$meta['num_lesson_videos'][0].' Lesson Videos' : '' ),
	( $meta['num_lesson_quizzes'][0] ? '<i class="fi fi-clipboard-pencil"></i> '.$meta['num_lesson_quizzes'][0].' Quizz' : '' )
);
?>

<div class="nl__item nl__item--hero nl__item--active">
	<article class="nl__article">
		<header class="nl__article__header owl--off">
			<p><?= $unit ?></p>
			<h1 class="nl__article__title"><?= $term->name ?></h1>
		</header>
		<footer class="nl__article__footer">
			<p><?= $footer ?></p>
		</footer>
	</article>
	<div class="nl__node nl__node--bigger">
		<div class="nl__node__link"></div>
		<div class="nl__node__border"></div>
		<div class="nl__node__link__inner"></div>
		<style>
			.nl__node__inner:before {
				width: 0%;
			}
		</style>
		<div class="nl__node__inner">0%</div>
	</div>
</div>	