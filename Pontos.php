<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
@session_start();
class Pontos {

    public $db;
    public $registry;
    public $tpl;
    public $menu;
    public $cliente;
    public $sessao;
    public $site;
    public $modulo;
    public $busca;
    public $busca_terms;
    public $busca_grupo;

    public function __construct() {
        //conexao com banco
        $this->registry = Registry::getInstance();
        $this->registry->set('db', new DB);
        $this->db = $this->registry->get('db');
        //Verifica modulos que devem ser carregados
        $this->modulo = new ModuloModel($this->registry);
    }

    public function indexController() {
        $sql = "SELECT cliente_lat_lon, cliente_id, cliente_nome,cliente_empresa, cliente_url, cliente_logo,grupo_id,grupo_nome,grupo_url, grupo_icone FROM cliente LEFT JOIN grupo ON (cliente_grupo = grupo_id)  WHERE cliente_lat_lon <> ''";
        $this->db->query($sql)->fetchAll();

        foreach($this->db->data as $k => $v){
            //$this->db->data[$k]->{'client_lat'} = $this->db->data[$k]->{'client_lat_lon'};
            $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
            $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
            $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
            $this->db->data[$k]['cliente_nome'] = utf8_decode($this->db->data[$k]['cliente_nome']);
            $this->db->data[$k]['cliente_empresa'] = utf8_decode($this->db->data[$k]['cliente_empresa']);
            unset($this->db->data[$k]['cliente_lat_lon']);
        }
        //$this->db->data = (object) $this->db->data;

        echo $this->db->toJson($this->db->data);
    }


}
