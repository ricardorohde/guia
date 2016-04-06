<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class CategoriaModel {

    public $categoria_id;
    public $categoria_url;
    public $foto_id;
    public $foto_url;
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

    //método retorna todas as categorias
    public function getAllCategoria() {
        //seta a query que será utilizada na consulta
        $this->db->query = "SELECT * FROM categoria ORDER BY categoria_pos, categoria_nome ASC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método atualiza registro vindo do fomulário categoria_editar do método editar()
    public function atualizar() {
        Post :: addIndex('categoria_url', Filter::slug(Post::get('categoria_nome')));
        $this->categoria_id = Filter:: parse_int(Post :: get('categoria_id'));
        Post :: removeIndex('categoria_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->categoria_id >= 1) {
            //atualiza categoria
            $sql = "UPDATE categoria SET $post->sql_update WHERE categoria_id = $this->categoria_id;";
        } else {
            //insere nova categoria
            $sql = "INSERT INTO categoria $post->sql_insert;";
        }
        $this->db->query($sql);
    }

    //remove categoria via $.post jquery
    public function remover() {
        $this->categoria_id = Filter::parse_int(Post::get('categoria_id'));
        $this->db->query = "DELETE FROM categoria WHERE categoria_id = $this->categoria_id";
        $this->db->query();
    }

    public function getCategoria($categoria_id) {
        $this->categoria_id = Filter::parse_int($categoria_id);
        $this->db->query = "SELECT * FROM categoria WHERE categoria_id = $this->categoria_id ORDER BY categoria_pos ASC";
        $this->db->query()->fetchAllObj();
        //$categoria = $this->db->data;
        //$produtos = $this->getProdutos();
        //$this->db->data = $categoria;
        //$this->db->data['produto'] = $produtos;
        return $this->db->data;
    }

    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE categoria SET categoria_pos = '$pos' WHERE categoria_id = $id;";
            $this->db->query();
        }
    }

}
