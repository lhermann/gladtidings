<?php
/**
 * Tamplate to display the nodelist
 * Render each unit of a course
 * $post represents the post-type 'course' object containing the custom fields
 */
global $post;

$unit = get_term_by( 'slug', $post['unit_id'], TAX_UNIT );
$unit = get_unit_meta( $unit );

$output = array();

/**
 * Get Status
 *
 * Note: Read the user meta to determine if he has started the lesson already or completed it
 *       then write the information into the unit_status, so it is available in this loop
 */
$status = 0;
switch ( $post['unit_status'] ) {
	case 'success':		$status++;			// 5
	case 'active':		$status++;			// 4
	case 'public':		$status++;			// 3
	case 'locked':		$status++;			// 2
	case 'scheduled':	$status++; break;	// 1
	case 'disabled':
	default:			return; break;		// 0
}


/**
 * CSS classes
 */
$output['li_classes'] = sprintf( 'nl__item %s %s panel',
	'nl__item--'.$unit->unit_order,
	'nl__item--'.$post['unit_status']
);

/**
 * Unit Link
 */
$output['link'] = ( $status >= 3 ? '<a href="'.get_term_link( $unit ).'">' : '' );

/**
 * Meta States:
 * <span class="color--success">Finished</span>
 * <span class="color--locked">Locked</span>
 * <span class="color--primary">Scheduled for 01/01/2016</span>
 */
$output['status'];
switch ( $status ) {
	case 1: $output['status'] = '<span class="color--primary t-comp-text">'.sprintf( __('Coming up for %s', 'gladtidings'), $post['unit_release_date'] ).'</span>'; break;
	case 2: $output['status'] = '<span class="color--locked t-comp-text">'.sprintf( __('Locked: Complete Unit %s to unlock', 'gladtidings'), $unit->unit_order - 1 ).'</span>'; break;
	case 5: $output['status'] = '<span class="color--success">'.__('Completed', 'gladtidings').'</span>'; break;
}

$output['meta'] = sprintf( '%s %s %s',
	__( 'Unit', 'gladtidings'),
	$unit->unit_order,
	$output['status'] ? '&bull; '.$output['status'] : ''
);

/**
 * Footer Paragraph
 * -> get number of videos and lessons
 */
$output['footer'] = sprintf( '%s %s',
	( $unit->num_lessons == 1 ? '<span class="fi fi-video"></span> 1 '.__( 'Lesson', 'gladtidings') : '<span class="fi fi-video"></span> '.$unit->num_lessons.' '.__( 'Lessons', 'gladtidings') ),
	( $unit->num_quizzes ? '&nbsp; '.( $unit->num_quizzes == 1 ? '<span class="fi fi-clipboard-pencil"></span> 1 '.__( 'Quizz', 'gladtidings') : '<span class="fi fi-clipboard-pencil"></span> '.$unit->num_quizzes.' '.__( 'Quizzes', 'gladtidings') ) : '' )
);

/**
 * Button
 */
$output['button'] = '';
switch ( $status ) {
	case 1: $output['button'] = '<span class="label label--small label--theme">'.__('Coming Soon', 'gladtidings').'</span>'; break;
	case 2: $output['button'] = '<span class="label label--small label--theme">'.__('Locked', 'gladtidings').'</span>'; break;
	case 3: $output['button'] = '<span class="btn btn--theme btn--tiny">'.__('Start Unit', 'gladtidings').'</span>'; break;
	case 4: $output['button'] = '<span class="btn btn--success btn--tiny">'.__('Continue', 'gladtidings').'</span>'; break;
	case 5: $output['button'] = '<span class="btn btn--unstress btn--tiny">'.__('Review', 'gladtidings').'</span>'; break;
}

?>
<li class="<?= $output['li_classes'] ?>">
	<?= $output['link'] ?>
		<article class="nl__article">
			<header class="nl__article__header">
				<h3 class="nl__article__title"><?= $unit->name ?></h3>
				<small class="nl__article__meta t-second-text"><?= $output['meta'] ?></small>
			</header>
			<footer class="nl__article__footer">
				<p class="t-comp-text"><?= $output['footer'] ?></p>
				<?= $output['button'] ?>
			</footer>
		</article>
	<?= $output['link'] ? '</a>' : '' ?>
	<div class="nl__node nl__node--big">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__link-inner"></div>
		<div class="nl__node__inner <?= $status == 3 ? 't-main-text t-main-border' : '' ?>"></div>
	</div>
</li>