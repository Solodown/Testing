<?php  get_header(); ?>
	
	<div id="banner_wrap" class="wrap_full"	>
		<div id="banner" class="wrap_980">
			<?php echo do_shortcode("[metaslider id=294]"); ?> 
		</div>
	</div>
	

	<div id="content_wrap" role="main">
		
		
		<div class="sidebar"></div>
		
		<div class="content">
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				the_content();

			endwhile;
		?>
		</div>
		
	</div><!-- #content -->


<?php get_footer(); ?>