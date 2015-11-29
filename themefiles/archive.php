<?php get_header(); ?>

<?php
//var_dump($userdata);
?>

	<header id="page-header">

		<nav class="top-bar owl--off" role="navigation">
			<ul class="top-bar__left">
				
				<li class="top-bar__item top-bar__item--title">
					<a href="index.html">The Glad Tidings</a>
				</li>

			</ul>

			<ul class="top-bar__right">
				
				<li class="top-bar__item top-bar__item--link"><a href="index.html" title="Course Overview">Course</a></li>
				<?php
					if( $userdata ) {
						print( '<li class="top-bar__item top-bar__item--avatar">Hello '.$userdata->data->display_name.' <a class="top-bar__avatar" href="" title="Your Profile"><img src="'.get_bloginfo('template_directory').'/img/avatar-300.jpg" alt="User Avatar" height="36" width="36"></a></li>' );
					} else {
						// print( '<li class="top-bar__item top-bar__item--btn">'.wp_login_form().'</li>' );
						print( '<li class="top-bar__item top-bar__item--btn"><a class="top-bar__btn btn btn--small" href="'.wp_login_url( $_SERVER['REQUEST_URI'] ).'">Log In</a></li>' );
					}
				?>
				<li class="top-bar__item top-bar__item--menu-icon"><a href="#"><span>Menu</span></a></li>

			</ul>
		</nav>

		<div class="page-hero page-hero--skinny">
			<div class="wrapper">
				<div class="nl__item nl__item--hero nl__item--active">
					<article class="nl__item__article">
						<header class="nl__item__header owl--off">
							<h1 class="nl__item__title">Module 2</h1>
							<p class="nl__item__subtitle">The Kingdom of God and Religious Liberty</p>
						</header>
						<footer class="nl__item__footer">
							<p><i class="fi fi-video"></i> 10 Lesson Videos &nbsp; <i class="fi fi-clipboard-pencil"></i> 1 Quizz</p>
						</footer>
					</article>
					<div class="nl__node nl__node--bigger">
						<div class="nl__node__link"></div>
						<div class="nl__node__border"></div>
						<div class="nl__node__link__inner"></div>
						<style>
							.nl__node__inner:before {
								width: 31%;
							}
						</style>
						<div class="nl__node__inner">31%</div>
					</div>
				</div>	
			</div>
		</div><!-- /.wrapper -->

	</header>


	<main id="page-content">

		<div class="wrapper">

			<?php var_dump($wp_query); ?>

			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">
					<p class="lede text--center">A short description of the course so people know what this website is about. This is a course about the ancient writings of the prophets and applicability today.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">
					<ul class="nodelist">
						<li class="nl__item nl__item--lesson nl__item--heading nl__item--success">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h3>Daniel 2 and Introduction</h3>
								</header>
							</article>
							<div class="nl__node nl__node--small">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner"></div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video nl__item--success">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.1 The Four Beasts that Daniel Saw</a></h4>
								</header>
							</article>
							<div class="nl__node nl__node--std">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">1</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video nl__item--success">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.2 Introduction</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">2</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video nl__item--success">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.3 Contrast faithfulness to God with the wisdom of the world’s wise men and bomoh’s</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">3</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--heading">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h3>Daniel 7</h3>
								</header>
							</article>
							<div class="nl__node nl__node--small">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner"></div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video nl__item--success">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.4 The Four Beasts that Daniel Saw</a></h4>
								</header>
							</article>
							<div class="nl__node nl__node--std">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">4</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.5 The Fourth Power’s Break-up Followed by the Judgment</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">5</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.6 Judgment and the Books</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">6</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.7 The Four Beasts Die</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">7</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--heading">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h3>The Kingdom of God and Religious Liberty</h3>
								</header>
							</article>
							<div class="nl__node nl__node--small">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner"></div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.8 The Limit of Human Authority (Daniel 3, 6)</a></h4>
								</header>
							</article>
							<div class="nl__node nl__node--std">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">8</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.9 The Conversion Story of the Leader of Medina and the Story of Gideon</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">9</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--lesson nl__item--video">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.10 Romans 13 and Daniel 4</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">10</div>
								</div>
							</div>
						</li>
						<li class="nl__item nl__item--locked nl__item--lesson nl__item--quizz">
							<article class="nl__item__article">
								<header class="nl__item__header">
									<h4 class="nl__item__title"><a class="a--bodycolor" href="lesson.html">2.11 Quizz</a></h4>
								</header>
							</article>
							<div class="nl__node">
								<div class="nl__node__link"></div>
								<div class="nl__node__border">
									<div class="nl__node__inner">11</div>
								</div>
							</div>
						</li>
					</ul>
				</section>
				<aside class="layout__item u-1/3-lap-and-up">
					<div>
						<a href="lesson.html" class="btn btn--full">Continue Lesson</a>
					</div>
					<div class="panel">
						<p><strong class="b--shout">103 min</strong> of video lessons in total.</p>
						<p>You completed 32 min and have <strong class="b--shout">71 min</strong> left.</p>
						<p>You have completed <strong class="b--shout">31%</strong> of this lesson.</p>
					</div>
					<div class="panel">
						<h4>Description</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta porro quibusdam numquam voluptas doloribus, corporis!</p>
					</div>
				</aside>
			</div>
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
