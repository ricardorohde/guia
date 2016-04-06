<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Index {

    public $site;
    public $usuario;
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
        }
        $this->db = $this->registry->get('db');
        //recupera ou add site do registry 
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
        }
        $this->site = $this->registry->get('site');
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

        $this->user = new UsuarioModel($this->registry);
        $this->user->setId($this->sessao->getNode('usuario_id'));
        $this->user->getById();

        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'dashboard')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_SLOGAN', $this->site->getSlogan())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('TEMPO_SESSAO', $this->sessao->getLeftTime())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()))
                ->assign('BASEURI', Router::base());
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos_json as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;
    }

    public function indexController() {
        //$this->db->factory(array('area'));
        //echo $this->db->area_nome;        
        //monsta grafico de acessos clicks
        $data = "-" . date('m') . "-" . date('Y');
        $mes_past = date('m') - 1;
        $mes_past = ($mes_past <= 9) ? "0$mes_past" : $mes_past;

        $data_past = "-"  . $mes_past. '-'.date('Y');
        $this->db->query = "SELECT acesso_data, count(*) as acesso_click FROM acesso WHERE acesso_data like'%$data%' OR acesso_data like'%$data_past%' GROUP BY acesso_data ORDER BY acesso_data ASC";

        $this->db->query()->fetchAllObj();
        $this->tpl->append('acesso', $this->db->data);

        /*
        //produtos cadastrados
        $this->db->query = "SELECT count(*) as click FROM produto";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('produto', $this->db->data[0]);
        //servicos cadastrados
        $this->db->query = "SELECT count(*) as click FROM servico";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('servico', $this->db->data[0]);
        //paginas cadastrados
        $this->db->query = "SELECT count(*) as click FROM pagina";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('pagina', $this->db->data[0]);
        //fotos cadastrados
        $this->db->query = "SELECT count(*) as click FROM foto";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('foto', $this->db->data[0]);
        //videos cadastrados
        $this->db->query = "SELECT count(*) as click FROM video";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('video', $this->db->data[0]);

        //mais acessados 
        $this->db->query = "select count(servico_click_uniq) as sum from servico;";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('serv', $this->db->data[0]);

        $this->db->query = "select count(produto_click_uniq) as sum from produto;";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('prod', $this->db->data[0]);

        $this->db->query = "select count(galeria_click_uniq) as sum from galeria;";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('gal', $this->db->data[0]);

        $this->db->query = "select count(pagina_click_uniq) as sum from pagina;";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('pag', $this->db->data[0]);

        $this->db->query = "select count(noticia_click_uniq) as sum from noticia;";
        $this->db->query()->fetchAllObj();
        $this->tpl->append('post', $this->db->data[0]);
        */
        
        $this->db->query = "SELECT cliente_empresa,cliente_click_uniq, cliente_click_view FROM cliente WHERE cliente_click_uniq >=1 ORDER BY cliente_click_uniq DESC";
        $this->db->paginate(10);
        
        $this->db->query()->fetchAllObj();
        $this->tpl->append('cliente', $this->db->data);
        ///Filter::printr($this->db->data);exit;

        $this->tpl->tpl('admin/index.phtml')->render();
    }

    //método destrutor encerra DB
    public function __destruct() {
        unset($this->db);
    }

}
