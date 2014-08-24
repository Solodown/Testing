<?php

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        
		<link href='http://fonts.googleapis.com/css?family=Lato|Play' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
		</script>
		
		<script type="text/javascript">
		
		
		
		</script>
		
		<?php wp_head(); ?>
    </head>

<!-- #BEGIN BODY -->
<body>

	<header class="mage">
		<div class="hearth_logo hidden"></div>
		<nav id="top-access" class="mage-access hidden">
				<ul class="hero-nav">
					<li class="hero-selection selected">Mage</li>
					<li class="hero-selection">Warrior</li>
					<li class="hero-selection">Druid</li>
					<li class="hero-selection">Warlock</li>
					<li class="hero-selection">Hunter</li>
					<li class="hero-selection">Shaman</li>
					<li class="hero-selection">Paladin</li>
					<li class="hero-selection">Priest</li>

				</ul>
		</nav>

		<nav id="access" class="mage-access">
				<?php wp_nav_menu( array( 'theme_location' => 'header_menu' ) ); ?>
		</nav>
		
		<div id="home_header" class="wrap_full" role="banner">
			
			<div class="home_banner">
				<div id="hero-name" class="hidden">MAGE CARDS</div>
				<div id ='hero-overview' class="mage-access"></div>
				<div class='hero'></div>
			</div>
			<div class="home_bottom_bar mage">

			</div>
		</div>
	</header><!-- #masthead -->

	<div class="header_bottom_divider"></div>


