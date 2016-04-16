<?php
@session_start();
require_once('lib/php_image_magician.php'); // editar imagem
require_once('lib/ZimaRegistry.php'); // Objeto que guarda onexão com banco

//require = erro fatal
//include = warning

/*
NÃO POSSO INICIALIZAR ATRIBUTOS DA CLASSE NA SUA DECLARAÇÃO
TEM QUE SER NO CONSTRUTOR
ISSO NÃO PODE: private $myAttribute = "xpto";

NÃO POSSO INICIALIZAR ATRIBUTOS DA CLASSE NA SUA DECLARAÇÃO
TEM QUE SER NO CONSTRUTOR
ISSO NÃO PODE: private $myAttribute = "xpto";

NÃO POSSO INICIALIZAR ATRIBUTOS DA CLASSE NA SUA DECLARAÇÃO
TEM QUE SER NO CONSTRUTOR
ISSO NÃO PODE: private $myAttribute = "xpto";
*/

class ImageUploader {

  private $zimaconn;
  private $ds;
  private $storeFolder;

  public function __construct(){
     $this->ds = DIRECTORY_SEPARATOR;
     $this->storeFolder = 'assets'.$this->ds.'images'.$this->ds.'promo';

    // colocar esse trecho de código em algum lugar que seja executado só uma vez
    /*$conn = new PDO(
      'mysql:host=localhost;port=3306;dbname=guiafacil',
      'root',
      ''
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $zimaRegistry = ZimaRegistry::getInstance();
    $zimaRegistry->set('zimaconnection', $conn);*/
    // colocar esse trecho de código em algum lugar que seja executado só uma vez

    // apenas esse trecho permanece no construtor
    //$zimaRegistry = ZimaRegistry::getInstance();
    //$this->zimaconn = $zimaRegistry->get('zimaconnection');

    $zimaRegistry = ZimaRegistry::getInstance();
    if ($zimaRegistry->get('zimaconnection') == null) {
      $opcoes = array(
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
      );
      $conn = new PDO(
        'mysql:host=localhost;port=3306;dbname=guiafacil',
        'root',
        '',
        $opcoes
      );
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

      $zimaRegistry->set('zimaconnection', $conn);
    }
  }

  // Faz insert na tabela de fotos do produto
  private function insert($fileName, $fileExt, $produtoID) {
      $this->zimaconn->beginTransaction();
      try {
          $stmt = $this->zimaconn->prepare(
              'INSERT INTO z_produto_foto (filename, fileext, z_produto_id) VALUES (:filename, :fileext, :z_produto_id)'
          );

          $stmt->bindValue(':filename', $fileName);
          $stmt->bindValue(':fileext', $fileExt);
          $stmt->bindValue(':z_produto_id', $produtoID);
          $stmt->execute();

          $this->zimaconn->commit();
      }
      catch(Exception $e) {
           echo $e.getMessage();
           $this->zimaconn->rollback();
      }
    }

  public function doImageUpload(){
    echo "<script> console.log(1111); </script>";
    if (!empty($_FILES)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $targetPath = dirname( __FILE__ ) . $this->ds. $this->storeFolder . $this->ds;

    	$md5FileName = md5($_FILES['file']['name']);
    	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

      // salvando em tamanho original
      $targetFile =  $targetPath . $md5FileName . '.'. $ext; //5
      move_uploaded_file($tempFile, $targetFile); //6
      $this->insert($md5FileName, $ext, 2); //Farmácia e Perfumaria

    	// salvando em 400x300
    	$newImage = new imageLib($targetFile);
    	$newImage->resizeImage(400,300);
    	$md5FileName = $md5FileName . '_400_300';
    	$targetFile = $targetPath . $md5FileName. '.' . $ext;
    	$newImage->saveImage($targetFile); // salva na pasta
    	$this->insert($md5FileName, $ext, 2); // salva no banco Farmácia e Perfumaria
    }

  }

  // Retorna as imagens vinculadas ao produto (da Promoção) com ID informado
  public function getImagesByProduto($produtoID = 0){
    // se não veio $produtoID passado por parâmetro no método então eu busco no $_POST (pode ser um AJAX)
    //!isset($produtoID) ? isset($_POST['produtoID']) ? $produtoID = $_POST['produtoID'] : 0;

    $sql = "SELECT id, titulo, filename, fileext FROM z_produto_foto WHERE z_produto_id = :z_produto_id";

    $stmt = $this->zimaconn->prepare($sql);
    $stmt->bindValue(':z_produto_id', $produtoID);

    $stmt->execute();
    $images = array();

    if ($stmt) {
      while($row = $stmt->fetch(PDO::FETCH_OBJ)){
        $images[] = array(
          'filename' => $row->filename,
          'fileext' => $row->fileext,
          'titulo' => $row->titulo,
          'id' => $row->id
        );
      }
    }
    return $images;
  }

  // Parâmetros: ID da imagem no banco e o nome do arquivo na pasta de upload vem por AJAX/POST
  public function deleteImage(){
    if (isset($_POST['fotoID']) && isset($_POST['fileName'])){
      $fotoID = $_POST['fotoID'];
      $fileNameExt = $_POST['fileName'] . "." . $_POST['fileExt'];

      // se o arquivo existir na pasta, removo da pasta e do banco
      if (is_file($this->storeFolder.$this->ds.$fileNameExt)){
        try {

          // removendo do banco
          $sql = "DELETE FROM z_produto_foto WHERE id = :id";
          $stmt = $this->zimaconn->prepare($sql);
          $stmt->bindValue(':id', $fotoID);
          $stmt->execute();

          // removendo da pasta assets/images/promo
          unlink($this->storeFolder.$this->ds.$fileNameExt);
        } catch (Exception $e) {
          echo $e.getMessage();
        }
      }
    } else {
      header("HTTP/1.0 403 Forbidden");
    }
  }

  public function updateTitleImage(){
    $fotoID = isset($_POST['fotoID']) ? $_POST['fotoID'] : 0;
    $title = isset($_POST['titulo']) ? $_POST['titulo'] : 'sem_titulo';

    $sql = 'UPDATE z_produto_foto SET titulo = :titulo WHERE id = :id';

    $stmt = $this->zimaconn->prepare($sql);
    $stmt->bindValue(':titulo', $title);
    $stmt->bindValue(':id', $fotoID);

    $deletedRows = $stmt->execute();
    return $deletedRows > 0;
  }
}
?>
