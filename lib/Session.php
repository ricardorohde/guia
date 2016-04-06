<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
Class Session {

    public $left = 0;
    public $fileHandler;

    /**
     * $session = new Session;
     * $session->start();
     * $session->init(TimeLife,Domain);
     * $session->check("dominio");
     * $session->status();
     * $session->destroy();
     */
    public function __construct($file = null) {
        $this->fileHandler = $file;
        // echo  '<div style="background:#dcdcdc;padding:5px;"> sess in '.$file.'...</div>';
    }

    public function start() {
        @session_start();
    }

    public function init($timeLife = 720000, $domain = null) {
        $this->start();
        $_SESSION["__ACTIVITY_ID"] = md5(uniqid(time()));
        $_SESSION['__LAST_ACTIVITY'] = time();
        $_SESSION['__SESSION_ID'] = session_id();
        $_SESSION['__SS_DOMAIN'] = md5($domain);
        $_SESSION['__LIFE_TIME'] = $timeLife;
    }

    public function getLeftTime() {
        $minutos = floor(($_SESSION['__LIFE_TIME'] - (time() - $_SESSION['__LAST_ACTIVITY']) ) / 60);
        $segundos = (($_SESSION['__LIFE_TIME'] - (time() - $_SESSION['__LAST_ACTIVITY']) ) % 60 );
        if ($segundos <= 9) {
            $segundos = "0" . $segundos;
        }
        return "$minutos:$segundos";
    }

    public function addNode($key, $value) {
        $_SESSION['node'][$key] = $value;
        return $this;
    }

    public function getNode($key) {
        if (isset($_SESSION['node'][$key])) {
            return $_SESSION['node'][$key];
        } else {
            //echo utf8_decode("session node [$key] não existe em ");
            return false;
        }
    }

    public function getDomain() {
        if (isset($_SESSION['__SS_DOMAIN'])) {
            return $_SESSION['__SS_DOMAIN'];
        }
    }

    public function remNode($key) {
        if (isset($_SESSION['node'][$key])) {
            unset($_SESSION['node'][$key]);
        }
        return $this;
    }

    public function destroyNodes() {
        if (isset($_SESSION['node'])) {
            unset($_SESSION['node']);
        }
        return $this;
    }

    public function check($domain) {
        $this->left = floor(($_SESSION['__LIFE_TIME'] - ( time() - $_SESSION['__LAST_ACTIVITY']) ) / 60);
        //if ( !isset( $_SESSION['__LAST_ACTIVITY'] ) || ((time() - $_SESSION['__LAST_ACTIVITY']) >= $_SESSION['LIFE_TIME']) )
        if ($this->left <= 0) {
            return false;
        } else {
            /*
              if (md5($domain) != $this->getDomain()) {
              return false;
              } else {
              return true;
              }
             */
            return true;
        }
    }

    public function getId() {
        if (isset($_SESSION['__SESSION_ID'])) {
            return $_SESSION['__SESSION_ID'];
        } else {
            return false;
        }
    }

    public function regenerate() {
        @session_regenerate_id();
        $_SESSION['__SESSION_ID'] = session_id();
    }

    public function destroy() {
        $this->destroyNodes();
        @session_destroy();
        $_SESSION['__SS_DOMAIN'] = md5(uniqid(time()));
        @session_start();
    }

}

/* end file */