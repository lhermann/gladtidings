<?php
global $_gt;
?>

<div class="u-text--center">
	<p class="lede"><?= $_gt->num_questions() ?> questions to answer. You can repeat this quizz as often as you want.</p>
	<p>
		<a class="btn btn--theme" href="<?= add_query_arg( 'view', 'question' ) ?>">Start Quizz</a>
		<?php if( $_gt->is_completed() ) print( '<a class="btn btn--theme" href="'.add_query_arg( 'view', 'evaluation' ).'">View Wrap-Up</a>' ); ?>
	</p>
</div>