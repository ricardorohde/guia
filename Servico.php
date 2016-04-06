<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Servico {

    public $site;
    public $stats;
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

        //Modulo Servico requerido
        $this->registry->set('servico', new ServicoModel($this->registry));
        //recupera configuracoes do modulo servico
        if (in_array('servico', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['servico'];
            $servico = $this->registry->get('servico');
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
        $contato = new ContatoModel($this->registry);
        $modulo_config = $this->modulo->config['contato'];
        $this->tpl->addMod('contato', array(
            'data' => $contato->get(),
            'config' => $modulo_config));

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

    //metodo principal /servico/
    public function indexController() {
        $obj = $this->registry->get('servico');
        //recupera o id da servico no parametro 2 da url /servico/url-slug/id_servico/
        $obj->servico_url = Filter::parse_string(Router::getUri(1));
        $obj->servico_id = Filter::parse_int(Router::getUri(2));
        if ($obj->servico_id >= 1 && $obj->servico_url != 'page') {
            //recupera dados da servico a partir do id recebido como parametro na url
            $this->tpl->append('servico', $obj->getServico());
            //stats - incrementa visitas
            $this->stats->up('servico', $obj->servico_id);
        } elseif ($obj->servico_url != "" && $obj->servico_url != 'page') {
            //recupera dados da servico a partir da url-slug recebida como parametro
            $this->tpl->append('servico', $obj->getServicoBySlug());
            //stats - incrementa visitas
            $this->stats->up('servico', $obj->servico_id);
        } else {
            //recupera todos os servicos - nenhum parametro na url
            $obj->db->url = Router::base() . "/servico";
            $obj->db->paginate($this->modulo->config['servico']->{'perpage'});
            $this->tpl->append('servico', $obj->getAllServico());
        }
        $this->tpl->append('paginacao', $obj->db->paginacao);
        //configura o template utilizado e renderiza
        $this->tpl->tpl("servico.phtml")->render();
    }

    public function __destruct() {
        // echo Server:: memory_end();
    }

}
