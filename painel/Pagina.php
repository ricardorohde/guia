<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Pagina extends PaginaModel {

    public $pagina_id;
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
        //verifica login / sessao ativa
        $this->login = new Login($this->registry);
        $this->login->status();
        //recupera usuario da sessao
        $this->user = new Usuario($this->registry);
        $this->user->setId($this->sessao->getNode('usuario_id'));
        $this->user->getById();
        //seta variaveis padrao do site
        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'pagina')
                ->assign('TIMESTAMP', date('dmYhis'))
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
        parent :: __destruct();
    }

    //método principal da classe - exibe páginas cadastradas
    public function indexController() {
        $this->db->url = Router::base() . "/painel/pagina";
        $this->db->paginate(25);
        //tpl->data é enviado ao template pagina.html para listagem no foreach
        $this->tpl->data = $this->getAllPagina();
        $this->tpl->tpl('admin/pagina.phtml')
                //->fetch('PAGINA_LIST', $this->db->data)
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

    //método para exibir formulário de cadastro de páginas
    public function nova() {
        $this->tpl->areas = $this->getAreas();
        $this->tpl->tpl('admin/pagina_nova.phtml')->render();
    }

    //método para exibir formulário de cadastro de páginas
    public function editar() {
        $this->pagina_id = Filter::parse_int(Router:: getUri(3));
        $this->tpl->areas = $this->getAreas();
        $this->tpl->data = $this->getPagina();
        $this->tpl->tpl('admin/pagina_editar.phtml')->render();
    }

    //método inclui registro vindo do fomulário pagina_nova do método nova()
    public function incluir() {
        parent:: incluir();
        //redireciona para página de conclusão
        Router::redirect("$this->baseuri/painel/pagina/ok-now/");
    }

    //método atualiza registro vindo do fomulário pagina_editar do método editar()
    public function atualizar() {
        parent:: atualizar();
        //redireciona para página de conclusão
        Router::redirect("$this->baseuri/painel/pagina/ok-now/");
    }

}
