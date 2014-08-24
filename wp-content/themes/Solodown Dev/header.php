<?php
/**
 
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
		</script>
		
		<script type="text/javascript">
		
		$( document ).ready(function() {

			//Find the number of days in a month/year
			function daysInMonth(month,year) {
				var dd = new Date(year, month, 0);
				return dd.getDate();
			}
			
			
			//Adjust the number of select options based on number of days in the selected month
			function adjustDates(numDays, $sel) {
				var $options = $sel.find('option');
				var dates = $options.length;
				//append/remove missing dates
				if (dates > numDays) { //remove
					$options.slice(numDays+1).remove();
				} else { //append
					var dateOptions = [];
					
					for (var date = dates ; date <= numDays; date++) {
						dateOptions.push('<option value="' + date + '">' + date + '</option>');
					}
					$sel.append(dateOptions.join('')); //reduces DOM call
				}
    }
			
			//Display correct number or days for selected month/year
			$('#day').click(function () {
				
				var month = $('#month').val();
				var year = $('#year').val();
				
				var numDays = daysInMonth(month,year);
				
				adjustDates(numDays, $('#day'));
				
				
				
			});
			
			//Adjust displayed day number if month change places is out of range
			$('#month').change(function () {
				
				var day = $('#day').val();
				var month = $('#month').val();
				var year = $('#year').val();
				
				var numDays = daysInMonth(month,year);
				
				if(day > numDays){
					document.getElementById("day").selectedIndex = numDays;
				}
			});

		});
		
		</script>
		
		<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-repyear' ); ?>
		
		
        
		
		<?php wp_head(); ?>
    </head>

<!-- #BEGIN BODY -->
<body>


	<header>
		<div id="home_header" class="wrap_full" role="banner">
			
			<nav id="access">
				<?php wp_nav_menu( array( 'theme_location' => 'header_menu' ) ); ?>
			</nav>

			<div id="logo">
				<div class="header_logo">
				</div>

				<div class="header_triangle"></div>
			</div>
		</div>
	</header><!-- #masthead -->

	<div class="header_bottom_divider"></div>


