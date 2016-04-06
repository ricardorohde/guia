<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Migrate {

    public $site;
    public $db;
    public $site_template;
    public $tpl;
    public $menu;
    public $registry;

    public function __construct() {
        $this->registry = Registry::getInstance();
        $this->registry->set('db', new DB);
        $this->db = $this->registry->get('db');
    }

    public function indexController() {
        $sql = "UPDATE versao SET versao_num = 2, versao_num_info = '1.0.1', versao_data = '24/12/2014';";
        $this->db->query($sql);
    }

    public function __destruct() {
    }

}
