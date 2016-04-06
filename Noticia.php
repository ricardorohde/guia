<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Noticia
{

    public $site;
    public $db;
    public $site_template;
    public $tpl;
    public $menu;
    public $registry;
    public $sessao;

    public function __construct()
    {
        //inicia sessao
        $this->sessao = new Session;
        $this->sessao->start();
        //conexao com banco
        $this->registry = Registry::getInstance();
        $this->registry->set('db', new DB);
        $this->db = $this->registry->get('db');

        //recupera ou add site do registry 
        $this->registry->set('site', new SiteModel($this->registry));
        $this->site = $this->registry->get('site');


        //checando rotas
        $this->noticia_url = Filter::parse_string(Router::getUri(2));
        $rota = new Router;
        $rota->routeRedirect($this->registry, Router::getUri(1) . "/$obj->noticia_url/");

        //novo template view + site obj
        $this->tpl = new Template;
        $this->tpl->site($this->site);

        //recupera ou add menu do registry 
        $this->registry->set('menu', new Menu($this->registry));
        $this->menu = $this->registry->get('menu');
        $this->tpl->append('area', $this->menu->categoriaPaginas());

        //Verifica modulos ativos
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
        $this->registry->set('noticia', new NoticiaModel($this->registry));
        if (in_array('noticia', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['noticia'];
            $this->tpl->addMod('noticia', array('config' => $modulo_config));
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
            $this->tpl->addMod('cliente', array(
                'menu-lista' => $this->cliente->menu_lista,
                'menu-right' => $this->cliente->menu_right,
                'data-home' => $clientes_home,
                'data-last' => $clientes_last,
                'config' => $modulo_config));
        }

        //grava acesso a noticia - stats
        //recupera ou add stats do registry 
        $this->registry->set('stats', new StatsModel($this->registry));
        $this->stats = $this->registry->get('stats');
        $this->stats->init();
        //modulos adicionais
        $this->tpl->registry = $this->registry;
        $this->tpl->modulo_all = $this->modulo;
        $this->tpl->sessao = $this->sessao;
    }

    //metodo principal /noticia/
    public function indexController()
    {
        $obj = $this->registry->get('noticia');
        //noticia com sem categoria
        $category = Filter::parse_string(Router::getUri(2));
        if (isset($category) && $category == "" && !is_numeric($category)) {
            //recupera o id da noticia no parametro 2 da url /noticia/url-slug/id_noticia/
            $obj->noticia_url = Filter::parse_string(Router::getUri(1));
            $obj->noticia_id = Filter::parse_int(Router::getUri(2));
        } else {
            //noticia sem categoria
            $obj->noticia_url = Filter::parse_string(Router::getUri(2));
            $obj->noticia_id = Filter::parse_int(Router::getUri(3));
        }
        if (in_array('page', Router::getUriParts())) {
            $obj->noticia_url = "";
            $obj->noticia_id = 0;
        }
        $rota = new Router;
        $rota->routeRedirect($this->registry, Router::getUri(1) . "/$obj->noticia_url/");

        if ($obj->noticia_id >= 1 && $obj->pagine_url != 'page') {
            //recupera dados da noticia a partir do id recebido como parametro na url
            $this->tpl->append('noticia', $obj->getNoticia());
            //stats - incrementa visitas
            $this->stats->up('noticia', $obj->noticia_id);
        } elseif ($obj->noticia_url != "" && $obj->noticia_url != 'page') {
            //echo $obj->noticia_url;exit;
            //recupera dados da noticia a partir da url-slug recebida como parametro
            $this->tpl->append('noticia', $obj->getNoticiaBySlug());
            //stats - incrementa visitas
            $this->stats->up('noticia', $obj->noticia_id);
        } else {
            //recupera todos as noticia - nenhum parametro na url
            $obj->db->url = Router::base() . "/noticia";
            $obj->db->paginate($this->modulo->config['noticia']->{'perpage'});
            $this->tpl->append('noticia', $obj->getAllNoticia());
            $this->tpl->append('paginacao', $obj->db->pages);
            $this->tpl->assign('paginacao', $obj->db->pages);
        }
        //configura o template utilizado e renderiza
        $this->tpl->tpl("noticia.phtml")->render();
    }

    public function __destruct()
    {
        // echo Server:: memory_end();
    }

}
