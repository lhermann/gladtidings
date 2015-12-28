<?php
/**
 * Tamplate to display the nodelist items (objects of type 'headline', 'lesson' and 'quizz') inside terms of type 'tax-unit'
 * This template works inside The Loop
 */

global $unit, $_gt;

// $_gt->lesson_setup( $post );
// var_dump($unit);

// CSS classes
$li_classes = array(
	"nl__item",
	"nl__item--{$post->ID}",
	"nl__item--{$post->post_type}"
);
if( $_gt->item_done( $post->post_type, $post->ID ) ) $li_classes[] = "nl__item--success";
// if( $_gt->lesson_done() ) $li_classes[] = "nl__item--success";
// var_dump( $_gt->item_done( $post->post_type, $post->ID ) );


switch ( $post->post_type ) {
	case 'headline':
		
		$title = sprintf( '<h3 class="t-main-text">%s</h3>',
			$post->post_title
		);

		$node_class = 'nl__node--small';

		break;
	case 'lesson':
	case 'quizz':
	default:
		
		$title = sprintf( '<h4 class="nl__article__title">%s <a class="a--bodycolor" href="%s" title="Permanent Link to %s">%s</a></h4>',
			'<span class="label label--small label--fixed label--theme">'.$post->order_nr.'</span>',
			get_the_permalink(),
			the_title_attribute( array( 'echo' => false ) ),
			$post->post_title
		);

		$node_class = '';

		break;
}

?>

<li class="<?= implode( $li_classes, ' ' ); ?>">
	<article class="nl__article">
		<header class="nl__article__header">
			<?= $title; ?>
		</header>
	</article>
	<div class="nl__node <?= $node_class; ?>">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner t-second-text"></div>
	</div>
</li>