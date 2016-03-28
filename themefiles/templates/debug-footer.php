<?php
	if( !WP_DEBUG ) return;
	global $wp_query;
?>

<p class="u-text--center">
	<?php
		// display execution time
		$time = round( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3 );
		// display theme root php file
		$template = '';
		foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) as $value) {
			if( $value['function'] == 'get_footer' ) {
				$template = basename( $value['file'] );
			}
		}
	?>
	Execution Time:  <strong><?= $time ?></strong>
	&bull; Template: <strong><?= $template ?></strong>
	&bull; Post ID:  <strong><?= get_the_ID() ?></strong>
</p>
<p class="u-text--center">
	Model:             <strong><?= $wp_query->debug['model'] ?></strong>
	&bull; Controller: <strong><?= $wp_query->debug['controller'] ?></strong>
	&bull; Action:     <strong><?= $wp_query->debug['action'] ?></strong>
</p>
