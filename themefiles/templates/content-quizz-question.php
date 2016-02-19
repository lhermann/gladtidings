<h3 class="progress__label"><span class="u-screen-reader-text">' . __( 'Quizz Progress', 'gladtidings' ) . '</span></h3>
<?= h_stepper_bar( $post ) ?>

<p class="lede"><?= $post->current_question['question_text'] ?></p class="lede">
<form class="form" method="POST">
	<fieldset class="form__radio-to-btn owl--narrow t-second-border">
		<legend>Answer</legend>

		<?php foreach ( $post->get_answers() as $key => $answer): ?>

			<div class="form__radiobtn">
				<input type="radio" name="answer" id="<?= "answer".($key+1) ?>" value="<?= base64_encode( $answer ) ?>" />
				<label class="btn btn--full btn--white" for="<?= "answer".($key+1) ?>"><?= $answer ?></label>
			</div>

		<?php endforeach; ?>

	</fieldset>
	<div class="u-text--center">
		<button class="btn btn--theme" type="submit">Submit</button>
	</div>
</form>
