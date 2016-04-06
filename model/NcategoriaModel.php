<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class NcategoriaModel {

    public $ncategoria_id;
    public $ncategoria_url;
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

    //método retorna todas as ncategorias
    public function getAllCategoria() {
        //seta a query que será utilizada na consulta
        $this->db->query = "SELECT * FROM ncategoria ORDER BY ncategoria_pos, ncategoria_nome ASC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método atualiza registro vindo do fomulário ncategoria_editar do método editar()
    public function atualizar() {
        Post :: addIndex('ncategoria_url', Filter::slug(Post::get('ncategoria_nome')));
        $this->ncategoria_id = Filter:: parse_int(Post :: get('ncategoria_id'));
        Post :: removeIndex('ncategoria_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->ncategoria_id >= 1) {
            //atualiza ncategoria
            $sql = "UPDATE ncategoria SET $post->sql_update WHERE ncategoria_id = $this->ncategoria_id;";
        } else {
            //insere nova ncategoria
            $sql = "INSERT INTO ncategoria $post->sql_insert;";
        }
        $this->db->query($sql);
    }

    //remove ncategoria via $.post jquery
    public function remover() {
        $this->ncategoria_id = Filter::parse_int(Post::get('ncategoria_id'));
        $this->db->query = "DELETE FROM ncategoria WHERE ncategoria_id = $this->ncategoria_id";
        $this->db->query();
    }

    public function getCategoria($ncategoria_id) {
        $this->ncategoria_id = Filter::parse_int($ncategoria_id);
        $this->db->query = "SELECT * FROM ncategoria WHERE ncategoria_id = $this->ncategoria_id ORDER BY ncategoria_pos ASC";
        $this->db->query()->fetchAllObj();
        //$ncategoria = $this->db->data;
        //$produtos = $this->getProdutos();
        //$this->db->data = $ncategoria;
        //$this->db->data['produto'] = $produtos;
        return $this->db->data;
    }

    //atualiza posicoes
    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE ncategoria SET ncategoria_pos = '$pos' WHERE ncategoria_id = $id;";
            $this->db->query();
        }
    }

}
