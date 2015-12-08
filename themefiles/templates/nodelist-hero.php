<?php
/**
 * Tamplate to display a pseudo nodelist item in the page-header
 * This template works in the context of a term of the type 'tax-unit'
*/

// Get term and meta information
global $unit;

// Unit number
$number = 'Unit '.$unit->unit_order;

// Footer
$footer = sprintf( '%s &nbsp; %s',
	( $unit->num_lesson_videos ? '<i class="fi fi-video"></i> '.$unit->num_lesson_videos.' Lesson Videos' : '' ),
	( $unit->num_lesson_quizzes ? '<i class="fi fi-clipboard-pencil"></i> '.$unit->num_lesson_quizzes.' Quizz' : '' )
);
?>

<div class="nl__item nl__item--hero nl__item--active">
	<article class="nl__article">
		<header class="nl__article__header owl--off">
			<p><?= $number ?></p>
			<h1 class="nl__article__title"><?= $unit->name ?></h1>
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