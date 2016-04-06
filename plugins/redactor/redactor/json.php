<?php

require_once '../../../config/routes.conf.php';	
global $routes;	
$dir = $routes["uploads_dir"];
$url = $routes["uploads_url"];

$json = "[\n";
# Extensoes permitidas
$exts = array('jpg','png','jpeg','gif','bmp');
if (is_dir($dir)) {
	if ($d = opendir($dir))
	{
		while (($file = readdir($d))!== false)
		{
			if (filetype($dir.'/'.$file) == 'file')
			{
				# recupera a extensao do arquivo
				$extensao = explode(".", $file); 
				for($i=0; $i<=count($exts)-1; $i++)
				{
					if($extensao[1] == $exts[$i])
					{
						$json .=" { \"thumb\": \"plugins/redactor/redactor/thumb.php?img={$file}\", \"image\": \"{$url}/{$file}\" },\n";
					}
				}
			}	
		}
		closedir($d);
	}
}
$json = substr($json, 0,-2);
$json .= " ]";
echo $json;

?>
