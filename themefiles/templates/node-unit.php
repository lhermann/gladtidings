<?php
/*------------------------------------*\
                NODE UNIT
\*------------------------------------*/

global $_gt;

$_gt->setup_context( $post );

/**
 * Get Status
 *
 * Note: Read the user meta to determine if he has started the lesson already or completed it
 *       then write the information into the unit_status, so it is available in this loop
 */
$status = 0;
switch ( $_gt->get_status( $post ) ) {
	case 'success':		$status++;			// 5
	case 'active':		$status++;			// 4
	case 'publish':		$status++;			// 3
	case 'locked':		$status++;			// 2
	case 'coming':		$status++; break;	// 1
	case 'draft':
	default:			return; break;		// 0
}

/**
 * CSS classes
 */
$output['li_classes'] = sprintf( 'nl__item %s %s panel',
	'nl__item--'.$post->order,
	'nl__item--'.$_gt->get_status( $post )
);

/**
 * Unit Link
 */
$output['link'] = ( $status >= 3 ? '<a href="'.gt_get_permalink( $unit ).'">' : '' );

/**
 * Meta States:
 * <span class="color--success">Completed</span>
 * <span class="color--locked">Locked: Complete Unit %s first</span>
 * <span class="color--primary">Coming soon: 01/01/2016</span>
 */
$output['meta'] = '';
switch ( $status ) {
	case 1: $output['meta'] = '<span class="color--primary t-comp-text">'.__('Coming soon', 'gladtidings').': '.$post['unit_release_date'].'</span>'; break;
	case 2: $output['meta'] = '<span class="color--locked t-comp-text">'.sprintf( __('Locked: Complete Unit %s first', 'gladtidings'), $post->order - 1 ).'</span>'; break;
	case 5: $output['meta'] = '<span class="color--success">'.__('Completed', 'gladtidings').'</span>'; break;
}

/**
 * Footer Paragraph
 * -> get number of videos and lessons
 */
$output['footer'] = sprintf( '%s %s',
	( $_gt->num_lessons() == 1 ? '<span class="fi fi-video"></span> 1 '._x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ) : '<span class="fi fi-video"></span> '.$_gt->num_lessons().' '._x( 'Lessons', 'Post Type General Name', 'gladtidings' ) ),
	( $_gt->num_quizzes() ? '&nbsp; '.( $_gt->num_quizzes() == 1 ? '<span class="fi fi-clipboard-pencil"></span> 1 '._x( 'Quizz', 'Post Type Singular Name', 'gladtidings' ) : '<span class="fi fi-clipboard-pencil"></span> '.$_gt->num_quizzes().' '._x( 'Quizzes', 'Post Type General Name', 'gladtidings' ) ) : '' )
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

/**
 * Show Progress percentage
 */
$output['progress'] = '';
if( $status === 4 ) {
	$output['progress'] = sprintf( '<div class="nl__node__progress" style="width: %1$s%%"></div><div class="nl__node__progress-text">%1$s</div>',
		$_gt->get_progress()
	);
}

// var_dump( $_gt->get_progress( $post ) );

?>
<li class="<?= $output['li_classes'] ?>">
	<?= $output['link'] ?>
		<article class="nl__article">
			<header class="nl__article__header">
				<h3 class="nl__article__title"><?php the_title() ?></h3>
				<small class="nl__article__meta t-second-text"><?= __( 'Unit', 'gladtidings').' '.$post->order.' '.$output['meta'] ?></small>
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
		<div class="nl__node__inner <?= $status == 3 ? 't-main-text t-main-border' : '' ?>"><?= $output['progress'] ?></div>
	</div>
</li>