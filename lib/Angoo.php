<?php

class Angoo {

    public $db = null;
    public $obj = array();

    public function __construct() {
        //parent::__construct();
        $this->db = new DB;
    }

  //private function __construct() {}
  private function __clone() {}
  
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new MyResource();
        }

        return self::$instance;
    }

    public function __set($k, $v) {
        if (!isset($this->obj[$k])) {
            $this->obj[$k] = $v;
        }
    }

    public function __get($k) {
        if (isset($this->obj[$k]) && !empty($this->obj[$k])) {
            return $this->obj[$k];
        } else {
            return '';
        }
    }

    //método destrutor encerra DB
    public function __destruct() {
        //$this->db->destroy();
        //unset($this->db);
    }

}

?>
