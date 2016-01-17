<?php
/*------------------------------------*\
                NODE LESSON
\*------------------------------------*/

global $_gt;
?>
<li class="nl__item nl__item--<?= $post->ID ?> nl__item--<?= $post->post_type ?> nl__item--<?= $post->status ?>">
	<article class="nl__article">
		<header class="nl__article__header">
			<h4 class="nl__article__title">
				<span class="label label--small label--fixed label--theme"><?= $post->order ?></span> 
				<a class="a--bodycolor" href="<?= $_gt->item_permalink( $post ) ?>" title="<?= the_title_attribute( array( 'before' => __('Permalink to: ', 'gladtidings'), 'echo' => false ) ) ?>"><?php the_title(); ?></a>
			</h4>
		</header>
	</article>
	<div class="nl__node">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner t-second-text"></div>
	</div>
</li>