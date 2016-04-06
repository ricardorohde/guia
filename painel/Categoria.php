<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Categoria extends CategoriaModel {

    public $categoria_id;
    public $categoria_url;
    public $foto_id;
    public $foto_url;
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
        //inicializa template com variaveis comuns de configuracao do site
        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'produto')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()))
                ->assign('BASEURI', Router::base());
        //verifica se existe na url o parametro "ok-now" indica sucesso no procedimento realizado
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
        parent::__destruct();
    }

    //método principal da classe - exibe áreas cadastradas
    public function indexController() {
        //defina a url base da paginacao
        $this->db->url = Router::base() . "/painel/categoria";
        //define 25 itens  por pagina
        $this->db->paginate(25);
        //tpl->data é enviado ao template categoria.html para listagem no foreach
        $this->tpl->data = $this->getAllCategoria();
        //seleciona o arquivo template phtml e repassa com assign os links da paginacao (db->pages)
        $this->tpl->tpl('admin/categoria.phtml')
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

    //método atualiza registro vindo do fomulário categoria_editar do método editar()
    public function atualizar() {
        parent::atualizar();
        //redireciona para página de conclusão
        Router::redirect("$this->baseuri/painel/categoria/ok-now/");
    }

}
