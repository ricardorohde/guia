<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class GaleriaModel {

    public $galeria_nome;
    public $galeria_id;
    public $galeria_url;
    public $galeria_categoria_slug;
    public $foto_id;
    public $foto_url;
    public $video_id;
    public $video_titulo;
    public $video_url;
    public $db;
    public $registry;

    public function __construct($registry = null) {
        //conexao com banco
        if ($registry != null) {
            $this->registry = $registry;
            $this->db = $this->registry->get('db');
        } else {
            $this->registry = Registry::getInstance();
            $this->registry->set('db', new DB);
            $this->db = $this->registry->get('db');
        }
    }

    //método destrutor 
    public function __destruct() {
        /*
          $this->db->destroy();
          unset($this->db);
          unset($this->registry);
         */
    }

    //atualiza foto
    public function fotoUpdate() {
        $this->foto_id = Filter:: parse_int(Post :: get('foto_id'));
        Post :: removeIndex('foto_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->foto_id >= 1) {
            //atualiza foto
            $sql = "UPDATE foto SET $post->sql_update WHERE foto_id = $this->foto_id;";
            $this->db->query($sql);
        }
    }

    //remove fotos selecionadas
    public function fotoRemove() {

        if (isset($_POST['lista']) && !empty($_POST['lista'])) {
            foreach ($_POST['lista'] as $foto) {
                //parse_str($post,$_POST['lista']);
                $this->foto_id = $foto['foto_id'];
                $this->foto_url = $foto['foto_url'];
                //verifica se o Id foi informado no post
                if ($this->foto_id >= 1) {
                    //atualiza foto            
                    $sql = "DELETE FROM foto WHERE foto_id = $this->foto_id;";
                    //echo $sql . "\n";
                    $this->db->query($sql);
                    if (file_exists("./midia/foto/$this->foto_url")) {
                        @unlink("./midia/foto/$this->foto_url");
                        // echo "./midia/foto/$this->foto_url\n";
                    }
                }
            }
        }
    }

    public function incluirVideo() {
        //Post :: addIndex('galeria_url', Filter::slug(Post::get('galeria_nome')));
        $this->video_galeria = Filter:: parse_int(Post :: get('video_galeria'));
        $this->video_id = Filter:: parse_int(Post :: get('video_id'));
        Post :: removeIndex('video_id');
        $post = Post::getAllObj();

        $this->video_url = Post :: get('video_url');
        $this->video_mirror = 2; //Youtube
        if (preg_match('/vimeo/', $this->video_url)) {
            $this->video_mirror = 1;
        }
        if (preg_match('/vimeo/', $this->video_url) || preg_match('/youtube/', $this->video_url)) {
            //verifica se o Id foi informado no post
            if ($this->video_id >= 1) {
                //atualiza video
                $sql = "UPDATE video SET $post->sql_update WHERE video_id = $this->video_id;";
            } else {
                //insere novo video
                $sql = "INSERT INTO video (video_titulo, video_url,video_mirror,video_galeria) VALUES ";
                $sql .= "('$this->video_titulo','$this->video_url','$this->video_mirror','$this->video_galeria')";
            }
            $this->result = $this->db->query($sql);
            //redireciona para página de conclusão
            Router::redirect("$this->baseuri/painel/galeria/video/$this->video_galeria/ok-now/");
        } else {
            //redireciona para página de erro (quando não é vimeo ou youtube)
            Router::redirect("$this->baseuri/painel/galeria/video/$this->video_galeria/no-mirror/");
        }
    }

    public function getVideo() {
        $sql = "SELECT * FROM video WHERE video_id = $this->video_id";
        $this->db->query($sql)->fetchAllObj();
        if ($this->db->result) {
            $this->video_id = $this->db->data[0]->{'video_id'};
            $this->video_titulo = $this->db->data[0]->{'video_titulo'};
            $this->video_url = $this->db->data[0]->{'video_url'};
            $this->video_mirror = $this->db->data[0]->{'video_mirror'};
            $this->video_galeria = $this->db->data[0]->{'video_galeria'};
        }
    }

    public function getVideos() {
        $this->db->query = "SELECT * FROM video WHERE video_galeria = $this->galeria_id ORDER BY video_pos ASC";
        $this->db->query()->fetchAllObj();
        if ($this->db->result) {
            $this->numRows = count($this->db->data);
        }
        return $this->db->data;
    }

    public function getVideoThumb() {
        if ($this->video_mirror == 1) {
            //VIDEOS VIMEO
            $link = 'http://vimeo.com/api/v2/video/' . $this->getVideoCodigo() . '.php';
            $html_returned = unserialize(@file_get_contents($link));
            $thumb_url = $html_returned[0]['thumbnail_large'];
            $video_thumb = '<img src="' . $thumb_url . '" class="img-responsive img-thumbnail" style="height:150px;width:220px !important" />';
        } else {
            //VIDEOS YOUTUBE
            $video_thumb = '<img src="//img.youtube.com/vi/' . $this->getVideoCodigo() . '/hqdefault.jpg" class="img-responsive img-thumbnail" style="height:150px; width:220px !important" />';
        }
        return $video_thumb;
    }

    public function getVideoFrame($w = 500, $h = 360) {
        if ($this->video_mirror == 1) {
            //VIDEOS VIMEO
            $video_codigo = explode("/", $this->video_url);
            $codigo = $video_codigo[count($video_codigo) - 1];
            $video_frame = '<iframe src="//player.vimeo.com/video/' . $codigo . '" width="' . $w . '" height="' . $h . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else {
            //VIDEOS YOUTUBE
            $video_codigo = explode("=", $this->video_url);
            $codigo = $video_codigo[1];
            $video_frame = '<iframe width="' . $w . '" height="' . $h . '" src="//www.youtube.com/embed/' . $codigo . '" frameborder="0" allowfullscreen></iframe>';
        }
        return $video_frame;
    }

    public function getVideoCodigo() {
        if ($this->video_mirror == 1) {
            //VIDEOS VIMEO
            $video_codigo = explode("/", $this->video_url);
            $codigo = $video_codigo[count($video_codigo) - 1];
        } else {
            //VIDEOS YOUTUBE
            $video_codigo = explode("=", $this->video_url);
            $codigo = $video_codigo[1];
        }
        return $codigo;
    }

    public function removeVideo() {
        if (!empty($_POST['lista'])) {
            foreach ($_POST['lista'] as $video) {
                $this->video_id = $video['video_id'];
                //verifica se o Id foi informado no post
                if ($this->video_id >= 1) {
                    //remove video
                    $this->db->query = "DELETE FROM video WHERE video_id = $this->video_id";
                    $this->db->query();
                }
            }
        }
    }

    public function atualizarVideo() {
        $sql = "UPDATE video SET ";
        $sql .= "video_titulo = '$this->video_titulo',";
        $sql .= "video_url  = '$this->video_url'";
        $sql .= "WHERE video_id = $this->video_id";
        $this->result = $this->db->query($sql);
    }

    //método para upload multiplo de fotos
    public function upload() {
        //diretorio de destino das fotos
        $diretorio = './midia/foto/';
        $file = $_FILES['file'];
        if (!empty($file['name'])) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                $handle->image_convert = 'jpg';
                if ($handle->image_src_x > 1300 || $handle->image_y > 1100) {
                    $handle->image_resize = true;
                    $handle->image_ratio_x = true;
                    $handle->image_ratio_crop = true;
                    $handle->image_x = 900;
                    //$handle->image_y = 900;
                }
                $handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->Process($diretorio);
                if ($handle->processed) {
                    $this->foto_url = $handle->file_dst_name;
                    $this->galeria_id = Post::get('galeria_id');
                    $sql = "INSERT INTO foto (foto_galeria,foto_url) VALUES ('$this->galeria_id','$this->foto_url');";
                    $this->db->query($sql);
                    echo json_encode(array('status' => 'ok'));
                } else {
                    echo json_encode(array('status' => 'error', 'error' => $handle->error));
                }
            } else {
                echo json_encode(array('status' => 'error', 'error' => $handle->error));
            }
        }
    }

    //método atualiza registro vindo do fomulário galeria_editar do método editar()
    public function atualizar() {
        Post :: addIndex('galeria_url', Filter::slug(Post::get('galeria_nome')));
        $this->galeria_id = Filter:: parse_int(Post :: get('galeria_id'));
        //converte quebra de linha em <br />
        //Post :: changeIndex('galeria_desc', nl2br(Post :: get('galeria_desc')));
        Post :: removeIndex('galeria_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->galeria_id >= 1) {
            //atualiza galeria
            $sql = "UPDATE galeria SET $post->sql_update WHERE galeria_id = $this->galeria_id;";
        } else {
            //insere nova galeria
            $sql = "INSERT INTO galeria $post->sql_insert;";
        }
        $this->db->query($sql);
    }

    //remove galeria via $.post jquery
    public function remover() {
        $this->galeria_id = Filter::parse_int(Post::get('galeria_id'));
        $sql = "DELETE FROM galeria WHERE galeria_id = $this->galeria_id";
        $this->db->query($sql);
    }

    public function getGaleriaJSON() {
        $this->galeria_id = Filter::parse_int(Router::getUri(3));
        $sql = "SELECT * FROM galeria LEFT JOIN gcategoria ON (galeria_gcategoria = gcategoria_id)  WHERE galeria_id = $this->galeria_id";
        $this->db->query($sql)->fetchAllObj();
        $this->db->data[0]->{'galeria_desc'} = $this->db->data[0]->{'galeria_desc'};
        echo json_encode($this->db->data[0]);
    }

    public function getGaleria($galeria_id) {
        $this->galeria_id = Filter::parse_int($galeria_id);
        $sql = "SELECT * FROM galeria LEFT JOIN gcategoria ON (galeria_gcategoria = gcategoria_id)  WHERE galeria_id = $this->galeria_id ";
        $this->db->query($sql)->fetchAllObj();
        if (isset($this->db->data[0])) {
            $this->galeria_nome = $this->db->data[0]->{'galeria_nome'};
            $this->gcategoria_nome = $this->db->data[0]->{'gcategoria_nome'};
            $galeria = $this->db->data[0];
            $galeria->{'fotos'} = $this->getFotos();
            $galeria->{'videos'} = $this->getVideos();
            $galeria->{'categorias'} = $this->getCategorias();
            $this->db->data = $galeria;
            return $this->db->data;
        }
    }

    public function getBySlug() {
        $sql = "SELECT * FROM galeria LEFT JOIN gcategoria ON (galeria_gcategoria = gcategoria_id)  WHERE galeria_url = '$this->galeria_url'";
        $this->db->query($sql)->fetchAllObj();
        if (isset($this->db->data[0])) {
            $this->galeria_nome = $this->db->data[0]->{'galeria_nome'};
            $this->gcategoria_nome = $this->db->data[0]->{'gcategoria_nome'};
            $galeria = $this->db->data[0];
            $this->galeria_id = $galeria->{'galeria_id'};
            $galeria->{'fotos'} = $this->getFotos();
            $galeria->{'videos'} = $this->getVideos();
            $galeria->{'categorias'} = $this->getCategorias();
            $this->db->data = $galeria;
            return $this->db->data;
        }
    }

    public function getCategorias() {
        $sql = "SELECT * FROM gcategoria ORDER BY gcategoria_pos ASC";
        $this->db->query($sql)->fetchAllObj();
        return $this->db->data;
    }

    public function getCategoriasComGaleria() {
        $sql = "SELECT * FROM gcategoria INNER JOIN galeria ON (galeria_gcategoria = gcategoria_id) GROUP BY gcategoria_id ORDER BY gcategoria_pos ASC";
        $this->db->query($sql)->fetchAllObj();
        return $this->db->data;
    }

    public function getAllGaleria() {
        $sql = "SELECT * FROM galeria LEFT JOIN gcategoria ON (galeria_gcategoria = gcategoria_id) ORDER BY galeria_pos ASC";
        $this->db->query($sql)->fetchAllObj();
        return $this->db->data;
    }

    public function getGaleriaByCatSlug() {
        $sql = "SELECT * FROM galeria LEFT JOIN gcategoria ON (galeria_gcategoria = gcategoria_id) WHERE gcategoria_url = '$this->galeria_categoria_slug' ORDER BY galeria_pos ASC";
        $this->db->query($sql)->fetchAllObj();
        return $this->db->data;
    }

    public function getAllGaleriaFotosVideos() {
        $sql = "SELECT * FROM galeria LEFT JOIN gcategoria ON (galeria_gcategoria = gcategoria_id) ORDER BY galeria_pos ASC";
        $this->db->query($sql)->fetchAllObj();
        $galerias = $this->db->data;
        $all = array();
        foreach ($galerias as $galeria) {
            $this->galeria_id = $galeria->galeria_id;
            $galeria->{'fotos'} = $this->getFotos();
            $galeria->{'videos'} = $this->getVideos();
            $all[] = $galeria;
        }
        return $all;
    }

    public function getFotos() {
        $this->db->query = "SELECT * FROM foto WHERE foto_galeria = $this->galeria_id ORDER BY foto_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function getAllFotos() {
        $this->db->query = "SELECT * FROM foto ORDER BY foto_id desc, foto_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function getFotoCapa() {
        $this->db->query = "SELECT * FROM foto WHERE foto_galeria = $this->galeria_id ORDER BY foto_pos ASC LIMIT 0,1";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function UpdateFotosPos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['d'] as $pos => $id) {
                $sql = "UPDATE foto SET foto_pos = '$pos' WHERE foto_id = $id;";
                $this->db->query($sql);
            }
        }
    }

    public function UpdateVideoPos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['d'] as $pos => $id) {
                $sql = "UPDATE video SET video_pos = '$pos' WHERE video_id = $id;";
                $this->db->query($sql);
            }
            echo $sql;
        }
    }

    public function UpdateGalPos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['li'] as $pos => $id) {
                $sql = "UPDATE galeria SET galeria_pos = '$pos' WHERE galeria_id = $id;";
                $this->db->query($sql);
            }
        }
    }

}
