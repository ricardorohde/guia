<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class ServicoModel {

    public $servico_id;
    public $servico_titulo;
    public $servico_texto;
    public $servico_url;
    public $servico_meta_keywords;
    public $servico_meta_desc;
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

    //seta o título do serviço
    public function setTitulo($titulo) {
        $this->servico_titulo = $titulo;
    }

    //seta o texto do serviço
    public function setTexto($texto) {
        $this->servico_texto = $texto;
    }

    //seta o texto do serviço
    public function setUrl($url) {
        $this->servico_url = $url;
    }

    //seta o ID  do serviço
    public function setId($id) {
        $this->servico_id = $id;
    }

    //seta a foto do serviço
    public function setFoto($foto) {
        $this->servico_capa = $foto;
    }

    //seta a meta desc
    public function setMetaDesc($desc) {
        $this->servico_meta_desc = $desc;
    }

    //seta a meta key
    public function setMetaKeywords($key) {
        $this->servico_meta_keywords = $key;
    }

    //recupera o título do serviço
    public function getTitulo() {
        return stripslashes($this->servico_titulo);
    }
    
    //recupera a meta desc
    public function getMetaDesc() {
        return $this->servico_meta_desc;
    }

    //recupera a meta key
    public function getMetaKeywords() {
        return $this->servico_meta_keywords;
    }
    
    //recupera o texto do serviço
    public function getTexto() {
        return stripslashes($this->servico_texto);
    }

    //recupera o url do serviço
    public function getUrl() {
        return $this->servico_texto;
    }

    //recupera ID do serviço
    public function getId() {
        return $this->servico_id;
    }

    //recupera foto capa do serviço
    public function getFoto() {
        return $this->servico_capa;
    }
    
    

    //método recuperar servico by id
    public function getServico() {
        $this->db->query = "SELECT * FROM servico WHERE servico_id = $this->servico_id";
        $this->db->query()->fetchAllObj();
        $this->setTexto($this->db->data[0]->{'servico_texto'});
        $this->setTitulo($this->db->data[0]->{'servico_titulo'});
        $this->setUrl($this->db->data[0]->{'servico_url'});
        $this->setId($this->db->data[0]->{'servico_id'});
        $this->setFoto($this->db->data[0]->{'servico_capa'});
        $this->setMetaDesc($this->db->data[0]->{'servico_meta_desc'});
        $this->setMetaKeywords($this->db->data[0]->{'servico_meta_keywords'});        
        $this->servico_click_uniq = $this->db->data[0]->{'servico_click_uniq'};
        return $this;
    }

    //método recuperar servico by slug
    public function getServicoBySlug() {
        $this->db->query = "SELECT * FROM servico WHERE servico_url = '$this->servico_url'";
        $this->db->query()->fetchAllObj();
        $this->setTexto($this->db->data[0]->{'servico_texto'});
        $this->setTitulo($this->db->data[0]->{'servico_titulo'});
        $this->setUrl($this->db->data[0]->{'servico_url'});
        $this->setId($this->db->data[0]->{'servico_id'});
        $this->setFoto($this->db->data[0]->{'servico_capa'});
        $this->setMetaDesc($this->db->data[0]->{'servico_meta_desc'});
        $this->setMetaKeywords($this->db->data[0]->{'servico_meta_keywords'});
        return $this;
    }

    //método recuperar serviço
    public function getAllServico() {
        $this->db->query = "SELECT servico_click_uniq,servico_id,servico_titulo,servico_url,servico_pos,servico_capa,servico_texto FROM servico ORDER BY servico_pos ASC, servico_id DESC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método recuperar menu serviço
    public function get2Menu($nums = 10) {
        $this->db->paginate($nums);
        $this->db->query = "SELECT servico_id,servico_titulo,servico_url,servico_pos FROM servico ORDER BY servico_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método inclui registro vindo do fomulário servico_nova do método novo()
    public function incluir() {
        $this->upload();
        if ($this->servico_capa) {
            Post:: addIndex('servico_capa', $this->servico_capa);
        }
        Post::addIndex('servico_url', Filter::slug(Post::get('servico_titulo')));
        $post = Post::getAllObj();
        $sql = "INSERT INTO servico $post->sql_insert;";
        $this->db->query($sql);
    }

    //método atualiza registro vindo do fomulário servico_editar do método editar()
    public function atualizar() {
        $this->servico_id = Post :: getAndRemoveIndex('servico_id');
        $this->upload();
        if ($this->servico_capa) {
            $this->removerCapa();
            Post:: addIndex('servico_capa', $this->servico_capa);
        }
        Post::changeIndex('servico_texto', addslashes(Post::get('servico_texto')));
        Post::addIndex('servico_url', Filter::slug(Post::get('servico_titulo')));
        $post = Post::getAllObj();
        $sql = "UPDATE servico SET $post->sql_update WHERE servico_id = $this->servico_id;";
        $this->db->query($sql);
    }

    //remove servico via $.post jquery
    public function remover() {
        $this->servico_id = Filter::parse_int(Post::get('servico_id'));
        $this->db->query = "DELETE FROM servico WHERE servico_id = $this->servico_id";
        $this->db->query();
    }

    //método upload do servico
    public function upload() {
        $dir_dest = 'midia/servico/';
        $file = $_FILES['filedata'];
        if (!empty($file['name'])) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                $handle->image_convert = 'png';
                /*
                  if ($handle->image_src_x > 150 || $handle->image_y > 150) {
                  $handle->image_resize = true;
                  $handle->image_ratio_crop = true;
                  $handle->image_x = 150;
                  $handle->image_y = 150;
                  }
                 */
                $handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->Process($dir_dest);
                if ($handle->processed) {
                    $this->servico_capa = $handle->file_dst_name;
                } else {
                    $this->servico_capa = false;
                }
            }
        } else {
            $this->servico_capa = false;
        }
        Post::removeIndex('filedata');
    }

    //remove foto capa da servico
    public function removerCapa() {
        $this->db->query = "SELECT * FROM servico WHERE servico_id = $this->servico_id";
        $this->db->query()->fetchAll();
        if (isset($this->db->data[0]['servico_capa'])) {
            $remove = $this->db->data[0]['servico_capa'];
            if (file_exists("./midia/servico/$remove")) {
                @unlink("./midia/servico/$remove");
            }
        }
    }

    //atualiza posicoes de listagem (drag) dos servicos
    public function UpdatePos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['li'] as $pos => $id) {
                $this->db->query = "UPDATE servico SET servico_pos = '$pos' WHERE servico_id = $id;";
                $this->db->query();
            }
        }
    }

}
