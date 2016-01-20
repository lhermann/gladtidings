<?php
global $_gt;

// var_dump( $_gt->current_question );
//$question = $_gt->quizz_get_question();

?>

<?php $_gt->print_progress_bar() ?>

<p class="lede"><?= $_gt->current_question['question_text'] ?></p class="lede">
<form class="form" method="POST">
	<fieldset class="form__radio-to-btn owl--narrow t-second-border">
		<legend>Answer</legend>

		<?php foreach ( $_gt->get_answers() as $key => $answer): ?>

			<div class="form__radiobtn">
				<input type="radio" name="answer" id="<?= "answer".($key+1) ?>" value="<?= base64_encode ( $answer ) ?>" />
				<label class="btn btn--full btn--white" for="<?= "answer".($key+1) ?>"><?= $answer ?></label>
			</div>

		<?php endforeach; ?>

	</fieldset>
	<div class="u-text--center">
		<button class="btn btn--theme" type="submit">Submit</button>
	</div>
</form>