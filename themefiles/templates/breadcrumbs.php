<?php
/**
 * Outputs the breadcrumbs
 *
 * Possible implementtions:
 *
 * 	<nav class="breadcrumbs breadcrumbs--full">
 *			<?php get_template_part( 'templates/breadcrumbs' ); ?>
 *	</nav>
 *
 * 	<nav class="breadcrumbs breadcrumbs--inline panel">
 *			<?php get_template_part( 'templates/breadcrumbs' ); ?>
 *	</nav>
 */

// var_dump(gt_get_breadcrumbs( $post ));
?>


<nav class="breadcrumbs breadcrumbs--full t-light-bg t-main-border">
	<ul class="wrapper list-inline owl--off">

		<?php foreach( gt_get_breadcrumbs( $post ) as $crumb ): ?>

			<li class="crumb__item">

				<?php if( is_object( $crumb ) ): ?>

					<?= gt_crumb_link( $crumb ); ?>

				<?php else: ?>

					<a class="a--bodycolor" href="<?= home_url() ?>" title="<?= __( 'Home', 'gladtidings' ) ?>"><?= __( 'Home', 'gladtidings' ) ?></a>

				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>
</nav>
