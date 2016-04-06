<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class AreaModel {

    public $area_id;
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

    //método destrutor encerra DB
    public function __destruct() {
        /*
          $this->db->destroy();
          unset($this->db);
          unset($this->registry);
         */
    }

    //método atualiza registro vindo do fomulário area_editar do método editar()
    public function atualizar() {
        Post::addIndex('area_url', Filter::slug(Post::get('area_nome')));
        $this->area_id = Filter:: parse_int(Post :: getAndRemoveIndex('area_id'));
        $this->area_show = Post :: getAndRemoveIndex('area_show');
        //verifica se o Id foi informado no post
        if ($this->area_id >= 1) {
            Post:: removeIndex('area_show', 0);
            $post = Post::getAllObj();
            //atualiza area
            $this->db->query = "UPDATE area SET $post->sql_update WHERE area_id = $this->area_id;";
        } else {
            Post:: addIndex('area_show', 1);
            $post = Post::getAllObj();
            //insere nova area
            $this->db->query = "INSERT INTO area $post->sql_insert;";
        }
        $this->db->query();
    }

    //remove area via $.post jquery
    public function remover() {
        $this->area_id = Filter::parse_int(Post::get('area_id'));
        $this->db->query = "DELETE FROM area WHERE area_id = $this->area_id";
        $this->db->query();
    }

    public function getById($area_id) {
        $this->area_id = Filter::parse_int($area_id);
        $this->db->query = "SELECT * FROM area WHERE area_id = $this->area_id";
        $this->db->query()->fetchAllObj();
        return $this->db->data[0];
    }

    //método atualiza exibicao sim/nao da area no menu do site
    public function exibicao() {
        $this->area_id = Filter:: parse_int(Post :: getAndRemoveIndex('area_id'));
        $this->area_show = Filter:: parse_int(Post :: getAndRemoveIndex('area_show'));
        Post:: addIndex('area_show', $this->area_show);
        $post = Post::getAllObj();
        $this->db->query = "UPDATE area SET $post->sql_update WHERE area_id = $this->area_id;";
        $this->db->query();
        echo $this->area_show;
    }

    //método atualiza exibicao sim/nao da area no rodape do site
    public function exibicao_footer() {
        $this->area_id = Filter:: parse_int(Post :: getAndRemoveIndex('area_id'));
        $this->area_footer = Filter:: parse_int(Post :: getAndRemoveIndex('area_footer'));
        Post:: addIndex('area_footer', $this->area_footer);
        $post = Post::getAllObj();
        $this->db->query = "UPDATE area SET $post->sql_update WHERE area_id = $this->area_id;";
        $this->db->query();
        echo $this->area_footer;
    }

    //atualiza posicoes
    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE area SET area_pos = '$pos' WHERE area_id = $id;";
            $this->db->query();
        }
    }

}
