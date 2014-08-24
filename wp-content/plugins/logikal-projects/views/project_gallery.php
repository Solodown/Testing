<?php
//Add gallery images DB table if it doesn't exist
$sql = 'CREATE TABLE IF NOT EXISTS logikalprojects_images(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
filename VARCHAR(255),
filepath VARCHAR(255),
gallery_id INT(11)
)';
//Submit query
if(!mysql_query($sql))
	die('ERROR: '.mysql_error());

//Add gallery DB table if it doesn't exist
$sql = 'CREATE TABLE IF NOT EXISTS logikalprojects_galleries(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
project_id INT(11),
num_images INT(11)
)';
//Submit query
if(!mysql_query($sql))
	die('ERROR: '.mysql_error());
//echo '<link rel="stylesheet" type="text/css" media="all" href="'.get_baseurl().'/wp-content/plugins/logikal-projects/styles/default.css" />';

$action = $_GET['action'];

$link = $_SERVER['REQUEST_URI'];
$link = explode('?',$link);
$link = $link[0].'?page=projectGallery';
$link_home = $link[0].'?page=projectGallery';

if($action == 'add'){
	addGallery($link);
}

else if($action == 'edit'){
	editGallery($link);
}

else{
	 
	 //if submit "add gallery" is pressed...
	if($_POST['submit'] == 'Save'){
		$name = $_POST['galleryname'];
		$connected_project_id = $_POST['project'];
	
		//Insert project gallery information in DB
		$iq = "INSERT INTO logikalprojects_galleries SET name='$name', project_id='$connected_project_id', num_images='0'";
		if(!mysql_query($iq))
			die("ERROR: ".mysql_error());
	}	
	
	//if submit "update gallery" is pressed...
	else if($_POST['submit'] == 'Save Changes'){
		
		$gallery_id = $_POST['id'];
		$name = $_POST['galleryname'];
		$connected_project_id = $_POST['project'];
		
		//Update project information in DB
		$uq = "UPDATE logikalprojects_galleries SET name='$name', project_id='$connected_project_id' WHERE id='$gallery_id'";
		if(!mysql_query($uq))
			die("ERROR: ".mysql_error());
			
	}
	//Delete project info from DB by project id
	else if($_POST['submit'] == 'Delete'){
			$id = $_POST['id'];
			if(!mysql_query("DELETE FROM logikalprojects_galleries WHERE id=".$id))
				die("ERROR: ".mysql_error());	
	}
	displayProjectGalleries($link);
}

//*********************************************
//	ADD NEW PROJECT GALLERY
//*********************************************
function addGallery($link){
	
	$projects = mysql_query('SELECT id, name FROM logikalprojects')
			or die('ERROR: '.mysql_error());
	$project_counter = 1;
	?>
	<!-- create gallery -->
		<h2><?php _e('Add New Project Gallery', 'project_galley') ;?></h2>
		<form name="add_project_gallery" id="add_project_gallery_form" method="POST" action="<?php echo $link; ?>" accept-charset="utf-8" >
			<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('New Gallery', 'project_gallery') ;?>:</th>
				<td><input type="text" size="35" name="galleryname" placeholder="Gallery Name" value="" /><br />
				
				<i>( <?php _e('Allowed characters are', 'project_gallery') ;?>: a-z, A-Z, 0-9, -, _ )</i></td>
			</tr>
            <tr valign="top">
            	<th scope="row"><?php _e('Connect To Project', 'project_gallery') ;?>:</th>
                <td><select id="project" name="project">
                	<option value="0">Pick a project  </option>
					<?php 
					while($row = mysql_fetch_row($projects)){
						echo "<option value=".$project_counter.">".$row[1]."</option>";

						$project_counter ++;
					}
					?>
                </select>
                </td>
            </tr>
			
			</table>
			 <div class="submitButtons">
	            <input type="submit" name="submit" value="Cancel" /><input type="submit" name="submit" value="Save" />
            </div>
		</form>

<?php
}

//***************************************************
//	DISPLAY ALL PROJECT GALLERIES
//***************************************************
function displayProjectGalleries($link){
	$project_galleries = mysql_query('SELECT id, name, project_id FROM logikalprojects_galleries')
			or die('ERROR: '.mysql_error());
	?>
	<!-- display all project galleries -->
    <h1>Project Galleries</h1>
    <div id="gallery_button" class="add_button"><a href="<?php echo $link ?>&action=add">Add New Gallery</a></div><br />
  
    <table class="widefat wp-list-table" cellspacing="0">
		<thead>
			<tr>
				<th style='width: 50px;'>ID</th>
                <th style='width: 250px;'>Name</th>
				<th style='width: 250px;'>Connected Project</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th style='width: 50px;'>ID</th>
                <th style='width: 250px;'>Name</th>
				<th style='width: 250px;'>Connected Project</th>
			</tr>
		</tfoot>
		<tbody>
		<?php 
		while($row = mysql_fetch_row($project_galleries)){
		?>
        <tr>
			<td><?php echo $row[0]; ?></td>
			<td><?php echo"<a href=".$link.'&action=edit&id='.$row[0].">".$row[1]; ?></td>
			
			<?php 
				$project_id = $row[2];
				
				$project = mysql_query("SELECT name FROM logikalprojects WHERE id='$project_id'")
					or die('ERROR: '.mysql_error());
	
				$project_name = mysql_fetch_row($project);
			?>
			<td><?php echo $project_name[0]; ?></td>
		</tr>
		<?php 
		}
		?>	
		</tbody>
	</table>
    
    <?php
}

//*********************************************
//	EDIT PROJECT GALLERY
//*********************************************
function editGallery($link){
	
	$gallery_id = $_GET['id'];
	$project_images = mysql_query('SELECT id, filename, filepath FROM logikalprojects_images WHERE gallery_id='.$gallery_id)
			or die('ERROR: '.mysql_error());
	
	$project_gallery = mysql_query('SELECT name, project_id FROM logikalprojects_galleries WHERE id='.$gallery_id)
		or die('ERROR: '.mysql_error());
	$gallery = mysql_fetch_row($project_gallery);
	
	$projects = mysql_query('SELECT id, name, gallery_id FROM logikalprojects')
			or die('ERROR: '.mysql_error());
	
	$project_counter = 1;
	?>
	<!-- edit gallery imagel list -->
    <h1>Edit Gallery </h1>
	
	<div id="gallery_form">
	<form action="<?php echo $link ?>" method="post"  enctype="multipart/form-data">
	
	<h3>Gallery Name</h3>
	<input type="text" name="galleryname" id="galleryname" value="<?php echo $gallery[0]; ?>"/>
    
	<h3>Choose Connected Project</h3>
	
		<input type="hidden" id="gallery_id" name="id" value="<?php echo $gallery_id?>" />
		<select id="project" name="project">
			<option value="0">Pick a project  </option>
			<?php 
				while($project = mysql_fetch_row($projects)){
					echo "<option "; if($project[0]==$gallery[1]){ echo "selected "; } echo "value=".$project_counter.">".$project[1]."</option>";
					$project_counter ++;
				}
			?>
		</select>
		</br></br>
		<table id ="gallery-list" class="widefat wp-list-table" cellspacing="0">
			<thead>
				<tr>
					<th style='width: 50px;'>ID</th>
					<th  style='text-align:center; width: 200px;'>Thumbnail</th>
					<th >Image Name</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th style='width: 50px;'>ID</th>
					<th style='text-align:center; width: 200px;'>Thumbnail</th>
					<th>Image Name</th>
				</tr>
			</tfoot>
			<tbody>
			<?php 
			while($row = mysql_fetch_row($project_images)){
			?>
			<tr>
				<td><?php echo $row[0]; ?></td>
				<td align="center"><img class="gallery_thumbnail" src="<?php echo $row[2]; ?>" width="30" height="30"></td>
				<td><?php echo $row[1]; ?></td>
			</tr>
			<?php 
			}
			?>
			</tbody>
		</table>
    
		<ul id="filelist"></ul>
		<br />
 
		<div id="upload_buttons">
			<a id="browse" href="javascript:;">[Add Image...]</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a id="start-upload" href="javascript:;">[Start Upload]</a>
			<div id="gallery_id"></div>
		</div>
		<ul id="filelist"></ul>
		<br />
		<pre id="console"></pre>
		<div class="submitButtons">
			<input type="submit" name="submit" value="Cancel" /><input type="submit" name="submit" value="Delete" /><input type="submit" name="submit" value="Save Changes" />
		</div>
	</form>
	</div>

<script type="text/javascript">

var Gallery_ID = jQuery('#gallery_id').val(); 

var uploader = new plupload.Uploader({
  browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
  url: '/wordpress/wp-content/plugins/logikal-projects/views/upload.php',
  container: document.getElementById('uploader'), // ... or DOM Element itself
  filters : {
        max_file_size : '10mb',
        mime_types: [
            {title : "Image files", extensions : "jpg,gif,png"}
        ]
  },
  multipart_params: {
  		gallery_id: Gallery_ID
  }
});

uploader.init();
 
uploader.bind('FilesAdded', function(up, files) {
  var html = '';
  plupload.each(files, function(file) {
    html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
  });
  document.getElementById('filelist').innerHTML += html;
});
 
 uploader.bind('UploadComplete', function(up,files){
	location.reload();
 });
 
uploader.bind('UploadProgress', function(up, file) {
  document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
});
 
uploader.bind('Error', function(up, err) {
  document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
});
 
document.getElementById('start-upload').onclick = function() {
  uploader.start();
};
 
</script> 
<?php 
} 
?>