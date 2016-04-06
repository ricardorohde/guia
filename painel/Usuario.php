<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Usuario extends UsuarioModel {

    public $usuario_id = null;
    public $usuario_nome;
    public $usuario_login;
    public $usuario_email;
    public $usuario_nivel;
    public $usuario_senha;
    public $usuario_fone;
    public $site;
    public $db;
    public $session;
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
        //recupera site do registry
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
        }
        //inicia sessao
        $this->sessao = $this->registry->get('sessao');
        if (!$this->sessao) {
            $this->registry->set('sessao', new Session(__FILE__));
            $this->sessao = $this->registry->get('sessao');
        }
        $this->sessao->start();
        //verifica login / sessão ativa
        $this->login = $this->registry->get('login');
        if (!$this->login) {
            $this->registry->set('login', new Login($this->registry));
            $this->login = $this->registry->get('login');
        }
        $this->login->status();

        //recupera usuario da sessão
        $this->setId($this->sessao->getNode('usuario_id'));
        $this->getById();
        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'configuracao')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->getNome())
                ->assign('USER_LOGIN', ucwords($this->getLogin()))
                ->assign('BASEURI', Router::base());

        //verifica se existe na url o parametro "ok-now" que indica sucesso
        if (in_array('ok-now', Router::getUriParts())) {
            $this->tpl->assign('FN_ONLOAD', '_alert("<p><i class=\"fa fa-check-circle-o\"></i> Procedimento realizado com sucesso!</p>");');
        } else {
            $this->tpl->assign('FN_ONLOAD', '/**NOTHING_FOR_LOAD**/');
        }
        if (in_array('novo', Router::getUriParts())) {
            $this->tpl->assign('FN_ONLOAD_TAB', "$('a[href=\"#novo\"]').tab('show');$('a[href=\"#lista\"]').removeClass('hide').fadeIn();$('a[href=\"#novo\"]').hide();");
        } else {
            $this->tpl->assign('FN_ONLOAD_TAB', "/**NOTHING_FOR_TAB_LOAD**/");
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

    //método atualizar dados
    public function atualizar() {
        parent:: atualizar();
        //redireciona para editar
        Router::redirect("$this->baseuri/painel/usuario/ok-now/");
    }

    //método remover usuario
    public function remover() {
        parent:: remover();
        //redireciona para lista
        Router::redirect("$this->baseuri/painel/usuario/ok-now/");
    }

    //método principal da classe - exibe usuario cadastrados
    public function indexController() {
        $this->db->url = Router::base() . "/painel/usuario";
        $this->db->paginate(25);
        $this->db->query = "SELECT * FROM usuario ORDER BY usuario_nome ASC";
        $this->db->query()->fetchAllObj();
        //tpl->data é enviado ao template usuario.html para listagem no foreach
        $this->tpl->data = $this->db->data;
        $this->tpl->tpl('admin/usuario.phtml')
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

}
