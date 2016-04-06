<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Market extends MarketModel {

    public $site;
    public $usuario;
    public $tpl;
    public $registry;

    public function __construct($registry = null) {

        parent:: __construct($registry);
        //recupera ou add site do registry 
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
            $this->site = $this->registry->get('site');
        }
        $this->sessao = new Session;
        $this->sessao->start();

        $this->login = new Login($this->registry);
        $this->login->status();

        $this->user = new Usuario($this->registry);
        $this->user->setId($this->sessao->getNode('usuario_id'));
        $this->user->getById();

        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'dashboard')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_SLOGAN', $this->site->getSlogan())
                ->assign('TEMPO_SESSAO', $this->sessao->getLeftTime())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()))
                ->assign('BASEURI', Router::base());
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;
    }

    public function indexController() {
        $this->tpl->tpl('admin/market_place.phtml')->render();
    }

    public function get() {
        echo 'getting...';
        $_SESSION['__CMS_MODULE_INSTALL__'] = "onechat";
        $_SESSION['__CMS_MODULE_NAME__'] = "oneChat";

        $mod_hash = $_SESSION['__CMS_MODULE_INSTALL__'];
        $mod_name = $_SESSION['__CMS_MODULE_NAME__'];

        $mod = $this->getMod($mod_hash);
        if ($this->finish()) {
            Router::redirect("$this->baseuri/painel/$mod_name/instalar/");
        } else {
            
        }
    }

    //método destrutor encerra DB
    public function __destruct() {
        //unset($this->db);
    }

}
