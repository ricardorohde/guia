<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Slide extends SlideModel {

    public $slide_id;
    public $slide_url = false;
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
        //recupera ou add site do registry 
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
            $this->site = $this->registry->get('site');
        }
        //inicia sessao
        $this->sessao = new Session;
        $this->sessao->start();
        //verifica login / sessão ativa
        $this->login = new Login($this->registry);
        $this->login->status();
        //recupera usuario da sessão
        $this->user = new Usuario($this->registry);
        $this->user->setId($this->sessao->getNode('usuario_id'));
        $this->user->getById();

        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'slide')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()))
                ->assign('BASEURI', Router::base());
        //verifica se existe na url o parametro "ok-now" que indica sucesso
        if (in_array('ok-now', Router::getUriParts())) {
            $this->tpl->assign('FN_ONLOAD', '_alert("<p><i class=\"fa fa-check-circle-o\"></i> Procedimento realizado com sucesso!</p>");');
        } else {
            $this->tpl->assign('FN_ONLOAD', '/**NOTHING_FOR_LOAD**/');
        }
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos_json as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;
    }

    //método destrutor encerra DB
    public function __destruct() {
        $this->db->destroy();
        unset($this->db);
    }

    //método principal da classe - exibe slide cadastrados
    public function indexController() {
        $this->db->url = Router::base() . "/painel/slide";
        $this->db->paginate(25);
        $this->db->query = "SELECT * FROM slide WHERE slide_local = 1 ORDER BY slide_pos ASC, slide_titulo ASC";
        $this->db->query()->fetchAllObj();
        //tpl->data é enviado ao template slide.html para listagem no foreach
        $this->tpl->data = $this->db->data;
        $this->tpl->tpl('admin/slide.phtml')
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

    //método principal da classe - exibe slide cadastrados
    public function banner() {
        $this->db->url = Router::base() . "/painel/slide/banner";
        $this->db->paginate(25);
        $this->db->query = "SELECT * FROM slide WHERE slide_local <> 1 ORDER BY slide_local ASC, slide_pos ASC, slide_titulo ASC";
        $this->db->query()->fetchAllObj();
        //tpl->data é enviado ao template slide.html para listagem no foreach
        $this->tpl->data = $this->db->data;
        $this->tpl->tpl('admin/banner.phtml')
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

    //método upload do slide
    public function upload() {
        parent:: upload();
        //redireciona para página de conclusão
        if ($this->slide_local <> 1) {
            Router::redirect("$this->baseuri/painel/slide/banner/ok-now/");
        } else {
            Router::redirect("$this->baseuri/painel/slide/ok-now/");
        }
    }

}
