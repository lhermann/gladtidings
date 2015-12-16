<?php
/**
 * Tamplate to display the nodelist items (objects of type 'headline', 'lesson' and 'quizz') inside terms of type 'tax-unit'
 * This template works inside The Loop
 */

global $lesson, $post;

$type = end(explode( '_', reset($lesson) ));
$ID = end($lesson);

// CSS classes
$li_classes = array(
	'nl__item',
	'nl__item--'.$type,
	$ID == $post->ID ? 'nl__item--active' : ''
);

switch ( $type ) {
	case 'headline':
		
		$title = "<h5>$ID</h5>";

		$li_classes[] = 'nl__item--divider'

		?>
			<li class="<?= implode( ' ', $li_classes ) ?>">
				<?= $title; ?>
			</li>
		<?php

		break;
	case 'lesson':
		$type_string = __('Lesson', 'gladtidings');
	case 'quizz':
		$type_string = $type_string ? $type_string : __('Quizz', 'gladtidings');

		$permalink_attr = sprintf( '%s href="%s" title="Permanent Link to %s"',
			'class="a--bodycolor"',
			get_the_permalink( $ID ),
			sprintf( __( 'Permanent Link to %s of %s', 'gladtidings' ),
				$type_string.' '.get_post_meta($ID, 'order_nr')[0],
				__( 'Unit', 'gladtidings' ).' '.$unit->unit_order
			)
		);

		$title = sprintf( '<h4 class="nl__article__title"><a %s>%s</a></h4>',
			$permalink_attr,
			$type_string.' '.get_post_meta($ID, 'order_nr')[0]
		);

		?>
			<li class="<?= implode( ' ', $li_classes ) ?>">
				<article class="nl__article">
					<header class="nl__article__header">
						<?= $title; ?>
					</header>
				</article>
				<?= $permalink_attr ? '<a '.$permalink_attr.'>' : '' ?>
					<div class="nl__node">
						<div class="nl__node__link"></div>
						<div class="nl__node__border"></div>
						<div class="nl__node__inner"></div>
					</div>
				<?= $permalink_attr ? '</a>' : '' ?>
			</li>
		<?php

		break;
}