<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class DepoimentoModel {

    public $cliente_id;
    public $cliente_logo = false;
    public $depoimento_id;
    public $depoimento_status = 1;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
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

    //método recupera dados do cliente status = 1
    public function getAll() {
        $this->db->query = "SELECT * FROM depoimento INNER JOIN cliente ON (depoimento_cliente = cliente_id) WHERE depoimento_status = $this->depoimento_status ORDER BY depoimento_id DESC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'depoimento_cliente'})) {
            return $this->db->data;
        }
    }

    //método recupera os clientes  
    public function getClientes() {
        $this->db->query = "SELECT * FROM cliente ORDER BY cliente_nome ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        }
    }

    //método inclui ou atualiza depoimento
    public function atualizar() {
        $this->depoimento_id = Filter:: parse_int(Post:: get('depoimento_id'));
        $this->depoimento_cliente = Filter:: parse_int(Post:: get('depoimento_cliente'));
        $this->depoimento_autor = Filter:: parse_string(Post:: get('depoimento_autor'));
        $this->depoimento_cargo = Filter:: parse_string(Post:: get('depoimento_cargo'));
        $this->depoimento_texto = Filter:: parse_string(Post:: get('depoimento_texto'));
        $this->depoimento_status = Filter:: parse_int(Post:: get('depoimento_status'));
        //faz upload da foto
        if (!empty($_FILES['filedata']['name'])) {
            $dir_dest = 'midia/cliente/';
            $file = $_FILES['filedata'];
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                //$handle->image_convert = 'jpg';
                $handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->Process($dir_dest);
                if ($handle->processed) {
                    $this->depoimento_foto = $handle->file_dst_name;
                } else {
                    $this->depoimento_foto = false;
                }
                Post::addIndex('depoimento_foto', $this->depoimento_foto);
            }
        }
        if (!$this->depoimento_foto) {
            Post::removeIndex('depoimento_foto');
        }

        if ($this->depoimento_id <= 0) {
            Post::removeIndex('depoimento_id');
            $post = Post:: getAllObj();
            $sql = "INSERT INTO depoimento $post->sql_insert;";
        } else {
            Post::removeIndex('depoimento_id');
            $post = Post:: getAllObj();
            $sql = "UPDATE depoimento SET $post->sql_update WHERE depoimento_id = $this->depoimento_id;";
        }
        $this->db->query($sql);
    }

    //remove cliente via $.post jquery
    public function remover() {
        $this->depoimento_id = Filter::parse_int(Post::get('depoimento_id'));
        $this->db->query = "DELETE FROM depoimento WHERE depoimento_id = $this->depoimento_id";
        $this->db->query();
    }

}
