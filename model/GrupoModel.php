<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class GrupoModel {

    public $grupo_id;
    public $grupo_url;
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

    //método retorna todas as grupos
    public function getAllCategoria() {
        //seta a query que será utilizada na consulta
        /*$this->db->query = "SELECT * FROM grupo ORDER BY grupo_nome ASC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;*/

        $sql = "SELECT * FROM grupo ORDER BY grupo_nome ASC";
        $this->db->query($sql)->fetchAll();

        foreach($this->db->data as $k => $v){
            //$this->db->data[$k]->{'client_lat'} = $this->db->data[$k]->{'client_lat_lon'};
            $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
            $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
            $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
            $this->db->data[$k]['cliente_nome'] = utf8_encode($this->db->data[$k]['cliente_nome']);
            $this->db->data[$k]['cliente_empresa'] = utf8_encode($this->db->data[$k]['cliente_empresa']);
            unset($this->db->data[$k]['cliente_lat_lon']);
        }

        //return $this->db->data;
    }

    //método atualiza registro vindo do fomulário grupo_editar do método editar()
    public function atualizar() {
        Post :: addIndex('grupo_url', Filter::slug(Post::get('grupo_nome')));
        $this->grupo_id = Filter:: parse_int(Post :: get('grupo_id'));
        Post :: removeIndex('grupo_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->grupo_id >= 1) {
            //atualiza grupo
            $sql = "UPDATE grupo SET $post->sql_update WHERE grupo_id = $this->grupo_id;";
        } else {
            //insere nova grupo
            $sql = "INSERT INTO grupo $post->sql_insert;";
        }
        $this->db->query($sql);
    }

    //remove grupo via $.post jquery
    public function remover() {
        $this->grupo_id = Filter::parse_int(Post::get('grupo_id'));
        $this->db->query = "DELETE FROM grupo WHERE grupo_id = $this->grupo_id";
        $this->db->query();
    }

    public function getCategoria($grupo_id) {
        $this->grupo_id = Filter::parse_int($grupo_id);
        $this->db->query = "SELECT * FROM grupo WHERE grupo_id = $this->grupo_id ORDER BY grupo_nome ASC";
        $this->db->query()->fetchAllObj();
        //$grupo = $this->db->data;
        //$produtos = $this->getProdutos();
        //$this->db->data = $grupo;
        //$this->db->data['produto'] = $produtos;
        return $this->db->data;
    }

    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE grupo SET grupo_pos = '$pos' WHERE grupo_id = $id;";
            $this->db->query();
        }
    }

}
