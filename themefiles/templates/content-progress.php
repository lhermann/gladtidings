<?php
global $_gt;

$lessons_watched = sprintf( '%s <strong class="t-comp-text">%d/%d</strong> ',
	__( 'Lessons watched:', 'gladtidings' ),
	$_gt->course_lessons_done(), // number of lessons watched
	$_gt->course_lessons_total() // total number of lessons in the course
);

$quizzes_passed = sprintf( '&bull; %s <strong class="t-comp-text">%d/%d</strong> ',
	__( 'Quizzes passed:', 'gladtidings' ),
	$_gt->course_quizzes_done(), // number of quizzes passed
	$_gt->course_quizzes_total() // total number of quizzes in the course
);

$progress_width = $_gt->course_progress();

?>
<h2 class="u-screen-reader-text"><?= __( 'Progress', 'gladtidings' ) ?></h2>
<div class="progress progress--meter" title="<?= __( 'Progress:', 'gladtidings' ).' '.$progress_width.'%' ?>">
	<span class="progress__item t-comp-bg" style="width: <?= $progress_width.'%' ?>"><?= $progress_width.'%' ?></span>
</div>
<p class="u-spacing--narrow t-second-text">
	<strong class="t-comp-text"><?= __( 'Progress:', 'gladtidings' ) ?></strong> 
	<?= $lessons_watched ?>
	<?= $_gt->course_quizzes_total() ? $quizzes_passed : '' ?>
</p>