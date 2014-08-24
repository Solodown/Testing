<?php
/*
Plugin Name: logikal-projects
Description: News Item Creator and Manager
Version: 1.0
Author: Logikal Code
Author URI: http://www.logikalcode.com
*/
add_action('admin_menu', 'logikalProjects_menu');
add_shortcode('show_projects', 'getProjects_func');
add_shortcode('show_featured_mechanical', 'getFeaturedMech_func');
add_shortcode('show_featured_civil', 'getFeaturedCiv_func');
add_shortcode('show_featured_structural', 'getFeaturedStruct_func');
add_shortcode('show_featured_piping', 'getFeaturedPiping_func');
add_shortcode('show_featured_residential', 'getFeaturedResi_func');
add_shortcode('show_featured_commercial', 'getFeaturedCom_func');
add_shortcode('show_featured_other', 'getFeaturedOther_func');

function logikalProjects_menu() {
	add_menu_page('Projects', 'Projects', 'edit_published_posts', 'logikal_projects', 'logikalProjects_options');
	add_submenu_page('logikal_projects', 'Projects Gallery', 'Projects Gallery', 'edit_published_posts', 'projectGallery', 'getGalleryView');
	add_media_page('Project Gallery', 'Projects', 'read', 'project_gallery', '');
}

function getGalleryView(){
	include('views/project_gallery.php');
}

function logikalForm() {
	//add_menu_page('LogikalForm', 'LogikalForm', 'edit_published_posts', 'logikal_form', 'displayFormMenu');
	add_menu_page( 'Logikal Forms', 'Logikal Forms', 'edit_published_posts', 'logikal_form', 'getDisplayFormsView');
	add_submenu_page( 'logikal_form', 'Create Form', 'Create Form', 'edit_published_posts', 'create_form', 'getCreateFormView');
}

//add_action("admin_print_scripts-{$my_page}", 'my_plugin_page_scripts');
//add_action("admin_print_styles-{$my_page}", 'my_plugin_page_styles');
 
function my_plugin_page_styles() {
    wp_enqueue_style('thickbox');
}
  
function my_plugin_page_scripts() {
    wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
}

function getFeaturedMech_func(){
	$type = 0;
	return getFeatured_func($type);
}
function getFeaturedCiv_func(){
	$type = 1;
	return getFeatured_func($type);
}
function getFeaturedStruct_func(){
	$type = 2;
	return getFeatured_func($type);
}
function getFeaturedPiping_func(){
	$type = 3;
	return getFeatured_func($type);
}
function getFeaturedResi_func(){
	$type = 4;
	return getFeatured_func($type);
}
function getFeaturedCom_func(){
	$type = 5;
	return getFeatured_func($type);
}
function getFeaturedOther_func(){
	$type = 6;
	return getFeatured_func($type);
}

function getFeatured_func($type){
	
	global $wpdb;
	
	$posting = $wpdb->get_row("SELECT * FROM logikalprojects WHERE type='$type' AND featured_flag='1'");
	$out .= '<strong class="featured"><h3><b>Project Name:</b></h3> <p>'.$posting->name.'</p></strong><h3><b>Client: </b>'.$posting->client.'</h3></strong><h3><b>Description</h3></b><p>'.$posting->description.'</p>';
	return $out;
}

function getProject_func(){
	
	global $wpdb;
		
	$posting = $wpdb->get_row("SELECT * FROM logikalprojects");
	$out .= '<strong class="posting"><h3><b>Project Name:</b></h3> <p>'.$posting->name.'</p></strong><h3><b>Client: </b>'.$posting->client.'</h3></strong><h3><b>Description</h3></b><p>'.$posting->description.'</p>';
	return $out;
	
}

function getProjects_func($atts){
	
	global $wpdb;
	
	$qry = "SELECT * FROM logikalprojects";
	$result = mysql_query($qry);
	$date = getdate();	
	while($row = mysql_fetch_row($result))
	{
		
		$dateArray = explode(',',$row[2]);
		list(,$month,$day) = explode(" ",$dateArray[1]);
		$year = $dateArray[2];
		
		if(strtotime($day.' '.$month.' '.$year) < strtotime('now'))
		{
			mysql_query("DELETE FROM logikalprojects WHERE id=".$row[0]);
		}
	}
	
	$qry = "SELECT * FROM logikalprojects ORDER BY table_position ASC";
	if(isset($atts['limit'])){
		$qry .= " LIMIT 4";
	}
	$qry = mysql_query($qry)
		or die("NEWS SELECT ERROR:".mysql_error());
	$out .='<div id="posts">';
	$count = 1;
	
	
	while($row = mysql_fetch_row($qry)){
		
		if($count <= 4){
			
			$out .= '<div id="project"><div id="project_image'.$count.'" class="project_image"></div>';
			
			$out .= '<ul id="project'.$count.'" class="project_posting">';
		
			$out .= $linkStart.'<strong class="project_name"><p>'.$row[1].'</p></strong><h3><b>Client: </b>'.$row[2].'</h3><h3><b>Description</h3></b><p>'.$row[7].'</p>'.$linkEnd;
			$count ++;
		
			$out .= '</ul></div>';
		}
		
	}
	
	
	$out .= '</div>';
	
	return $out;
}
function logikalProjects_options(){
	
	global $wpdb;
	
	//Create projects table in DB if it doesnt already exist
	$sql = 'CREATE TABLE IF NOT EXISTS logikalprojects(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255),	
	client VARCHAR(255),
	location VARCHAR(255),
	type INT(11),
	featured_flag TINYINT(1),
	table_position INT(11),
	description LONGTEXT,
	gallery_id INT(11)
	)';
	//Submit query
	if(!mysql_query($sql))
		die('ERROR: '.mysql_error());
		
	//Retreive current page link
	$link = $_SERVER['REQUEST_URI'];
	$link = explode('?',$link);
	$link = $link[0].'?page=logikal_projects';


	//echo '<link rel="stylesheet" type="text/css" media="all" href="'.get_baseurl().'/wp-content/plugins/logikal-projects/styles/default.css" />';
	$action = $_GET['action'];
	echo '<div id="pageWrap">';
	
	//If action is set to create, display the project creation inputs
	if($action == 'create'){
		logikalCreateProject($link);
	}
	
	//If action is set to edit, display project edit inputs
	else if($action == 'edit'){
		logikalEditProject($link);
	}
	
	//If a displayed project creation or edit form is submitted...
	else{
		//If new project is saved, insert data into DB
		if($_POST['submit'] == 'Save'){
			$name = $_POST['name'];
			$client = $_POST['client'];
			$description = $_POST['description'];
			$location = $_POST['location'];
			$featured = $_POST['featured'];
			$type = $_POST['type'];
			$table_position = (int)$_POST['table_position'];
			
			//If an invlalid table position is entered, set to 0
			if(!is_int($table_position)){
				$table_position = 0;
			}
			//Insert project information in DB
			$iq = "INSERT INTO logikalprojects SET name='$name', client='$client', gallery_id='0', description='$description', location='$location', type ='$type', featured_flag = '$featured', table_position='$table_position'";
			if(!mysql_query($iq))
				die("ERROR: ".mysql_error());
		}
		
		//Save project changes and update project info in DB
		else if($_POST['submit'] == 'Save Changes'){
			$id = $_POST['id'];
			$name = $_POST['name'];
			$client = $_POST['client'];
			$description = $_POST['description'];
			$location = $_POST['location'];
			$featured = $_POST['featured'];
			$type = $_POST['type'];
			$table_position = (int)$_POST['table_position'];
			
			//If an invlalid table position is entered, set to 0
			if(!is_int($table_position)){
				$table_position = 0;
			}
			
			//Update project information in DB
			$uq = "UPDATE logikalprojects SET name='$name', client='$client', description='$description', location='$location', featured_flag='$featured', type='$type', table_position='$table_position' WHERE id='$id'";
			if(!mysql_query($uq))
				die("ERROR: ".mysql_error());
		}
		//Delete project info from DB by project id
		else if($_POST['submit'] == 'Delete'){
			$id = $_POST['id'];
			if(!mysql_query("DELETE FROM logikalprojects WHERE id='$id'"))
				die("ERROR: ".mysql_error());	
		}

		logikalDisplayProjectHome($link);
	}
	echo '</div>';
}

function logikalCreateProject($link){
	?>
    <h1>Add a Project Project</h1>
    <div class="projectForm">
        <form action="<?php echo $link ?>" method="post" enctype="multipart/form-data">
        	<ul>
            	<li><div class="project_label"><label for="name">Project Name</label></div></br>
				<input type="text" name="name" id="name" /></li>
                
                <li><div class="project_label"><label for="featured">Featured </label></div></br>
                <select name="featured" id="feaured">
  					<option value="0">No</option>
  					<option value="1">Yes</option>
				</select> 
                </li>
                
                <li><div class="project_label"><label for="type">Project Type </label></div>
                <select name="type" id="type">
  					<option value="0">Mechanical</option>
  					<option value="1">Civil</option>
                    <option value="2">Structural</option>
                    <option value="3">Piping</option>
                    <option value="4">Residential</option>
                    <option value="5">Commercial</option>
                    <option value="6">Other</option>
				</select> 
                </li>
                
                <li><div class="project_label"><label for="client">Client Name </label></div><input type="text" name="client" id="client" /></li>
            	
              <li><div class="project_label"><label for="description">Description</label></div><?php wp_editor( $editInfo[7], "description"); ?></li>
                
                <li><div class="project_label"><label for="table_position">Project Listing Order</label></div><input type="text" name="table_position" id="table_position" value="0"/></li>
            </ul>
            <div class="submitButtons">
	            <input type="submit" name="submit" value="Cancel" /><input type="submit" name="submit" value="Save" />
            </div>
        </form>
    </div>
    <?php
}

function logikalEditProject($link){
	$id = $_GET['id'];
	$editInfo = mysql_query("SELECT * FROM logikalprojects WHERE id='$id'")
		or die("ERROR: ".mysql_error());
    $editInfo = mysql_fetch_row($editInfo);
	?>
    <h1>Edit Project Item</h1>
    <div class="projectForm">
        <form action="<?php echo $link ?>" method="post"  enctype="multipart/form-data">
        	<input type="hidden" name="id" value="<?php echo $id?>" />
        	<ul>
            	<li><div class="project_label"><label class="project_label" for="name">Project Name</label></br>
				<input type="text" name="name" id="name" value="<?php echo $editInfo[1]?>"/></li>
            	
                <li><div class="project_label"><label class="project_label" for="featured">Featured </label></br>
                <select name="featured" id="feaured">
  					<option value="0">No</option>
  					<option value="1">Yes</option>
				</select> 
                </li>
                
                <li><div class="project_label"><label for="type">Project Type </label></div>
                <select name="type" id="type">
  					<option value="0">Mechanical</option>
  					<option value="1">Civil</option>
                    <option value="2">Structural</option>
                    <option value="3">Piping</option>
                    <option value="4">Residential</option>
                    <option value="5">Commercial</option>
                    <option value="6">Other</option>
				</select> 
                </li>
                
                <li><div class="project_label"><label for="client">Client Name </label></div><input type="text" name="client" id="client" value="<?php echo $editInfo[2]?>"/></li>             
            	
                <li><div class="project_label"><label for="description">Description</label></div><?php wp_editor( $editInfo[7], "description"); ?> </li>
                
                <li><div class="project_label"><label for="table_position">Project Listing Order</label></div><input type="text" name="table_position" id="table_position" value="<?php echo $editInfo[6]?>"/></li>
            </ul>
            <div class="submitButtons">
	            <input type="submit" name="submit" value="Cancel" /><input type="submit" name="submit" value="Delete" /><input type="submit" name="submit" value="Save Changes" />
            </div>
        </form>
    </div>
    <?php
}
function logikalDisplayProjectHome($link){
	$projects = mysql_query('SELECT id, name, table_position FROM logikalprojects ORDER BY id')
			or die('ERROR: '.mysql_error());
	?>
    <h1>Projects</h1>
	<a href="<?php echo $link ?>&action=create">Create A New Project</a><br /><br />
	<table class="widefat wp-list-table" cellspacing="0">
		<thead>
			<tr>
				<th style='width: 50px;'>ID</th>
                <th style='width: 130px;'>Order</th>
                <th style='width: 250px;'>Name</th>
                <th >Image Gallery</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th style='width: 50px;'>ID</th>
                <th style='width: 130px;'>Order</th>
                <th style='width: 250px;'>Name</th>
                <th>Image Gallery</th>
                
			</tr>
		</tfoot>
		<tbody>
			<?php
				while($row = mysql_fetch_row($projects)){
					echo '<tr>';
					echo '<td><a href="'.$link.'&action=edit&id='.$row[0].'">'.$row[0].'</a></td>';
					echo '<td style="padding-left:20px;">'.$row[2].'</a></td>';
					echo '<td><a href="'.$link.'&action=edit&id='.$row[0].'">'.$row[1].'</a></td>';
					
					$project_id = $row[0];
			
					$gallery = mysql_query("SELECT name FROM logikalprojects_galleries WHERE project_id='$project_id'")
						or die('ERROR: '.mysql_error());

					$gallery_name = mysql_fetch_row($gallery);
					
					if($gallery_name[0]==""){
						echo '<td>-- No Gallery Linked --</td>';
					}
					else{
						echo '<td>'.$gallery_name[0].'</td>';
					}
					echo '</tr>';
				}?>
		</tbody>
	</table>
    <?php
}
?>