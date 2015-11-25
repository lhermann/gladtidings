<?php
/**
 * Tamplate to display the nodelist
 */

// var_dump( $post );

//get all the units
$units = get_field( 'units_repeater' );

// check if the repeater field has rows of data
if( $units ):

	print( '<ul class="nodelist">' );

	foreach ( $units as $key => $unit ) {
		// var_dump( $unit );

		/*
		 * Render Each Unit
		 */

		// Get Status
		/*
		 * Note: Read the user meta to determine if he has started the lesson already or completed it
		 *       then write the information into the unit_status, so it is available in this loop
		 */
		if( $unit['unit_status'] == 'disabled' )	$s = 0;
		if( $unit['unit_status'] == 'scheduled' )	$s = 1;
		if( $unit['unit_status'] == 'locked' )		$s = 2;
		if( $unit['unit_status'] == 'public' )		$s = 3;
		if( $unit['unit_status'] == 'active' ) 		$s = 4;
		if( $unit['unit_status'] == 'success' )		$s = 5;

		// If disabled, don't render!
		if( $s === 0 ) continue;

		// CSS classes
		$unit['li-classes'] = sprintf( 'nl__item %s nl__item--unit %s',
			'nl__item--'.$key,
			'nl__item--'.$unit['unit_status']
		);

		// Unit Link
		$unit['link'] = ( $s >= 3 ? '<a href="'.get_term_link( $post->post_name, 'tax-course' ).'">' : '' );
		//$unit['link'] = ( $s >= 3 ? '<a href="'.get_term_link( 'escape-unit-title', 'tax-unit' ).'">' : '' );

		/*
		 * Meta States:
		 * <span class="color--success">Finished</span>
		 * <span class="color--locked">Locked</span>
		 * <span class="color--primary">Scheduled for 01/01/2016</span>
		 */
		$status = '';
		if( $s == 1 ) $status = '<span class="color--primary">Scheduled for '.$unit['unit_release_date'].'</span>';
		if( $s == 2 ) $status = '<span class="color--locked">Locked: Complete Unit '.($key).' to unlock</span>';
		if( $s == 5 ) $status = '<span class="color--success">Completed</span>';

		$unit['meta'] = sprintf( 'Unit %s %s',
			$key + 1,
			$status ? '&bull; '.$status : ''
		);

		// Footer Paragraph
		// -> get number of videos and lessons
		$count = array( 'videos' => 0, 'quizzes' => 0 );
		if( $unit['unit_items_repeater'] ) {
			foreach( $unit['unit_items_repeater'] as $i => $item ) {
				if( $item['acf_fc_layout'] == 'lesson_video' ) $count['videos']++;
				if( $item['acf_fc_layout'] == 'lesson_quizz' ) $count['quizzes']++;
			}
		}

		$unit['footer'] = sprintf( '%s %s',
			( $count['videos'] == 1 ? '<span class="fi fi-video"></span> 1 Lesson' : '<span class="fi fi-video"></span> '.$count['videos'].' Lessons' ),
			( $count['quizzes'] ? '&nbsp; '.( $count['quizzes'] == 1 ? '<span class="fi fi-clipboard-pencil"></span> 1 Quizz' : '<span class="fi fi-clipboard-pencil"></span> '.$count['quizzes'].' Quizzes' ) : '' )
		);

		// Button
		$unit['button'] = '';
		if( $s == 1 ) $unit['button'] = '<span class="badge badge--tiny">Scheduled</span>';
		if( $s == 2 ) $unit['button'] = '<span class="badge badge--tiny badge--unstress">Locked</span>';
		if( $s == 3 ) $unit['button'] = '<span class="btn badge--tiny">Start Lesson</span>';
		if( $s == 4 ) $unit['button'] = '<span class="btn badge--tiny btn--success">Continue</span>';
		if( $s == 5 ) $unit['button'] = '<span class="btn btn--tiny btn--unstress">Review</span>';


		?>

		<li class="<?= $unit['li-classes'] ?>">
			<?= $unit['link'] ?>
				<article class="nl__item__article">
					<header class="nl__item__header">
						<h4 class="nl__item__title"><?= $unit['unit_title'] ?></h4>
						<small class="nl__item__meta"><?= $unit['meta'] ?></small>
					</header>
					<footer class="nl__item__footer">
						<p><?= $unit['footer'] ?></p>
						<?= $unit['button'] ?>
					</footer>
				</article>
			<?= $unit['link'] ? '</a>' : '' ?>
			<div class="nl__node nl__node--big">
				<div class="nl__node__link"></div>
				<div class="nl__node__border"></div>
				<div class="nl__node__link__inner"></div>
				<div class="nl__node__inner"></div>
			</div>
		</li>

		<?php
	}

	print( '</ul><!-- /.nodelist -->' );

else :

    // no rows found

endif;