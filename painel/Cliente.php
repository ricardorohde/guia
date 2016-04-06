<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Cliente extends ClienteModel
{

    public $cliente_id;
    public $cliente_logo = false;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
    public $registry;

    public function __construct($registry = null)
    {
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
        $this->tpl->assign('MENU_ATIVO', 'cliente')
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
    public function __destruct()
    {
        parent:: __destruct();
    }

    //método principal da classe - exibe cliente cadastrados
    public function indexController()
    {
        $this->db->url = Router::base() . "/painel/cliente";
        $this->db->paginate(25);
        if (isset($_POST['busca']) && !empty($_POST['busca'])) {
            $busca = addslashes(trim($_POST['busca']));
            $this->db->query = "SELECT * FROM cliente WHERE cliente_nome like'%$busca%' OR cliente_empresa like'%$busca%' OR cliente_meta_keywords like'%$busca%'";
        } else {
            //$this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) ORDER BY cliente_tipo DESC, grupo_nome ASC, cliente_nome ASC";
            $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) ORDER BY cliente_status ASC, grupo_nome ASC, cliente_nome ASC";
        }
        $this->db->query()->fetchAllObj();
        //tpl->data é enviado ao template cliente.html para listagem no foreach
        $this->tpl->data = $this->db->data;
        $this->tpl->grupo = $this->getClienteGrupo();
        $this->tpl->tpl('admin/cliente.phtml')
            ->assign('PAGINACAO', $this->db->pages)
            ->render();
    }

    public function editar()
    {
        $this->cliente_id = Filter::parse_int(Router:: getUri(3));
        $this->getCliente();
        $this->db->query()->fetchAllObj();
        //tpl->data é enviado ao template cliente.html para listagem no foreach
        $this->tpl->data = (object)$this->db->data[0];
        $this->tpl->grupo = $this->getClienteGrupo();
        $this->tpl->tpl('admin/cliente_editar.phtml')->render();
    }

    //método inclui ou atualiza cliente
    public function atualizar()
    {
        parent:: atualizar();
        Router::redirect("$this->baseuri/painel/cliente/ok-now/");
    }

}
