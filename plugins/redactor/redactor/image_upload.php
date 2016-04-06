<?php
// arquivo configuracao de diretorio para upload  /config/routes.conf.php
require_once '../../../config/routes.conf.php';	
global $routes;	
$dir = $routes["uploads_dir"]."/"; 
 
$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
 
if ($_FILES['file']['type'] == 'image/png' 
|| $_FILES['file']['type'] == 'image/jpg' 
|| $_FILES['file']['type'] == 'image/gif' 
|| $_FILES['file']['type'] == 'image/jpeg'
|| $_FILES['file']['type'] == 'image/pjpeg')
{	
    //setting file's mysterious name
    //$file = $dir.md5(date('YmdHis')).'.jpg';
    $file = $_FILES['file']['name'];
    // copying
    copy($_FILES['file']['tmp_name'],$dir.$file);
    // displaying file    
	$array = array(
		'filelink' => $routes["uploads_url"].'/'.$file
	);	
	echo stripslashes(json_encode($array));   
    
}
 
?>
