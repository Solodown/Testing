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

				endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

</div><!-- #main-content -->

<?php get_footer(); ?>