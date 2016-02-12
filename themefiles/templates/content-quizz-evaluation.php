<?php if( !$post->mistakes ): ?>
	<div class="u-text--center">
		<p class="p--biglede"><span class="fi fi-check fi--success"></span> <?= __( 'Quizz passed', 'gladtidings' ) ?></p>
		<p><?= h_quizz_continue_btn( $post ); ?></p>
	</div>
<?php else: ?>
	<div class="u-text--center">
		<p class="p--biglede"><span class="fi fi-x fi--error"></span> <?= $post->mistakes.' '._n( 'Mistake', 'Mistakes', $post->mistakes, 'gladtidings' ) ?></p>
		<p><a class="btn btn--theme" href="<?= gt_get_permalink( $post, 'question' ) ?>"><span class="fi fi-refresh"></span> Repeat Quizz</a></p>
	</div>
<?php endif; ?>

<h3>Answer Review</h3>
<ol class="list-ui owl--off">

	<?php foreach ( $post->get_evaluation() as $key => $answer ): ?>

		<li class="list-ui__item owl--narrow">
			<h3 class="lede"><?= $answer['title'] ?></h3>
			<p class="u-spacing--narrow">
				<span class="fi <?= $answer['correct'] ? 'fi-check fi--success' : 'fi-x fi--error' ?> fi--square"></span>
				<?= __( 'Your Answer:', 'gladtidings' ) ?> <strong class="<?= $answer['correct'] ? 'color--success' : 'color--error' ?>"><?= $answer['given_answer'] ?></strong>
				<?= h_related_lesson_btn( $answer['related_lesson'] ) ?>
			</p>
		</li>

	<?php endforeach; ?>

</ol>
