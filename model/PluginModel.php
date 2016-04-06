<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class PluginModel {

    public $db;
    public $links;
    public $response;
    public $tpl;
    public $registry;
    public $baseuri;
    public $modulo_id;

    public function __construct($registry = null) {
        $this->baseuri = Router::base();
        //conexao com banco
        if ($registry != null) {
            $this->registry = $registry;
            $this->db = $this->registry->get('db');
        } else {
            $this->registry = Registry::getInstance();
            $this->registry->set('db', new DB);
            $this->db = $this->registry->get('db');
        }
        //recupera ou add site do registry 
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
            $this->site = $this->registry->get('site');
        }
        $this->tpl = new Template;
        $this->rota = new Router;
        //verifica se o módulo é chamado no front ou admin para checar a sessão
        if (in_array($this->rota->dir_admin, $this->rota->getUriParts())) {
            $this->sessao = new Session;
            $this->sessao->start();
            $this->login = new Login($this->registry);
            $this->login->status();
            $this->user = new Usuario($this->registry);
            $this->user->setId($this->sessao->getNode('usuario_id'));
            $this->user->getById();
            $this->tpl->assign('TEMPO_SESSAO', $this->sessao->getLeftTime())
                    ->assign('USER_NOME', $this->user->getNome())
                    ->assign('USER_LOGIN', ucwords($this->user->getLogin()));
        }
        $this->tpl->assign('MENU_ATIVO', 'dashboard')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_SLOGAN', $this->site->getSlogan())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('BASEURI', Router::base());
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos_json as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;
    }

    public function dir_remove($source, $excludeSvnFolders = true, $recusion = false) {
        @chmod($source, 0777);
        $dir_handle = opendir($source);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file == '.' || $file == '..')
                continue;
            if ($excludeSvnFolders && $file == '.svn')
                continue;
            if (!is_dir($source . '/' . $file)) {
                @chmod($source . '/' . $file, 0777);
                unlink($source . '/' . $file);
            } else {
                $this->dir_remove($source . '/' . $file, $excludeSvnFolders, true);
            }
        }
        closedir($dir_handle);
        if ($recusion) {
            rmdir($source);
        }
        return true;
    }

    public function parse_mysql_dump($filename) {
        global $success, $msg;
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line_num => $line) {
            if (substr($line, 0, 2) != '--' && $line != '') {
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    if (!mysqli_query($this->db->link, $templine)) {
                        $success = false;
                        $msg = "<div class=\"qerror\">'" . mysqli_errno($this->db->link) . " "
                                . mysqli_errno($this->db->link) . "' Erro:</div><div class=\"query\">{$templine} </div>";
                        echo $msg;
                    } else {
                        echo 'executando ... <br>';
                    }
                    $templine = '';
                }
            }
        }
    }

}
