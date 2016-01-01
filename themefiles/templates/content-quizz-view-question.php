<?php
global $_gt, $fields;

$question = $_gt->quizz_get_question();

?>

<?php $_gt->quizz_print_progress_bar() ?>

<p class="lede"><?= $question['question_text'] ?></p class="lede">
<form class="form" method="POST">
	<fieldset class="form__radio-to-btn owl--narrow t-second-border">
	  <legend>Answer</legend>
	  <?php

	  	foreach ( $question['answers'] as $key => $answer) {
	  		printf( '<div class="form__radiobtn"><input type="radio" name="answer" id="%1$s" value="%2$s"><label class="btn btn--full btn--white" for="%1$s">%3$s</label></div>',
	  			"answer".($key+1),
	  			base64_encode ( $answer ),
	  			$answer
	  		);
	  	}

	  ?>
	</fieldset>
	<div class="u-text--center">
		<button class="btn btn--theme" type="submit">Submit</button>
	</div>
</form>