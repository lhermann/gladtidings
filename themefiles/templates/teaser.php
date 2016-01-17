<?php
	$fields = array();
	$fields['img_course_badge'] = default_course_batch( get_field( 'img_course_badge' ) );
	$fields['course_description'] = get_field( 'course_description' );
	$fields['color_main'] = textsave_hex( get_field( 'color_main' ) );
?>
<article class="teaser panel">
	<a class="teaser__badge panel" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><img src="<?= $print['img_course_badge'] ?>" alt="<?= get_the_title().' '.__( 'Badge', 'gladtidings' ); ?>"></a>
	<h2 class="teaser__title"><a style="color: <?= $print['color_main'] ?>;" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<p class="teaser__description">
		<?= $print['course_description'] ?>
	</p>
	<p class="teaser__btn">
		<a class="btn btn--primary" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">View Course</a>
	</p>
</article>