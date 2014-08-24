<?php  get_header(); ?>

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
				?>
					<h2> <?php the_title(); ?> </h2>
				<?php
					the_content();
					?>
					<div id="date_selector">
			
				<select required  size="1" name="day" id="day" class="custom-select">
						<option value="0" >DD</option>
						<?php for ($i = 1; $i <= 28; $i++){?>
						<option value="<?php echo $i ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
				</select> 
				
				<select required size="1" name="month" id="month" class="custom-select">
					<option value="0" >MM</option>
					<?php for ($i = 1; $i <= 12; $i++){?>
						<option value="<?php echo $i ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select> 
				
				<select required size="1" name="year" id="year" class="custom-select">
					<option value="0" >YYYY</option>
					<?php for ($i = 1950; $i <= 2014; $i++){?>
						<option value="<?php echo $i ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select> 
				
			</div>
				<?php	
				endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

</div><!-- #main-content -->

<?php get_footer(); ?>