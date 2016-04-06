<?php

class UpdateModel {

    public $db;
    public $registry;
    public $ativos = array();
    public $inativos = array();
    public $perpage;
    public $modulo;
    public $config;

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
        $this->baseuri = Router::base();
    }

    //metodo principal 
    public function indexController() {
        //novo template view + site obj
        $this->tpl = new Template;
    }

    //método destrutor 
    public function __destruct() {
        //$this->db->destroy();
        //unset($this->db);
        //unset($this->registry);
    }

}
