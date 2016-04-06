<?php

class FacefanModel {

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
        $this->db->find(array("social"));
        $this->app_link = $this->db->data->app_link;
        $this->app_id = $this->db->data->app_id;
    }

    public function getAppId() {
        return stripslashes($this->app_id);
    }

    public function getAppLink() {
        return stripslashes($this->app_link);
    }

    //método destrutor 
    public function __destruct() {
        /*
          $this->db->destroy();
          unset($this->db);
          unset($this->registry);
         */
    }

}
