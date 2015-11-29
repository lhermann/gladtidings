<?php
/**
 * Template Footer
 */
?>
	<footer id="page-footer" class="page-footer">
		<div class="wrapper">
			<p class="text--center">
				Copyright 2015 The Glad Tidings, Inc. &bull; <?php wp_loginout( $_SERVER['REQUEST_URI'], true ); ?>
				<?php
					// display execution time
					if( true ) {
						$time = round( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3 );
						print( '&bull; Execution Time: '.$time );
					};
				?>
			</p>
		</div><!-- /.wrapper -->
	</footer>
	
	<?php wp_footer(); ?>
	<!--
	<script src="js/jquery.min.js"></script>
	<script src="js/main.js"></script>
	-->
</body>
</html>