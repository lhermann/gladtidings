<?php
/**
 * Tamplate to display the nodelist items (objects of type 'headline', 'lesson' and 'quizz') inside terms of type 'tax-unit'
 * This template works inside The Loop
 */

global $unit;

// CSS classes
$li_classes = sprintf( 'nl__item %s %s',
	'nl__item--'.$post->ID,
	'nl__item--'.$post->post_type
);


switch ( $post->post_type ) {
	case 'headline':
		
		$title = sprintf( '<h3>%s</h3>',
			$post->post_title
		);

		$node_class = 'nl__node--small';

		break;
	case 'lesson':
	case 'quizz':
	default:
		
		$title = sprintf( '<h4 class="nl__article__title">%s <a class="a--bodycolor" href="%s" title="Permanent Link to %s">%s</a></h4>',
			'<span class="label label--small label--fixed">'.$unit->unit_order.'.'.$post->order_nr.'</span>',
			get_the_permalink(),
			the_title_attribute( array( 'echo' => false ) ),
			$post->post_title
		);

		$node_class = '';

		break;
}

?>

<li class="<?= $li_classes ?>">
	<article class="nl__article">
		<header class="nl__article__header">
			<?= $title; ?>
		</header>
	</article>
	<div class="nl__node <?= $node_class; ?>">
		<div class="nl__node__link"></div>
		<div class="nl__node__border"></div>
		<div class="nl__node__inner"></div>
	</div>
</li>