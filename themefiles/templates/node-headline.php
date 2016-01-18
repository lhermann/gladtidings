<?php
/*------------------------------------*\
              NODE HEADLINE
\*------------------------------------*/

global $_gt;
?>
<li class="nl__item nl__item--<?= $post->ID ?> nl__item--<?= $post->post_type ?> nl__item--<?= $post->status ?>">
	<article class="nl__article">
		<header class="nl__article__header">
			<h3 class="t-main-text"><?php the_title(); ?></h3>
		</header>
	</article>
	<div class="nl__node nl__node--small">
		<div class="nl__node__link t-second-border"></div>
		<div class="nl__node__border t-second-border"></div>
		<div class="nl__node__inner t-second-text"></div>
	</div>
</li>