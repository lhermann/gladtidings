<?php
	$fields = array();
	$fields['img_course_badge'] = default_course_batch( get_field( 'img_course_badge' ) );
	$fields['course_description'] = get_field( 'course_description' );
	$fields['color_main'] = textsave_hex( get_field( 'color_main' ) );
	// $fields['color_secondary'] = textsave_hex( get_field( 'color_secondary' ) );
	// var_dump($fields);

?>
<article class="teaser panel">
	<a class="teaser__badge panel" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><img src="<?= $fields['img_course_badge'] ?>" alt="<?= get_the_title().' '.__( 'Badge', 'gladtidings' ); ?>"></a>
	<h2 class="teaser__title"><a style="color: <?= $fields['color_main'] ?>;" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<p class="teaser__description"><?= $fields['course_description'] ?></p>
	<a class="teaser__btn btn btn--primary" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">View Course</a>
</article>