<?php
global $_gt;
?>

<?php if( !$_gt->mistakes ) : ?>
	<div class="u-text--center">
		<p class="p--biglede"><span class="fi fi-check fi--success"></span> <?= __( 'Quizz passed', 'gladtidings' ) ?></p>
		<p><?php $_gt->print_continue_btn(); ?></p>
	</div>
<?php else: ?>
	<div class="u-text--center">
		<p class="p--biglede"><span class="fi fi-x fi--error"></span> <?= $_gt->mistakes.' '._n( 'Mistake', 'Mistakes', $_gt->mistakes, 'gladtidings' ) ?></p>
		<p><a class="btn btn--theme" href="<?= add_query_arg( 'view', 'question' ) ?>"><span class="fi fi-refresh"></span> Repeat Quizz</a></p>
	</div>
<?php endif; ?>

<h3>Answer Review</h3>
<ol class="list-ui owl--off">

	<?php foreach ( $_gt->get_evaluation() as $key => $answer ): ?>

		<?php //var_dump( $answer ); ?>

		<li class="list-ui__item owl--narrow">
			<h3 class="lede"><?= $answer['title'] ?></h3>
			<p class="u-spacing--narrow">
				<span class="fi <?= $answer['correct'] ? 'fi-check fi--success' : 'fi-x fi--error' ?> fi--square"></span>
				<?= __( 'Your Answer:', 'gladtidings' ) ?> <strong class="<?= $answer['correct'] ? 'color--success' : 'color--error' ?>"><?= $answer['given_answer'] ?></strong>
				<?php $_gt->print_related_lesson_btn( $answer ) ?>
			</p>
		</li>

	<?php endforeach; ?>

</ol>