<?php
/**
 * Tamplate to display the nodelist items for the course
 */

//var_dump( $post );

// CSS classes
$li_classes = sprintf( 'nl__item %s %s',
	'nl__item--'.$post->ID,
	'nl__item--'.$post->post_type
);

?>

<li class="<?= $li_classes ?>">
	<article class="nl__item__article">
		<header class="nl__item__header">
			<h4 class="nl__item__title"><a class="a--bodycolor" href="<?php the_permalink(); ?>" title="Permanent Link to <?php the_title_attribute(); ?>">1.1 <?php the_title(); ?></a></h4>
		</header>
	</article>
	<div class="nl__node">
		<div class="nl__node__link"></div>
		<div class="nl__node__border">
			<div class="nl__node__inner"><?= $post->ID ?></div>
		</div>
	</div>
</li>