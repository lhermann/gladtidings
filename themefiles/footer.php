<?php
/**
 * Template Footer
 */
?>
		<footer id="page-footer" class="page-footer">
			
			<?php if( !is_home() ): ?>
				<nav class="breadcrumbs breadcrumbs--full t-light-bg t-main-border">
					<div class="wrapper">
						<?php get_template_part( 'templates/breadcrumbs' ); ?>
					</div>
				</nav>
			<?php endif; ?>

			<div class="wrapper">
				<p class="t-text--center">
					Copyright 2015 The Glad Tidings, Inc. &bull; <?php wp_loginout( $_SERVER['REQUEST_URI'], true ); ?>
				</p>
				<p class="t-text--center">
					<?php
						// display execution time
						if( true ) {
							$time = round( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3 );
							print( 'Execution Time: '.$time );
						};
						print( ' &bull; ');
						foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) as $value) {
							if( $value['function'] == 'get_footer' ) {
								print( basename( $value['file'] ) );
							}
						}
					?>
				</p>
			</div><!-- /.wrapper -->
		</footer>

	</div><!-- /.container -->
	
	<?php wp_footer(); ?>
	<!--
	<script src="js/jquery.min.js"></script>
	<script src="js/main.js"></script>
	-->
</body>
</html>