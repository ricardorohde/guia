<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Portfolio {

    public $site;
    public $db;
    public $site_template;
    public $tpl;
    public $menu;
    public $registry;

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

        //Verifica modulos ativos
        $this->modulo = new ModuloModel($this->registry);

        //Modulo Produto Requerido
        $this->registry->set('galeria', New GaleriaModel($this->registry));

        if (in_array('galeria', $this->modulo->ativos)) {
            //recupera configuracoes do modulo
            $modulo_config = $this->modulo->config['galeria'];
            $this->tpl->addMod('galeria', array(
                'config' => $modulo_config
            ));
        }

        //modulo produto 
        if (in_array('produto', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['produto'];
            $produto = New ProdutoModel($this->registry);
            $produto->condicao = " WHERE produto_ativo = 1 AND produto_show = 1 ";
            $produto->menu = $produto->get2Menu($modulo_config->{'top-menu-perpage'});
            $this->tpl->addMod('produto', array(
                'menu' => $produto->menu,
                'config' => $modulo_config));
        }
        //recupera configuracoes do modulo servico
        if (in_array('servico', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['servico'];
            $servico = new ServicoModel($this->registry);
            $servico->menu = $servico->get2Menu($modulo_config->{'top-menu-perpage'});
            //envia para tpl data e config do modulo
            $this->tpl->addMod('servico', array(
                'config' => $modulo_config,
                'menu' => $servico->menu));
        }
        //modulo noticia 
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
        //modulo contato carregamento requerido
        if (in_array('contato', $this->modulo->ativos)) {
            $contato = new ContatoModel($this->registry);
            $modulo_config = $this->modulo->config['contato'];
            $this->tpl->addMod('contato', array(
                'data' => $contato->get(),
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
        //modulo google analitycs
        if (in_array('ga', $this->modulo->ativos)) {
            //recupera configuracoes do modulo
            $modulo_config = $this->modulo->config['ga'];
            $this->tpl->addMod('ga', array('config' => $modulo_config));
        }
        //grava acesso a pagina - stats
        //recupera ou add stats do registry 
        $this->registry->set('stats', new StatsModel($this->registry));
        $this->stats = $this->registry->get('stats');
        $this->stats->init();      
        //modulos adicionais
        $this->tpl->registry = $this->registry;
        $this->tpl->modulo_all = $this->modulo;           
    }

    //metodo principal /portfolio/
    public function indexController() {
        $obj = $this->registry->get('galeria');
        $obj->db->url = Router::base() . "/portfolio";
        $obj->galeria_url = Filter::parse_string(Router::getUri(1));
        $obj->galeria_id = Filter::parse_int(Router::getUri(2));
        if ($obj->galeria_id >= 1 && $obj->galeria_url != 'page') {
            //recupera dados da produto a partir do id recebido como parametro na url
            $data = $obj->getGaleria($obj->galeria_id);
            $this->stats->up('galeria', $obj->galeria_id);
        } else {
            //tenta recuperar por url-slug            
            $slug = $obj->getBySlug();
            if (isset($slug->{'galeria_id'})) {
                //data by slug
                $data = $slug;
                $this->stats->up('galeria', $slug->{'galeria_id'});
            } else {
                //todas as galerias
                $obj->db->paginate($this->modulo->config['galeria']->{'perpage'});
                $data = $obj->getAllGaleriaFotosVideos();
            }
        }
        $this->tpl->append('obj', $obj);
        $this->tpl->append('paginacao', $obj->db->paginacao);
        $this->tpl->append('galeria', $data);
        $this->tpl->append('gcategoria', $obj->getCategorias());
        //$this->tpl->append('paginacao', $obj->db->paginacao);
        //configura o template utilizado e renderiza
        $this->tpl->tpl("portfolio.phtml")->render();
    }
}
