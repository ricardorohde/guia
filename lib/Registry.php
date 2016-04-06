<?php

class Registry {

    private static $instance = null;
    public $registry = array();

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    public function set($key, $value) {
        if (isset($this->registry[$key])) {
            throw new Exception("Já existe uma instância para " . $key);
        }
        $this->registry[$key] = $value;
    }

    public function get($key) {
        $this->cleaner();
        if (!isset($this->registry[$key])) {
            //throw new Exception("Não existe a instância para " . $key);
            return false;
        } else {
            return $this->registry[$key];
        }
    }

    public function cleaner() {
        if (isset($this->registry['db']->{'data'})) {
            unset($this->registry['db']->{'data'});
        }
        if (isset($this->registry['db']->{'data_all'})) {
            unset($this->registry['db']->{'data_all'});
        }
        if (isset($this->registry['db']->{'result'})) {
            unset($this->registry['db']->{'result'});
        }
        if (isset($this->registry['db']->{'query'})) {
            unset($this->registry['db']->{'query'});
        }
    }

}
