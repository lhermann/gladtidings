<?php get_header(); ?>

<?php
//var_dump($userdata);
?>

	<header id="page-header" class="page-hero t-header-image">

		<nav class="top-bar top-bar--layover owl--off">
			<ul class="top-bar__right">
				
				<li class="top-bar__item top-bar__item--link"><a href="#" title=""> </a></li>
				<?php
					if( $userdata ) {
						print( '<li class="top-bar__item top-bar__item--avatar">Hello '.$userdata->data->display_name.' <a class="top-bar__avatar" href="" title="Your Profile"><img src="'.get_bloginfo('template_directory').'/img/avatar-300.jpg" alt="User Avatar" height="36" width="36"></a></li>' );
					} else {
						// print( '<li class="top-bar__item top-bar__item--btn">'.wp_login_form().'</li>' );
						print( '<li class="top-bar__item top-bar__item--btn"><a class="top-bar__btn btn btn--small" href="'.wp_login_url( $_SERVER['REQUEST_URI'] ).'">Log In</a></li>' );
					}
				?>

			</ul>
		</nav>

		<div class="wrapper">
			<div class="page-hero__frame">
				<h1 class="page-hero__title">The Glad Tidings</h1>
				<div class="page-hero__hr u-header-color"></div>
				<p class="page-hero__subtitle">What the Prophets of old want you to know</p>
			</div>
		</div><!-- /.wrapper -->

	</header>


	<main id="page-content">
		<div class="wrapper">
			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">
					<p class="lede u-text--center">A short description of the course so people know what this website is about. This is a course about the ancient writings of the prophets and applicability today.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">
					<h2 class="text-center">Lessons</h2>

					<?php

						// The Query
						$bible_basics = new WP_Query( 
							array(
								'post_type' => 'obj-course',
								'name' => 'bible-basics'
							)
						);

						//The Loop
						if ( $bible_basics->have_posts() ) {
							//while ( $bible_basics->have_posts() ) {
								$bible_basics->the_post();
								get_template_part( 'templates/nodelist', 'course' );
							//}
						}
						
						/* Restore original Post Data */
						wp_reset_postdata(); 
					?>

					<ul class="nodelist">
						<li class="nl__item nl__item--1 nl__item--unit nl__item--success">
							<a href="unit.html">
								<article class="nl__item__article">
									<header class="nl__item__header">
										<h4 class="nl__item__title">Introduction, the Kingdom of God and Religious Liberty</h4>
										<small class="nl__item__meta">Unit 1 &bull; <span class="color--success">Finished</span></small>
									</header>
									<footer class="nl__item__footer">
										<p><i class="fi fi-video"></i> 10 Lessons &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
										<span class="btn btn--tiny btn--unstress">Review</span>
									</footer>
								</article>
							</a>
							<div class="nl__node nl__node--big">
								<div class="nl__node__link"></div>
								<div class="nl__node__border"></div>
								<div class="nl__node__link__inner"></div>
								<div class="nl__node__inner"></div>
							</div>
						</li>
						<li class="nl__item nl__item--2 nl__item--unit nl__item--active">
							<a href="unit.html">
								<article class="nl__item__article">
									<header class="nl__item__header">
										<h4 class="nl__item__title">God’s Plan and God’s Special People</h4>
										<small class="nl__item__meta">Unit 2</small>
									</header>
									<footer class="nl__item__footer">
										<p><i class="fi fi-video"></i> 8 Lessons &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
										<span class="btn btn--tiny btn--success">Continue</span>
									</footer>
								</article></a>
							<div class="nl__node nl__node--big">
								<div class="nl__node__link"></div>
								<div class="nl__node__border"></div>
								<div class="nl__node__link__inner"></div>
								<div class="nl__node__inner u-nodelist-progress">31%</div>
							</div>
						</li>
						<li class="nl__item nl__item--3 nl__item--unit nl__item--public">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title">The Ottoman Empire and how God used Mohammed to Punish Rome</h4>
									<small class="nl__item__meta">Unit 3 &bull; <span class="locked">Locked</span></small>
								</header>
								<footer class="nl__item__footer">
									<p><i class="fi fi-video"></i> 10 Lessons &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
									<span class="btn btn--tiny">Start Lesson</span>
								</footer>
							</article>
							<div class="nl__node nl__node--big">
								<div class="nl__node__link"></div>
								<div class="nl__node__border"></div>
								<div class="nl__node__link__inner"></div>
								<div class="nl__node__inner"></div>
							</div>
						</li>
						<li class="nl__item nl__item--4 nl__item--unit nl__item--locked">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title">A Message to the World</h4>
									<small class="nl__item__meta">Unit 4 &bull; <span class="locked">Locked: Complete Unit 3 to unlock</span></small>
								</header>
								<footer class="nl__item__footer">
									<p><i class="fi fi-video"></i> 5 Lessons &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
									<span class="badge badge--tiny badge--unstress">Locked</span>
								</footer>
							</article>
							<div class="nl__node nl__node--big">
								<div class="nl__node__link"></div>
								<div class="nl__node__border"></div>
								<div class="nl__node__link__inner"></div>
								<div class="nl__node__inner"></div>
							</div>
						</li>
						<li class="nl__item nl__item--5 nl__item--unit nl__item--scheduled">
								<article class="nl__item__article">
									<header class="nl__item__header">
										<h4 class="nl__item__title">Health, History and Prophets</h4>
										<small class="nl__item__meta">Unit 5 &bull; <span class="color--primary">Scheduled for 01/01/2016</span></small>
									</header>
									<footer class="nl__item__footer">
										<p><i class="fi fi-video"></i> 6 Lessons &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
										<span class="badge badge--tiny">Scheduled</span>
									</footer>
								</article>
							<div class="nl__node nl__node--big">
								<div class="nl__node__link"></div>
								<div class="nl__node__border"></div>
								<div class="nl__node__link__inner"></div>
								<div class="nl__node__inner"></div>
							</div>
						</li>
						<li class="nl__item nl__item--6 nl__item--unit nl__item--disabled">
								<article class="nl__item__article">
									<header class="nl__item__header">
										<h4 class="nl__item__title">Last Things</h4>
										<small class="nl__item__meta">Unit 6 &bull; <span class="locked">Disabled</span></small>
									</header>
									<footer class="nl__item__footer">
										<p><i class="fi fi-video"></i> 6 Lessons &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
									</footer>
								</article>
							<div class="nl__node nl__node--big">
								<div class="nl__node__link"></div>
								<div class="nl__node__border"></div>
								<div class="nl__node__link__inner"></div>
								<div class="nl__node__inner"></div>
							</div>
						</li>
					</ul><!-- /.nodelist -->
			
				</section>
				<aside class="layout__item u-1/3-lap-and-up">
					<div class="whitespace whitespace--h2"></div>
					<div class="panel">
						<h4 class="text-center">Progress</h4>
						<div class="progress">
							<span class="progress__meter" style="width: 24%">24%</span>
						</div>
						<p><a href="#">Log In</a> or <a href="#">Sign Up</a> to save your progress.</p>
					</div>
					<div class="panel">
						<p>This course consists of <strong>45 Vidos</strong> and <strong>6 Quizzes</strong></p>
					</div>
					<div class="panel">
						<p>These latin phrases are just placeholder text.</p>
						<p>Eius incidunt nostrum vero temporibus, consectetur ea est provident fugit.</p>
						<p>Recusandae nemo accusantium, reiciendis id voluptatum debitis cupiditate, distinctio ipsum.</p>
					</div>
				</aside>
			</div>
		</div><!-- /.wrapper -->
	</main>

<?php get_footer(); ?>
