<?php
/**
 * Tamplate to display a pseudo nodelist item in the page-header
 * This template works in the context of a term of the type 'tax-unit'
*/

global $_gt;

// Footer
$footer = sprintf( '%s &nbsp; %s',
	( $unit->num_lessons ? '<i class="fi fi-video"></i> '.$unit->num_lessons.' Lesson Videos' : '' ),
	( $unit->num_quizzes ? '<i class="fi fi-clipboard-pencil"></i> '.$unit->num_quizzes.' Quizz' : '' )
);
?>

<div class="nl__item nl__item--hero nl__item--<?= $post->post_status ?>">
	<article class="nl__article panel t-second-border">
		<header class="nl__article__header owl--off">
			<p class="t-second-text"><?= __( 'Unit', 'gladtidings').' '.$_gt->get_order_num().' '.$_gt->node_meta() ?></p>
			<h1 class="nl__article__title"><?= $unit->name ?></h1>
		</header>
		<footer class="nl__article__footer t-comp-text">
			<p><?= $footer ?></p>
		</footer>
	</article>
	<div class="nl__node nl__node--bigger">
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner">
			<?php if( $progress !== 100 ): ?>
				<div class="nl__node__progress" style="width: <?= $progress ?>%"></div>
				<div class="nl__node__progress-text"><?= $progress ?>%</div>
			<?php endif; ?>
		</div>
	</div>
</div>	