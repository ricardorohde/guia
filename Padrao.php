<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Cadastro {

    public $db;
    public $registry;
    public $tpl;
    public $menu;
    public $cliente;
    public $site;
    public $modulo;
    public $busca;
    public $busca_terms;
    public $busca_grupo;

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
            $clientes_last = $this->cliente->getClientes();
            $clientes_home = $clientes_last;
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
        
        $this->tpl->registry = $this->registry;
        $this->tpl->modulo_all = $this->modulo;
    }

    public function indexController() {
        $this->tpl->tpl("cadastro.phtml")->render();
    }


    public function __destruct() {
        //echo Server:: memory_end();
    }

}
