<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class TopicoModel {

    public $topico_id;
    public $topico_url;
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
    //método retorna todas as topicos
    public function getAllCategoria() {
        //seta a query que será utilizada na consulta
        $this->db->query = "SELECT * FROM topico ORDER BY topico_pos, topico_nome ASC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método atualiza registro vindo do fomulário topico_editar do método editar()
    public function atualizar() {
        Post :: addIndex('topico_url', Filter::slug(Post::get('topico_nome')));
        $this->topico_id = Filter:: parse_int(Post :: get('topico_id'));
        Post :: removeIndex('topico_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->topico_id >= 1) {
            //atualiza topico
            $sql = "UPDATE topico SET $post->sql_update WHERE topico_id = $this->topico_id;";
        } else {
            //insere nova topico
            $sql = "INSERT INTO topico $post->sql_insert;";
        }
        $this->db->query($sql);
    }

    //remove topico via $.post jquery
    public function remover() {
        $this->topico_id = Filter::parse_int(Post::get('topico_id'));
        $this->db->query = "DELETE FROM topico WHERE topico_id = $this->topico_id";
        $this->db->query();
    }

    public function getCategoria($topico_id) {
        $this->topico_id = Filter::parse_int($topico_id);
        $this->db->query = "SELECT * FROM topico WHERE topico_id = $this->topico_id ORDER BY topico_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //atualiza posicoes
    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE topico SET topico_pos = '$pos' WHERE topico_id = $id;";
            $this->db->query();
        }
    }

}
