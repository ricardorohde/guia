<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Menu {

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
    }

    public function categorias() {
        $this->db->paginate(6);
        $this->db->query = "SELECT * FROM area ORDER BY area_pos ASC, area_nome ASC";
        $this->db->query()->fetchAllObj();
        $menu = array();
        if ($this->db->rows >= 1) {
            $menu = $this->db->data;
        }
        return $menu;
    }

    public function categoriaPaginas() {
        //$this->db->paginate(6);
        $this->db->query = "SELECT * FROM area WHERE area_show = 1 OR area_footer = 1 ORDER BY area_pos ASC, area_nome ASC";
        $this->db->query()->fetchAllObj();
        $menu = array();
        if ($this->db->rows >= 1) {
            $menu = $this->db->data;
            foreach ($menu as $k => $v) {
                $this->area_id = $menu[$k]->{'area_id'};
                $this->db->query = "SELECT pagina_id,pagina_nome,pagina_area,pagina_url FROM pagina WHERE pagina_area = $this->area_id ORDER BY pagina_nome ASC";
                $this->db->query()->fetchAllObj();
                $menu[$k]->{'PAG'} = $this->db->data;
            }
        }
        //Filter:: printr($menu);exit;
        return (object) $menu;
    }

    public function paginas() {
        $this->db->paginate(6);
        $this->db->query = "SELECT * FROM pagina ORDER BY pagina_nome ASC";
        $this->db->query()->fetchAllObj();
        $menu = array();
        if ($this->db->rows >= 1) {
            $menu = $this->db->data;
        }
        return $menu;
    }

}

?>
