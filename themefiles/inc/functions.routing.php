<?php
/*------------------------------------*\
    Modify rewrite rules and routing
\*------------------------------------*/

// http://gladtidings:8888/course/bible-basics
// http://gladtidings:8888/course/bible-basics/unit/1
// http://gladtidings:8888/course/bible-basics/unit/1/lesson/the-four-beasts-that-daniel-saw
// http://gladtidings:8888/course/bible-basics/unit/1/quizz/quizz-1/question
// http://gladtidings:8888/course/bible-basics/unit/1/quizz/quizz-1/evaluation
// http://gladtidings:8888/course/bible-basics/exam/title-of-exam

// http://gladtidings:8888/bible-basics/lesson/1

// global $wp_rewrite;
// var_dump( $wp_rewrite );

/**
 * Add a query variables
 * 	'view' 		introduction|question|evaluation
 *	'of-course'	slug
 * 	'of-unit'	int
 */
function gladtidings_get_variables() {
    add_rewrite_tag('%view%', '([^&]+)');
    add_rewrite_tag('%of-course%', '([^&]+)');
    add_rewrite_tag('%of-unit%', '([0-9]{1,})');
}
add_action('init', 'gladtidings_get_variables', 10, 0);



/**
 * Custom URL Routing
 * Tutorial: http://www.hongkiat.com/blog/wordpress-url-rewrite/ 
 */
function gladtidings_rewrite_rules() {
  global $wp_rewrite;
  
  // add rules
  $new_rules = array(
  	// lesson
  	"course/(.?.+?)/unit/([0-9]{1,})/lesson/([^/]+)/?$" => "index.php?lesson=".$wp_rewrite->preg_index(3)."&of-course=".$wp_rewrite->preg_index(1)."&of-unit=".$wp_rewrite->preg_index(2),
  	//quizz
  	"course/(.?.+?)/unit/([0-9]{1,})/quizz/([^/]+)(?:/(introduction|question|evaluation))?/?$" => "index.php?quizz=".$wp_rewrite->preg_index(3)."&of-course=".$wp_rewrite->preg_index(1)."&of-unit=".$wp_rewrite->preg_index(2)."&view=".$wp_rewrite->preg_index(4),
  	// unit
  	"course/(.?.+?)/unit/([0-9]{1,})/?$" => "index.php?unit=".$wp_rewrite->preg_index(2)."&of-course=".$wp_rewrite->preg_index(1),
  	// exam
  	"course/(.?.+?)/exam/([^/]+)(?:/(introduction|question|evaluation))?/?$" => "index.php?quizz=".$wp_rewrite->preg_index(2)."&of-course=".$wp_rewrite->preg_index(1)."&view=".$wp_rewrite->preg_index(3)
  	// course
  	// "(.?.+?)(?:/([0-9]+))?/?$" => "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2)
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
  
  // root to:
  // $wp_rewrite->rules['(.?.+?)(?:/([0-9]+))?/?$'] = "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2);
}
add_action( 'generate_rewrite_rules', 'gladtidings_rewrite_rules' );


/**
 * Manipulate query before it is executed
 */
function your_function_name( $query ) {
	// var_dump( $query ); die();
	// b23d68cc
	$slug = 'my-first-unit';
	$query->query_vars['unit'] = $slug;
	$query->query_vars['name'] = $slug;
}



// add_action( 'pre_get_posts', 'your_function_name' );