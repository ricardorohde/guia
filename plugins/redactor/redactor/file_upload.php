<?php
// arquivo configuracao de diretorio para upload  /config/routes.conf.php
require_once '../../../config/routes.conf.php';	
global $routes;	
$dir = $routes['uploads_dir']."/"; 

 $file = $_FILES['file']['name'];
@copy($_FILES['file']['tmp_name'],$dir.$file);
$array = array(
	'filelink' => $routes["uploads_url"].'/'.$file
	'filename' => $_FILES['file']['name']
);
echo stripslashes(json_encode($array));
	
?>
