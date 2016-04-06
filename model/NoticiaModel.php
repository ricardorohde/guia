<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class NoticiaModel {

    public $noticia_id;
    public $noticia_titulo;
    public $noticia_texto;
    public $noticia_url;
    public $noticia_ncategoria;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
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

    //seta o título da notícia
    public function setTitulo($titulo) {
        $this->noticia_titulo = $titulo;
    }

    //seta o texto da notícia
    public function setTexto($texto) {
        $this->noticia_texto = $texto;
    }

    //seta o texto da notícia
    public function setUrl($url) {
        $this->noticia_url = $url;
    }

    //seta a categoria da notícia
    public function setCategoria($cat) {
        $this->noticia_ncategoria = $cat;
    }

    //seta a url da categoria da notícia
    public function setCategoriaUrl($catUrl) {
        $this->ncategoria_url = $catUrl;
    }

    //seta a titulo da categoria da notícia
    public function setCategoriaTitulo($catT) {
        $this->ncategoria_titulo = $catT;
    }

    //seta o ID  da notícia
    public function setId($id) {
        $this->noticia_id = $id;
    }

    //seta a foto da notícia
    public function setFoto($foto) {
        $this->noticia_capa = $foto;
    }

    //seta a data da notícia
    public function setData($data) {
        $this->noticia_data = $data;
    }

    //recupera o título da notícia
    public function getTitulo() {
        return stripslashes($this->noticia_titulo);
    }

    //recupera o texto da notícia
    public function getTexto() {
        return stripslashes($this->noticia_texto);
    }

    //recupera o url da notícia
    public function getUrl() {
        return $this->noticia_texto;
    }

    //recupera a categoria da notícia
    public function getCategoria() {
        return $this->noticia_ncategoria;
    }

    //recupera a url da categoria da notícia
    public function getCategoriaUrl() {
        return $this->ncategoria_url;
    }

    //recupera a titulo da categoria da notícia
    public function getCategoriaTitulo() {
        return $this->ncategoria_titulo;
    }

    //recupera ID da notícia
    public function getId() {
        return $this->noticia_id;
    }

    //recupera foto capa da notícia
    public function getFoto() {
        return $this->noticia_capa;
    }

    //recupera data da notícia
    public function getData() {
        return $this->noticia_data;
    }

    //método recuperar página
    public function getNoticia() {
        $this->db->query = "SELECT * FROM noticia LEFT JOIN ncategoria ON (noticia_ncategoria = ncategoria_id) WHERE noticia_id = $this->noticia_id";
        $this->db->query()->fetchAllObj();
        if ($this->db->numRows() >= 1) {
            $this->setTexto($this->db->data[0]->{'noticia_texto'});
            $this->setTitulo($this->db->data[0]->{'noticia_titulo'});
            $this->setUrl($this->db->data[0]->{'noticia_url'});
            $this->setCategoria($this->db->data[0]->{'noticia_ncategoria'});
            $this->setCategoriaUrl($this->db->data[0]->{'ncategoria_url'});
            $this->setCategoriaTitulo($this->db->data[0]->{'ncategoria_nome'});
            $this->setId($this->db->data[0]->{'noticia_id'});
            $this->setFoto($this->db->data[0]->{'noticia_capa'});
            $this->setData($this->db->data[0]->{'noticia_data'});
            $this->noticia_meta_desc = $this->db->data[0]->{'noticia_meta_desc'};
            $this->noticia_meta_keywords = $this->db->data[0]->{'noticia_meta_keywords'};
            return $this;
        } else {
            return false;
        }
    }

    //método recuperar noticia
    public function getNoticiaBySlug() {
        $this->db->query = "SELECT * FROM noticia LEFT JOIN ncategoria ON (noticia_ncategoria = ncategoria_id) WHERE noticia_url = '$this->noticia_url'";
        $this->db->query()->fetchAllObj();
        if ($this->db->numRows() >= 1) {
            $this->setTexto($this->db->data[0]->{'noticia_texto'});
            $this->setTitulo($this->db->data[0]->{'noticia_titulo'});
            $this->setUrl($this->db->data[0]->{'noticia_url'});
            $this->setCategoria($this->db->data[0]->{'noticia_ncategoria'});
            $this->setCategoriaUrl($this->db->data[0]->{'ncategoria_url'});
            $this->setCategoriaTitulo($this->db->data[0]->{'ncategoria_nome'});
            $this->setId($this->db->data[0]->{'noticia_id'});
            $this->setFoto($this->db->data[0]->{'noticia_capa'});
            $this->setData($this->db->data[0]->{'noticia_data'});
            $this->noticia_meta_desc = $this->db->data[0]->{'noticia_meta_desc'};
            $this->noticia_meta_keywords = $this->db->data[0]->{'noticia_meta_keywords'};
            return $this;
        } else {
            return false;
        }
    }

    //método recuperar notícia
    public function getAllNoticia() {
        $this->db->query = "SELECT * FROM noticia LEFT JOIN ncategoria ON (noticia_ncategoria = ncategoria_id) ORDER BY noticia_id DESC, noticia_titulo ASC";
        $this->db->query()->fetchAllObj();
        if ($this->db->numRows() >= 1) {
            return $this->db->data;
        } else {
            return false;
        }
    }

    //método inclui registro vindo do fomulário noticia_nova do método nova()
    public function incluir() {
        $this->upload();
        if ($this->noticia_capa) {
            Post:: addIndex('noticia_capa', $this->noticia_capa);
        }
        Post::addIndex('noticia_data', date('d/m/Y H:i'));
        Post::addIndex('noticia_url', Filter::slug(Post::get('noticia_titulo')));
        $post = Post::getAllObj();
        $sql = "INSERT INTO noticia $post->sql_insert;";
        $this->db->query($sql);
    }

    //método atualiza registro vindo do fomulário noticia_editar do método editar()
    public function atualizar() {
        $this->noticia_id = Post :: getAndRemoveIndex('noticia_id');
        $this->upload();
        if ($this->noticia_capa) {
            $this->removerCapa();
            Post:: addIndex('noticia_capa', $this->noticia_capa);
        }
        Post::addIndex('noticia_url', Filter::slug(Post::get('noticia_titulo')));
        $post = Post::getAllObj();
        $sql = "UPDATE noticia SET $post->sql_update WHERE noticia_id = $this->noticia_id;";
        $this->db->query($sql);
    }

    //remove noticia via $.post jquery
    public function remover() {
        $this->noticia_id = Filter::parse_int(Post::get('noticia_id'));
        $this->db->query = "DELETE FROM noticia WHERE noticia_id = $this->noticia_id";
        $this->db->query();
    }

    public function getCategorias() {
        $this->db->query = "SELECT * FROM ncategoria order by ncategoria_pos asc, ncategoria_nome asc";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método upload do noticia
    public function upload() {
        $dir_dest = 'midia/news/';
        $file = $_FILES['filedata'];
        if (!empty($file['name'])) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                $handle->image_convert = 'jpg';
                /*
                  if ($handle->image_src_x > 1300 || $handle->image_y > 1100) {
                  $handle->image_resize = true;
                  $handle->image_ratio_crop = true;
                  $handle->image_x = 1000;
                  $handle->image_y = 900;
                  }
                 */
                $handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->Process($dir_dest);
                if ($handle->processed) {
                    $this->noticia_capa = $handle->file_dst_name;
                } else {
                    $this->noticia_capa = false;
                }
            }
        } else {
            $this->noticia_capa = false;
        }
        Post::removeIndex('filedata');
    }

    //remove foto capa da noticia
    public function removerCapa() {
        $this->db->query = "SELECT * FROM noticia WHERE noticia_id = $this->noticia_id";
        $this->db->query()->fetchAll();
        if (isset($this->db->data[0]['noticia_capa'])) {
            $remove = $this->db->data[0]['noticia_capa'];
            if (file_exists("./midia/news/$remove")) {
                @unlink("./midia/news/$remove");
            }
        }
    }

}
