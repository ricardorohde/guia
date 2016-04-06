<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class AngooModel {

    public $site;
    public $db;

    public function __construct() {

        $registry = Registry::getInstance();
        $registry->set('db', new DB);
        $this->db = $registry->get('db');

        $registry = Registry::getInstance();
        $registry->set('site', new Site);
        $this->site = $registry->get('site');
    }
    
    //método recuperar menu
    public function get2Menu() {

    }    

}
