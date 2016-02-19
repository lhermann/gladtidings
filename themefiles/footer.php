<?php
/**
 * Template Footer
 */
?>
		<footer id="page-footer" class="page-footer">

			<?php if( !is_home() && !is_404() ) get_template_part( 'templates/breadcrumbs' ); ?>

			<div class="wrapper">
				<p class="u-text--center">
					Copyright 2015 The Glad Tidings, Inc. &bull; <?php wp_loginout( $_SERVER['REQUEST_URI'], true ); ?>
				</p>
				<p class="u-text--center">
					<?php
						// display execution time
						if( true ) {
							$time = round( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3 );
							print( 'Execution Time: '.$time );
						};
						// display theme root php file
						print( ' &bull; ');
						foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) as $value) {
							if( $value['function'] == 'get_footer' ) {
								print( basename( $value['file'] ) );
							}
						}
						// display post ID
						print( ' &bull; ');
						print( 'Post ID: ' . get_the_ID() );
					?>
				</p>
				<p class="u-text--center">
					<?php global $wp_query; ?>
					Model: <?= $wp_query->debug['model'] ?>
					&bull; Controller: <?= $wp_query->debug['controller'] ?>
					&bull; Action: <?= $wp_query->debug['action'] ?>
				</p>
			</div><!-- /.wrapper -->
		</footer>

	</div><!-- /.container -->

	<?php wp_footer(); ?>
</body>
</html>
