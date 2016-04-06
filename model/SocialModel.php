<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class SocialModel {

    public $db;
    public $links;
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

    //método atualiza registro vindo do fomulário
    public function atualizar() {
        $post = Post::getAllObj();
        $this->db->query = "UPDATE social SET $post->sql_update;";
        $this->db->query();
    }

    //recupera dados
    public function get() {
        $this->db->query = "SELECT * FROM social";
        $this->db->query()->fetchAllObj();
        $this->links = $this->db->data[0];
        return $this->db->data[0];
    }

    //recupera link
    public function getLink($name) {
        $icon = preg_replace(array('/plus/'), array('google-plus'), $name);
        $name = "social_$name";
        if (isset($this->links->{"$name"}) && $this->links->{"$name"} != "") {
            return '<li><a href="' . $this->links->{"$name"} . '" target="_blank"><i class="fa fa-' . $icon . '"></i></a></li>' . "\n";
        }
    }

}
