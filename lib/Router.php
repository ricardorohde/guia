<?php

class Router {

    public $uri = array();
    public $controller;
    public $action;
    public $baseuri;
    public $idxbase = 0;
    public $dir_admin = 'painel';
    public $registry;
    public $db;

    public function __construct() {
        $this->uri = ( isset($_GET['r']) ) ? explode('/', $_GET['r']) : array('');
        $this->base();
        if (in_array("$this->dir_admin", $this->uri)) {
            $this->idxbase = 1;
        }
    }

    public function setPanel($dir) {
        $this->dir_admin = $dir;
        if (in_array("$this->dir_admin", $this->uri)) {
            $this->idxbase = 1;
        }
    }

    public function getPanel() {
        return $this->dir_admin;
    }

    public function getUri($key) {
        $this->uri = ( isset($_GET['r']) ) ? explode('/', $_GET['r']) : array('');
        if (array_key_exists($key, $this->uri)) {
            return $this->uri[$key];
        } else {
            return false;
        }
    }

    public function routeRedirect($registry, $origem) {
        $this->db = $registry->get('db');
        $this->db->query("SELECT * FROM rota WHERE rota_url = '$origem'")->fetchAllObj();
        if (isset($this->db->data[0])) {
            $r = $this->db->data[0];
            $redir = self::base() . "/";
            $redir .= $r->{'rota_controle'} . "/";
            if ($r->{'rota_acao'} != "") {
                $redir .= $r->{'rota_acao'} . "/";
            }
            if ($r->{'rota_item_id'} >= 1) {
                $redir .= $r->{'rota_item_id'} . "/";
            }
            Router::redirect("$redir");
        }
    }

    public function controller() {
        $this->controller = ( $this->uri[$this->idxbase] == NULL ) ? 'index' : $this->uri[$this->idxbase];
        return ( is_string($this->controller) ) ? $this->controller : 'index';
    }

    public function action() {
        $this->action = (
                isset($this->uri[$this->idxbase + 1]) && strlen($this->uri[$this->idxbase + 1]) != 0 && is_string($this->uri[$this->idxbase + 1])
                ) ? $this->uri[$this->idxbase + 1] : 'indexController';
        return $this->action;
    }

    public function base() {
        $this->baseuri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') .
                (isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
                (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
                        (isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] === 443 ||
                        $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) .
                substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
        return $this->baseuri;
    }

    public function current() {
        $current_url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $current_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        return $current_url;
    }

    public function redirect($url = '') {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
        }
    }

    public function getUriParts() {
        return explode('/', $_GET['r']);
    }

}
