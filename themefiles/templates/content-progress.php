<?php
global $_gt;

$num_lessons = (int)get_post_meta( $post->ID, 'num_lessons', true );
$num_quizzes = (int)get_post_meta( $post->ID, 'num_quizzes', true );

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
<div class="progress">
	<span class="progress__meter t-comp-bg" style="width: <?= $progress_width.'%' ?>"><?= $progress_width.'%' ?></span>
</div>
<p class="u-spacing--narrow t-second-text">
	<strong class="t-comp-text"><?= __( 'Progress', 'gladtidings' ) ?>:</strong> 
	<?= $lessons_watched ?>
	<?= $num_quizzes ? $quizzes_passed : '' ?>
</p>