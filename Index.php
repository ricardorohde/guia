<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
@session_start();
class Index {

    public $db;
    public $registry;
    public $tpl;
    public $menu;
    public $cliente;
    public $sessao;
    public $site;
    public $modulo;
    public $busca;
    public $busca_terms;
    public $busca_grupo;
    public $filtrohome;

    public function __construct() {
        //conexao com banco
        $this->registry = Registry::getInstance();
        $this->registry->set('db', new DB);
        $this->db = $this->registry->get('db');
        //recupera ou add site do registry
        $this->registry->set('site', new SiteModel($this->registry));
        $this->site = $this->registry->get('site');
        //novo template view + site obj
        $this->tpl = new Template;
        $this->tpl->site($this->site);
        //recupera ou add menu do registry
        $this->registry->set('menu', new Menu($this->registry));
        $this->menu = $this->registry->get('menu');
        $this->tpl->append('area', $this->menu->categoriaPaginas());

        $this->sessao = $this->registry->get('sessao');
        if (!$this->sessao) {
            $this->registry->set('sessao', new Session(__FILE__));
            $this->sessao = $this->registry->get('sessao');
        }
        $this->sessao->start();

        //Verifica modulos que devem ser carregados
        $this->modulo = new ModuloModel($this->registry);

        //modulo contato carregamento requerido
        if (in_array('contato', $this->modulo->ativos)) {
            $contato = new ContatoModel($this->registry);
            $modulo_config = $this->modulo->config['contato'];
            $this->tpl->addMod('contato', array(
                'data' => $contato->get(),
                'end' => $contato->endereco,
                'config' => $modulo_config));
        }
        //modulo slideshow
        if (in_array('slideshow', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['slideshow'];
            $slide = new SlideModel($this->registry);
            //paginacao do modulo
            $slide->db->paginate($modulo_config->{'home-perpage'});
            $this->tpl->addMod('slideshow', array(
                'data' => $slide->getSlides(),
                'banner' => $slide->getBannerS(),
                'config' => $modulo_config)
            );
        }
        //modulo noticia
        if (in_array('noticia', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['noticia'];
            $news = new NoticiaModel($this->registry);
            $news->db->paginate($modulo_config->{'home-perpage'});
            $this->tpl->addMod('noticia', array(
                'menu' => '',
                'data' => $news->getAllNoticia(),
                'config' => $modulo_config));
        }
        //modulo social
        if (in_array('social', $this->modulo->ativos)) {
            //recupera configuracoes do modulo
            $modulo_config = $this->modulo->config['social'];
            $social = new SocialModel($this->registry);
            $this->tpl->addMod('social', array(
                'data' => $social->get(),
                'config' => $modulo_config,
                'obj' => $social
            ));
        }
        //social facebook fanpage
        if (in_array('facebook', $this->modulo->ativos)) {
            //recupera configuracoes do modulo
            $modulo_config = $this->modulo->config['facebook'];
            //envia para tpl as configs do modulo
            $this->tpl->addMod('facebook', array('config' => $modulo_config));
        }
        //modulo contato na home
        if (in_array('contato-home', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['contato-home'];
            //$contato = new ContatoModel($this->registry);
            $this->tpl->addMod('contato-home', array('config' => $modulo_config));
        }

        //modulo google analitycs
        if (in_array('ga', $this->modulo->ativos)) {
            //recupera configuracoes do modulo
            $modulo_config = $this->modulo->config['ga'];
            $this->tpl->addMod('ga', array('config' => $modulo_config));
        }

        //modulo cliente
        if (in_array('cliente', $this->modulo->ativos)) {
            $this->registry->set('cliente', new ClienteModel($this->registry));
            $this->cliente = $this->registry->get('cliente');
            $modulo_config = $this->modulo->config['cliente'];
            //paginacao do modulo
            $this->cliente->db->paginate($modulo_config->{'home-perpage'});
            $clientes_last = $this->cliente->getClientesComum();
            $clientes_home = $this->cliente->getClientesVip();
            shuffle($clientes_home);
            $this->cliente->db->pagination = false;
            //limite do menu
            $this->cliente->menu_lista = $this->cliente->getClienteGrupo();
            $this->cliente->menu_right = array_slice($this->cliente->menu_lista, 0, $modulo_config->{'right-menu-perpage'});
            //$this->cliente->menu_combo = $this->cliente->menu_lista;
            $this->tpl->addMod('cliente2', array(
                'menu-lista' => $this->cliente->menu_lista,
                'menu-right' => $this->cliente->menu_right,
                'data-home' => $clientes_home,
                'data-last' => $clientes_last,
                'config' => $modulo_config));
        }

        //modulo filtro
        if (in_array('filtrohome', $this->modulo->ativos)) {

          $this->registry->set('filtrohome', new FiltroHomeModel($this->registry));
          $this->filtrohome = $this->registry->get('filtrohome');
          $modulo_config = $this->modulo->config['filtrohome'];

          $gruposDoFiltroHome = $this->filtrohome->getFiltros();
          $this->tpl->addMod('filtrohome', array(
              'filtrohome-data' => $gruposDoFiltroHome
          ));
        }

        $this->tpl->registry = $this->registry;
        $this->tpl->modulo_all = $this->modulo;
        $this->tpl->sessao = $this->sessao;
    }

    public function indexController() {
        $this->tpl->tpl("index.phtml")->render();
    }

    public function categorias() {
        $this->tpl->tpl("categorias.phtml")->render();
    }

    public function busca() {
        parse_str(file_get_contents('php://input'), $_PUT);
        $termos = $_PUT['termos'];
        $grupo = $_PUT['grupo'];
        $this->tpl->modulo['cliente']['data'] = $this->cliente->getBusca($termos, $grupo);
        $this->tpl->tpl("resultado_busca.phtml")->render();
    }

    public function por_categoria() {
        $this->cliente->grupo_url = Router::getUri(2);
        $this->tpl->modulo['cliente']['data'] = $this->cliente->getClienteByCatSlug();
        $this->tpl->tpl("resultado_busca.phtml")->render();
    }

    public function contato() {
        $this->tpl->tpl("contato.phtml")->render();
    }

    public function __destruct() {
        //echo Server:: memory_end();
    }

}
