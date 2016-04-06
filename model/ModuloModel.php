<?php

class ModuloModel {

    public $db;
    public $registry;
    public $ativos = array();
    public $inativos = array();
    public $menu = array();
    public $perpage;
    public $modulo;
    public $id;
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
        $this->db->query = "SELECT *  FROM modulo order by modulo_ordem ASC, modulo_nome ASC";
        $this->db->query()->fetchAllObj();
        foreach ($this->db->data as $m) {

            $json_config = json_decode($m->modulo_config_options);

            if ($json_config->{'status'} == 1) {
                $this->ativos[] = $m->modulo_nome;
                if ($m->modulo_menu_admin == 1) {
                    $this->menu[] = "modulos/$m->modulo_dir/view/menu.phtml";
                }
            } else {
                $this->inativos[] = $m->modulo_nome;
            }
            $this->ativos_json = $this->ativos;
            $this->config["$m->modulo_nome"] = $json_config;
            $this->core["$m->modulo_nome"] = $m->modulo_core;
            $this->tpl["$m->modulo_nome"] = $m->modulo_tpl_public;
            $this->dir["$m->modulo_nome"] = $m->modulo_dir;
            $this->id["$m->modulo_nome"] = $m->modulo_id;
            $this->indexes["$m->modulo_id"] = array($m->modulo_config_options, $m->modulo_nome, $m->modulo_id, $m->modulo_core, $m->modulo_dir,$json_config->{'status'});
        }
        $this->baseuri = Router::base();
    }

    //metodo principal
    public function indexController() {
        //novo template view + site obj
        $this->tpl = new Template;
    }

    public function loadMod($key) {
        if (isset($this->modulo[$key])) {
            return $this->modulo[$key];
        }
    }

    public function addMod($key = null, $data = null) {
        if (!isset($this->modulo[$key])) {
            $this->modulo[$key] = $data;
        }
        return $this;
    }

    public function isMod($key) {
        if (isset($this->modulo[$key])) {
            return true;
        } else {
            return false;
        }
    }

    public function getConfigKey($mod, $key) {
        if (isset($this->modulo[$mod]['config']->{$key})) {
            return $this->modulo[$mod]['config']->{$key};
        } else {
            return false;
        }
    }

    public function getDataKey($mod, $key, $idx = 0) {
        if (isset($this->modulo[$mod]['data']["$idx"]->{"$key"})) {
            return $this->modulo[$mod]['data']["$idx"]->{"$key"};
        } else {
            return false;
        }
    }

    public function get($mod, $key) {
        if (isset($this->modulo[$mod][$key])) {
            return $this->modulo[$mod][$key];
        } else {
            return false;
        }
    }

    public function getID($mod) {
        if (isset($this->id[$mod])) {
            return $this->id[$mod];
        } else {
            return false;
        }
    }

    public function inData($mod, $key, $idx = 0) {
        if (isset($this->modulo[$mod]["$key"]["$idx"])) {
            return $this->modulo[$mod]["$key"]["$idx"];
        } else {
            return false;
        }
    }

    //método destrutor
    public function __destruct() {
        //$this->db->destroy();
        //unset($this->db);
        //unset($this->registry);
    }

}
