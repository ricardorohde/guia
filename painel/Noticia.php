<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */

class Noticia extends NoticiaModel {

    public $noticia_id;
    public $site;
    public $db;
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
        
        
        //verifica login / sessao ativa
        $this->login = $this->registry->get('login');
        if (!$this->login) {
            $this->registry->set('login', new Login($this->registry));
            $this->login = $this->registry->get('login');
        }
        $this->login->status();
        //recupera usuario da sessao
        $this->user = new Usuario($this->registry);
        $this->user->setId($this->sessao->getNode('usuario_id'));
        $this->user->getById();
        //seta variaveis padrao do site
        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'noticia')
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

    //método principal da classe - exibe notícias cadastradas
    public function indexController() {
        $this->db->url = Router::base() . "/painel/noticia";
        $this->db->paginate(25);
        //tpl->data é enviado ao template noticia.html para listagem no foreach
        $this->tpl->data = $this->getAllNoticia();
        $this->tpl->tpl('admin/noticia.phtml')->assign('PAGINACAO', $this->db->pages)->render();
    }

    //método para exibir formulário de cadastro de notícias
    public function nova() {
        //retorna lista de categorias de noticias (ncategoria)
        $this->tpl->categorias = $this->getCategorias();
        $this->tpl->tpl('admin/noticia_nova.phtml')
                //->fetch('NCATEGORIA_LIST', $this->getCategorias())
                ->render();
    }

    //método para exibir formulário de cadastro de notícias
    public function editar() {
        //retorna lista de categorias de noticias (ncategoria)
        $this->noticia_id = Filter::parse_int(Router:: getUri(3));
        $this->tpl->categorias = $this->getCategorias();
        $this->tpl->noticia = $this->getNoticia();
        $this->tpl->tpl('admin/noticia_editar.phtml')->render();
    }

    //método inclui registro vindo do fomulário noticia_nova do método nova()
    public function incluir() {
        parent:: incluir();
        //redireciona para notícia de conclusão
        Router::redirect("$this->baseuri/painel/noticia/ok-now/");
    }

    //método atualiza registro vindo do fomulário noticia_editar do método editar()
    public function atualizar() {
        parent:: atualizar();
        //redireciona para notícia de conclusão
        Router::redirect("$this->baseuri/painel/noticia/ok-now/");
    }

}
