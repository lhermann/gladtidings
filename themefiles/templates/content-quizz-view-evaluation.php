<?php
global $_gt;
?>

<?php if( !$_gt->quizz_num_mistakes() ) : ?>
	<div class="u-text--center">
		<p class="p--biglede"><span class="fi fi-check fi--success"></span> <?= __( 'Quizz passed', 'gladtidings' ) ?></p>
		<p><?php $_gt->quizz_print_continue_btn(); ?></p>
	</div>
<?php else: ?>
	<div class="u-text--center">
		<p class="p--biglede"><span class="fi fi-x fi--error"></span> <?= $_gt->quizz_num_mistakes().' '.( $_gt->quizz_num_mistakes() === 1 ? _x( 'Mistake', 'Singular', 'gladtidings' ) : _x( 'Mistakes', 'Plural', 'gladtidings' ) ) ?></p>
		<p><a class="btn btn--theme" href="<?= add_query_arg( 'view', 'question' ) ?>"><span class="fi fi-refresh"></span> Repeat Quizz</a></p>
	</div>
<?php endif; ?>

<h3>Answer Review</h3>
<ol class="list-ui owl--off">

<?php
$evaluation = $_gt->quizz_get_evaluation();

foreach ($evaluation as $key => $a):

	$btn = '';
	if( !$a['correct'] ) {
		$btn = sprintf( '<a class="btn btn--small btn--theme u-pull--right" href="%s" title="%s">%s</a>',
			get_permalink( $a['related_lesson'] ),
			get_the_title( $a['related_lesson'] ),
			"Review Lesson ".get_post_meta( $a['related_lesson'], 'order_nr', true )
		);
	}

	?>

		<li class="list-ui__item owl--narrow">
			<h3 class="lede"><?= $a['title'] ?></h3>
			<p class="u-spacing--narrow">
				<span class="fi <?= $a['correct'] ? 'fi-check fi--success' : 'fi-x fi--error' ?> fi--square"></span> 
				Your Answer: <strong class="<?= $a['correct'] ? 'color--success' : 'color--error' ?>"><?= $a['given_answer'] ?></strong> 
				<?= $btn ?>
			</p>
		</li>

	<?php
endforeach;
?>

</ol>