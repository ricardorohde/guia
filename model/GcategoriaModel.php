<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class GcategoriaModel {

    public $gcategoria_id;
    public $gcategoria_url;
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

    //método retorna todas as gcategorias
    public function getAllCategoria() {
        //seta a query que será utilizada na consulta
        $this->db->query = "SELECT * FROM gcategoria ORDER BY gcategoria_pos, gcategoria_nome ASC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método atualiza registro vindo do fomulário gcategoria_editar do método editar()
    public function atualizar() {
        Post :: addIndex('gcategoria_url', Filter::slug(Post::get('gcategoria_nome')));
        $this->gcategoria_id = Filter:: parse_int(Post :: get('gcategoria_id'));
        Post :: removeIndex('gcategoria_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->gcategoria_id >= 1) {
            //atualiza gcategoria
            $sql = "UPDATE gcategoria SET $post->sql_update WHERE gcategoria_id = $this->gcategoria_id;";
        } else {
            //insere nova gcategoria
            $sql = "INSERT INTO gcategoria $post->sql_insert;";
        }
        $this->db->query($sql);
    }

    //remove gcategoria via $.post jquery
    public function remover() {
        $this->gcategoria_id = Filter::parse_int(Post::get('gcategoria_id'));
        $this->db->query = "DELETE FROM gcategoria WHERE gcategoria_id = $this->gcategoria_id";
        $this->db->query();
    }

    public function getCategoria($gcategoria_id) {
        $this->gcategoria_id = Filter::parse_int($gcategoria_id);
        $this->db->query = "SELECT * FROM gcategoria WHERE gcategoria_id = $this->gcategoria_id ORDER BY gcategoria_pos ASC";
        $this->db->query()->fetchAllObj();
        //$gcategoria = $this->db->data;
        //$produtos = $this->getProdutos();
        //$this->db->data = $gcategoria;
        //$this->db->data['produto'] = $produtos;
        return $this->db->data;
    }

    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE gcategoria SET gcategoria_pos = '$pos' WHERE gcategoria_id = $id;";
            $this->db->query();
        }
    }

}
