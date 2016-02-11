<div class="u-text--center">
	<p class="lede"><?= $post->required_questions ?> questions to answer. You can repeat this quizz as often as you want.</p>
	<p>
		<a class="btn btn--theme" href="question"><?= __( 'Start Quizz', 'gladtidings' ); ?></a>
		<?php if( $post->final_step() ): ?>
			<a class="btn btn--theme" href="evaluation"><?= __( 'View Wrap-Up', 'gladtidings' ) ?></a>
		<?php endif; ?>
	</p>
</div>
