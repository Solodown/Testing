<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-config.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');

global $wpdb;

if (empty($_FILES) || $_FILES["file"]["error"]) {
  die('{"OK": 0}');
}
 
$fileName = $_FILES["file"]["name"];

$gallery_id = $_POST["gallery_id"];

			
//echo $fileName;
move_uploaded_file($_FILES["file"]["tmp_name"], "../project_uploads/$fileName");

$filePath = WP_PLUGIN_URL . '/logikal-projects' . '/project_uploads/'.$fileName;

$iq = "INSERT INTO logikalprojects_images SET filename='$fileName', filepath='$filePath', gallery_id=".$gallery_id;
			if(!mysql_query($iq))
				die("ERROR: ".mysql_error());

die('{"OK": 1}');

?>