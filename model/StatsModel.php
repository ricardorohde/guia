<?php

@session_start();

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class StatsModel {

    public $acesso_id;
    public $acesso_ip;
    public $acesso_data;
    public $acesso_hora;
    public $acesso_url;
    public $acesso_local;
    public $site;
    public $user;
    public $login;
    public $session;
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
        $this->acesso_ip = Filter::parse_string($_SERVER['REMOTE_ADDR']);
        $this->acesso_data = date('d-m-Y');
        $this->acesso_hora = date('H:i');
        $this->acesso_url = Router::current();
        $this->acesso_agent = ""; //$_SERVER['HTTP_USER_AGENT'];
    }

    //método destrutor
    public function __destruct() {
        //unset($_SESSION['ACESSO_URL']);
        //unset($_SESSION['ACESSO_IP']);
    }

    public function init() {
        if (isset($_SESSION['ACESSO_IP'])) {
            $of = (array) $_SESSION['ACESSO_URL'][md5($this->acesso_ip)];
            if (!in_array("$this->acesso_url", $of)) {
                $this->checkin();
            }
        } else {
            $this->checkin();
        }
    }

    public function checkin() {
        $this->db->query = "SELECT * FROM acesso WHERE acesso_ip = '$this->acesso_ip' AND acesso_data = '$this->acesso_data' AND acesso_url = '$this->acesso_url' ";
        $this->db->query()->fetchAll();
        if (!isset($this->db->data[0]['acesso_ip'])) {
            $this->db->query = "INSERT INTO acesso (acesso_ip,acesso_data, acesso_hora,acesso_url) ";
            $this->db->query .= "VALUES ('$this->acesso_ip','$this->acesso_data','$this->acesso_hora','$this->acesso_url') ";
            $this->db->query();
        }
        $_SESSION['ACESSO_IP'] = "$this->acesso_ip";
        $_SESSION['ACESSO_URL'][md5($this->acesso_ip)] = "$this->acesso_url";
    }

    public function up($tbl, $id) {
        //if (isset($_SESSION['ACESSO_IP'])) {
            $of = (array) $_SESSION['ACESSO_URL'][$tbl][$id];
            if (!in_array("$this->acesso_url", $of)) {
                $this->db->increment($tbl, "${tbl}_click_uniq", 1, "${tbl}_id = $id");
                $this->db->increment($tbl, "${tbl}_click_view", 1, "${tbl}_id = $id");
                $_SESSION['ACESSO_URL'][$tbl][$id] = $this->acesso_url;
            }else{
                $this->db->increment($tbl, "${tbl}_click_view", 1, "${tbl}_id = $id");
            }
        //}
    }

    public function close() {
        $_SESSION['ACESSO_IP'] = "$this->acesso_ip";
        $_SESSION['ACESSO_URL'][md5($this->acesso_ip)] = "$this->acesso_url";
    }

}
