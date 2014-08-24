<?php /*Template Name: Hero Profile*/ ?>

<?php  get_header('home'); ?>

<div id="content_wrap" role="main">
	
		<div class="sidebar"></div>
		<div class="content">
				

			<div class="info-box">
				<div class="card_class"></div>
				<div id="demo-preview" class="preview hidden">
					<?php 
					$boxes = array(1,2,3,4,5,6,7,8);

					foreach($boxes as $box){
						echo '<div class="box">'.$box.'</div>';
					}

					?>
				</div>
				<div id="profile-preview" class="preview hidden">
				</div>
				
			</div>
		</div>
</div><!-- #content -->