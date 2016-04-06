<?php
global $routes;
$routes = array();

/** 
* alteracao obrigatoria
* subdiretorio do site ex: site, loja,  
* Se estiver na raiz do servidor deixe vazio ex: $routes['site_dir'] = "";
*/
$routes['site_dir'] = "/seta"; 

/** 
* alteracao opcional
* diretorio onde os arquivos de imagem do editor serão gravadas (apenas o nome)
* dê permissão de gravacao neste diretorio ex: chmod 777 - caso contrario as fotos nao serao upadas
*/
$routes['images_dir'] = "midia/editor";

/** 
* não alterar 
*/
$routes['uploads_dir'] = $_SERVER['DOCUMENT_ROOT'].$routes['site_dir']."/".$routes['images_dir'];
$routes['uploads_url'] = "//".$_SERVER['HTTP_HOST'].$routes['site_dir']."/".$routes['images_dir'];



