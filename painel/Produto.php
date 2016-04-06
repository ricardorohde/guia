<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Produto extends ProdutoModel {

    public $produto_id;
    public $produto_url;
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
        if (in_array('novo', Router::getUriParts())) {
            $this->tpl->assign('FN_ONLOAD_TAB', "novo();");
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
        parent:: __destruct();
    }
    //método principal da classe - exibe produtos cadastrados
    public function indexController() {
        //defina a url base da paginacao
        $this->db->url = Router::base() . "/painel/produto";
        //define 25 itens  por pagina
        $this->db->paginate(25);
        //tpl->data é enviado ao template produto.html para listagem no foreach
        $this->tpl->data = $this->getAllProduto();
        //retorna lista de categorias
        $this->tpl->categorias = $this->getCategorias();
        //seleciona o arquivo template phtml e repassa com assign os links da paginacao (db->pages)      
        $this->tpl->tpl('admin/produto.phtml')
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

    //método para exibir produto de fotos de uma produto
    public function foto() {
        //recupera o id da produto passado no parametro 3 da url /produto/foto/id_produto/
        $this->produto_id = Filter::parse_int(Router::getUri(3));
        //recupera dados da produto a partir do id recebido como parametro
        $this->tpl->data = $this->getProduto($this->produto_id);
        //recupera fotos da produto 
        $this->tpl->fotos = $this->tpl->data['fotos'];
        //Filter::printr($this->tpl->data);exit;
        //seta o template html utilizado e o renderiza na tela
        $this->tpl->tpl('admin/produto_foto.phtml')
                ->assign('PRODUTO_ID', $this->produto_id)
                ->assign('PAGINACAO', $this->data->pages)
                //->fetch('FOTOS', $this->tpl->data['fotos'])
                //->fetch('MODAL', $this->tpl->data['fotos'])
                ->render();
    }
    //método atualiza registro vindo do fomulário produto_editar do método editar()
    public function atualizar() {
        parent::atualizar();
        //redireciona para página de conclusão
        Router::redirect("$this->baseuri/painel/produto/ok-now/");
    }
}
