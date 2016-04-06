<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class FaqModel {

    public $faq_id;
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

    //método atualiza registro vindo do fomulário faq_editar do método editar()
    public function atualizar() {
        Post::addIndex('faq_url', Filter::slug(Post::get('faq_pergunta')));
        $this->faq_id = Filter:: parse_int(Post :: getAndRemoveIndex('faq_id'));
        $this->faq_show = Post :: getAndRemoveIndex('faq_show');
        //verifica se o Id foi informado no post
        if ($this->faq_id >= 1) {
            Post:: removeIndex('faq_show', 0);
            $post = Post::getAllObj();
            //atualiza faq
            $this->db->query = "UPDATE faq SET $post->sql_update WHERE faq_id = $this->faq_id;";
        } else {
            Post:: addIndex('faq_show', 1);
            $post = Post::getAllObj();
            //insere nova faq
            $this->db->query = "INSERT INTO faq $post->sql_insert;";
        }
        $this->db->query();
    }

    //remove faq via $.post jquery
    public function remover() {
        $this->faq_id = Filter::parse_int(Post::get('faq_id'));
        $this->db->query = "DELETE FROM faq WHERE faq_id = $this->faq_id";
        $this->db->query();
    }

    public function getById() {
        //$this->faq_id = Filter::parse_int($faq_id);
        $this->db->query = "SELECT * FROM faq WHERE faq_id = $this->faq_id";
        $this->db->query()->fetchAllObj();
        return $this->db->data[0];
    }

    public function getBySlug() {
        $this->db->query = "SELECT * FROM faq WHERE faq_url = '$this->faq_url'";
        $this->db->query()->fetchAllObj();
        return $this->db->data[0];
    }

    public function getAll() {
        $this->db->query = "SELECT * FROM faq ORDER BY faq_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método atualiza exibicao sim/nao da faq no menu do site
    public function exibicao() {
        $this->faq_id = Filter:: parse_int(Post :: getAndRemoveIndex('faq_id'));
        $this->faq_show = Filter:: parse_int(Post :: getAndRemoveIndex('faq_show'));
        Post:: addIndex('faq_show', $this->faq_show);
        $post = Post::getAllObj();
        $this->db->query = "UPDATE faq SET $post->sql_update WHERE faq_id = $this->faq_id;";
        $this->db->query();
        echo $this->faq_show;
    }

    //método atualiza exibicao sim/nao da faq no rodape do site
    public function exibicao_footer() {
        $this->faq_id = Filter:: parse_int(Post :: getAndRemoveIndex('faq_id'));
        $this->faq_footer = Filter:: parse_int(Post :: getAndRemoveIndex('faq_footer'));
        Post:: addIndex('faq_footer', $this->faq_footer);
        $post = Post::getAllObj();
        $this->db->query = "UPDATE faq SET $post->sql_update WHERE faq_id = $this->faq_id;";
        $this->db->query();
        echo $this->faq_footer;
    }

    //atualiza posicoes
    public function UpdatePos() {
        $item = $_POST['item'];
        parse_str($item, $arr);
        foreach ($arr['li'] as $pos => $id) {
            $this->db->query = "UPDATE faq SET faq_pos = '$pos' WHERE faq_id = $id;";
            $this->db->query();
        }
    }

}
