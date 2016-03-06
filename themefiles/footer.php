<?php
/**
 * Template Footer
 */
?>

		</main>

		<?php get_template_part( 'templates/flyout', 'right' ); ?>

		<footer id="page-footer" class="page-footer">

			<?php if( !is_home() && !is_404() ) get_template_part( 'templates/breadcrumbs' ); ?>

			<div class="wrapper">
				<p class="u-text--center">
					Copyright 2015 The Glad Tidings, Inc. &bull; <?php wp_loginout( $_SERVER['REQUEST_URI'], true ); ?>
				</p>

				<?php get_template_part( 'templates/debug', 'footer' ); ?>

			</div><!-- /.wrapper -->
		</footer>

	</div><!-- /.container -->

	<?php wp_footer(); ?>
</body>
</html>
