<?php

require_once( 'canvas.php' );
require_once '../../../config/routes.conf.php';	
global $routes;	
$dir = $routes["uploads_dir"];
$pic = $dir."/".$_GET['img'];
$t = new Canvas;
$t->carrega( $pic );
$t->redimensiona( 60, 60, 'crop' );
$t->grava();
/*end file*/
