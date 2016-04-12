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
?>
<nav class="breadcrumbs breadcrumbs--full">
	<ul class="wrapper list-inline owl--offall">

		<?php foreach( gt_get_breadcrumbs( $post ) as $crumb ): ?>

			<li class="crumb__item">

				<?php if( is_object( $crumb ) ): ?>

					<?= gt_crumb_link( $crumb, ['class' => 'a--unstress'] ); ?>

				<?php else: ?>

					<a class="a--unstress" href="<?= home_url() ?>" title="<?= __( 'Home', 'gladtidings' ) ?>"><?= __( 'Home', 'gladtidings' ) ?></a>

				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>
</nav>
