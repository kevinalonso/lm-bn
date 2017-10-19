<?php
/**
 * Header template for the theme
 *
 * Displays all of the <head> section and everything up till <div id="main">.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) & !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="stylesheet" href="../mn/wp-content/themes/twentyeleven/slider/flexslider.css" type="text/css" media="screen" />

<title><?php
	// Print the <title> tag based on what is being viewed.
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		echo esc_html( ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php //bloginfo( 'stylesheet_url' ); ?>
<link rel="stylesheet" type="text/css" media="all" href="http://www.lm-bn.fr/wp-content/themes/twentyeleven/style.css" />
<link rel="stylesheet" href="http://www.lm-bn.fr/wp-content/themes/twentyeleven/responsive.css" type="text/css" media="screen"/>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/*
	 * We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-77112117-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
	<header id="branding" role="banner">
			<hgroup>
				<h1 id="site-title">					<span>						<div class="logo-div">							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">								<img class="logo-header" src="http://www.lm-bn.fr/wp-content/uploads/2017/09/cropped-LM-BN-logo.png"/>							</a>						</div>						<div class="titre-site-div">							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>						</div>					</span>				</h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
			<!--<div class="flexslider">
	          <ul class="slides">
	            <li>
	  	    	    <img src="../mn/wp-content/uploads/2016/01/image1.jpg" />
	              	<p class="flex-caption">Adventurer Cheesecake Brownie</p>
	  	    	</li>
	  	    	<li>
	  	    	    <img src="../mn/wp-content/uploads/2016/01/image1.jpg" />
	              	<p class="flex-caption">Adventurer Lemon</p>
	  	    	</li>
	  	    	<li>
	  	    	    <img src="../mn/wp-content/uploads/2016/01/image1.jpg" />
	              	<p class="flex-caption">Adventurer Donut</p>
	  	    	</li>
	          </ul>
	        </div>-->
	        <!-- jQuery -->
			  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
			  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>')</script>

			  <!-- FlexSlider -->
			  <script defer src="../mn/wp-content/themes/twentyeleven/slider/jquery.flexslider.js"></script>

			  <script type="text/javascript">
			    $(function(){
			      SyntaxHighlighter.all();
			    });
			    $(window).load(function(){
			      $('.flexslider').flexslider({
			        animation: "slide",
			        start: function(slider){
			          $('body').removeClass('loading');
			        }
			      });
			    });
			  </script>
			<!-- Old banner (default banner in twenty eleven)-->
			<!--<?php
				// Check to see if the header image has been removed
				$header_image = get_header_image();
				if ( $header_image ) :
					// Compatibility with versions of WordPress prior to 3.4.
					if ( function_exists( 'get_custom_header' ) ) {
						/*
						 * We need to figure out what the minimum width should be for our featured image.
						 * This result would be the suggested width if the theme were to implement flexible widths.
						 */
						$header_image_width = get_theme_support( 'custom-header', 'width' );
					} else {
						$header_image_width = HEADER_IMAGE_WIDTH;
					}
					?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
					/*
					 * The header image.
					 * Check if this is a post or page, if it has a thumbnail, and if it's a big one
					 */
					if ( is_singular() && has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( $header_image_width, $header_image_width ) ) ) &&
							$image[1] >= $header_image_width ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else :
						// Compatibility with versions of WordPress prior to 3.4.
						if ( function_exists( 'get_custom_header' ) ) {
							$header_image_width  = get_custom_header()->width;
							$header_image_height = get_custom_header()->height;
						} else {
							$header_image_width  = HEADER_IMAGE_WIDTH;
							$header_image_height = HEADER_IMAGE_HEIGHT;
						}
						?>
					<img src="<?php header_image(); ?>" width="<?php echo esc_attr( $header_image_width ); ?>" height="<?php echo esc_attr( $header_image_height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
				<?php endif; // end check for featured image or standard header ?>
			</a>
			<?php endif; // end check for removed header image ?>-->
			<!--end old banner-->

			<!--phone-->
			<div class="contact-marco">
				<h2 class="lastname-marco">Marco, votre peintre</h2>
				<label class="number-marco">06.84.40.74.08</label>
			</div>
			<div class="contact-nicolas">
				<h2 class="lastname-nicolas">Nicolas, votre menuisier</h2>
				<label class="number-nicolas">06.98.60.50.77</label>
			</div>
			<!--end phone-->

			<nav id="access" role="navigation">
				<h3 class="assistive-text"><?php _e( 'Main menu', 'twentyeleven' ); ?></h3>
				<?php /* Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
				<div class="skip-link"><a class="assistive-text" href="#content"><?php _e( 'Skip to primary content', 'twentyeleven' ); ?></a></div>
				<?php if ( ! is_singular() ) : ?>
					<div class="skip-link"><a class="assistive-text" href="#secondary"><?php _e( 'Skip to secondary content', 'twentyeleven' ); ?></a></div>
				<?php endif; ?>
				<?php /* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assigned to the primary location is the one used. If one isn't assigned, the menu with the lowest ID is used. */ ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'my-nav-menu')); ?>
				<?php 
					/*wp_nav_menu( 
						array( 
								'theme_location' => 'primary mobile',
								'menu_class' => 'nav-menu')
						);*/
				?>
			</nav><!-- #access -->
	</header><!-- #branding -->

	<div id="main">
