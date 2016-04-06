<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Faq {

    public $site;
    public $db;
    public $site_template;
    public $tpl;
    public $menu;
    public $registry;
    public $sessao;

    public function __construct() {
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

        //novo template view + site obj
        $this->tpl = new Template;
        $this->tpl->site($this->site);

        //recupera ou add menu do registry 
        $this->registry->set('menu', new Menu($this->registry));
        $this->menu = $this->registry->get('menu');
        $this->tpl->append('area', $this->menu->categoriaPaginas());

        //Verifica modulos ativos
        $this->modulo = new ModuloModel($this->registry);

        //Modulo Faq Requerido
        $this->registry->set('faq', New FaqModel($this->registry));
        //recupera configuracoes do modulo servico
        if (in_array('faq', $this->modulo->ativos)) {
            $faq = $this->registry->get('faq');
            $modulo_config = $this->modulo->config['faq'];
            //envia para tpl config e data do modulo
            $this->tpl->addMod('faq', array(
                'config' => $modulo_config,
                'data' => $faq->getAll()));
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
        //modulos adicionais
        $this->tpl->registry = $this->registry;
        $this->tpl->modulo_all = $this->modulo;        
        $this->tpl->sessao = $this->sessao;        
    }

    //metodo principal /faq/
    public function indexController() {
        $obj = $this->registry->get('faq');
        //recupera o id da faq no parametro 2 da url /faq/url-slug/id_faq/
        $obj->faq_url = Filter::parse_string(Router::getUri(2));
        $obj->faq_id = Filter::parse_int(Router::getUri(3));

        if ($obj->faq_id >= 1 && $obj->pagine_url != 'page') {
            //recupera dados da faq a partir do id recebido como parametro na url
            $this->tpl->append('faq', $obj->getById());
        } elseif ($obj->faq_url != "" && $obj->faq_url != 'page') {
            //recupera dados da faq a partir da url-slug recebida como parametro
            $this->tpl->append('faq', $obj->getBySlug());
        } else {
            //recupera todos as faq - nenhum parametro na url
            $obj->db->url = Router::base() . "/faq";
            $obj->db->paginate($this->modulo->config['faq']->{'perpage'});
            $this->tpl->append('faq', $obj->getAll());
        }
        $this->tpl->append('paginacao', $obj->db->paginacao);
        //configura o template utilizado e renderiza
        $this->tpl->tpl("faq.phtml")->render();
    }

    public function __destruct() {
        // echo Server:: memory_end();
    }

}
