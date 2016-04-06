<?php
require 'helpers/Request.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>404</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
    <center>
        <h2 style="color:#666">Ooops! <Br> Erro 404</h2>
        <img src="assets/images/404.jpg" />
         <h2 style="color:#666">Página não encontrada!</h2>
        <a href="<?= Request::get('return') ?>">Voltar</a>
    </center>
</body>
</html>
