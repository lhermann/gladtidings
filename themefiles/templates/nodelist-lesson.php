<?php
/**
 * Tamplate to display the nodelist items (objects of type 'headline', 'lesson' and 'quizz') inside terms of type 'tax-unit'
 * This template works inside The Loop
 */

global $lesson, $post;

$type = end(explode( '_', reset($lesson) ));
$ID = end($lesson);

// is active?
$a = $ID == $post->ID;

// CSS classes
$li_classes = array(
	'nl__item',
	'nl__item--'.$type,
	$a ? 'nl__item--active' : ''
);

switch ( $type ) {
	case 'headline':
		
		$title = "<h3 class=\"u-screen-reader-text\">$ID</h3>";

		$li_classes[] = 'nl__item--divider';
		$li_classes[] = 't-second-bg';

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

		if( $a ) $li_classes[] = 't-second-border';

		?>
			<li class="<?= implode( ' ', $li_classes ) ?>">
				<article class="nl__article">
					<header class="nl__article__header">
						<?= $title; ?>
					</header>
				</article>
				<?= $permalink_attr ? '<a '.$permalink_attr.'>' : '' ?>
					<div class="nl__node">
						<div class="nl__node__link t-second-border"></div>
						<div class="nl__node__border <?= !$a ? 't-second-border' : '' ?>"></div>
						<div class="nl__node__inner <?= !$a ? 't-second-text' : '' ?>"></div>
					</div>
				<?= $permalink_attr ? '</a>' : '' ?>
			</li>
		<?php

		break;
}