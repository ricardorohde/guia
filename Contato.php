<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Contato {

    public $site;
    public $tpl;
    public $menu;
    public $registry;

    public function __construct($registry = null) {
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

        //modulo contato carregamento requerido
        $this->registry->set('contato', new ContatoModel($this->registry));
        if (in_array('contato', $this->modulo->ativos)) {
            $contato = $this->registry->get('contato');
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

        //Modulo Pagina Requerido
        $this->registry->set('pagina', New PaginaModel($this->registry));
        //recupera configuracoes do modulo servico
        if (in_array('pagina', $this->modulo->ativos)) {
            $modulo_config = $this->modulo->config['pagina'];
            //envia para tpl as configs do modulo
            $this->tpl->addMod('pagina', array('config' => $modulo_config));
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

        //modulos adicionais
        $this->tpl->registry = $this->registry;
        $this->tpl->modulo_all = $this->modulo;
        $this->tpl->sessao = $this->sessao;
    }

    public function indexController() {
        $contato = $this->registry->get('contato');
        $this->tpl->tpl("contato.phtml")->render();
    }

    public function enviar() {
        if (isset($_POST['dados']) && !empty($_POST['dados'])) {
            $msg = Post::parse2Obj($_POST['dados']);
            if (!isset($msg->contato_assunto)) {
                $msg->contato_assunto = "Contato via Site";
            }
            $contato = new MailerModel($this->registry);
            $status = $contato->enviar($msg);
            if ($status) {
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function enviar_empresa() {
        if (isset($_POST['dados']) && !empty($_POST['dados'])) {
            $msg = Post::parse2Obj($_POST['dados']);
            $msg->contato_assunto = "Contato sobre seu anúncio";
            $contato = new MailerModel($this->registry);
            $status = $contato->enviar_empresa($msg);
            if ($status) {
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function testar() {
        $contato = new MailerModel($this->registry);
        $status = $contato->teste();
        if ($status) {
            echo 0;
        } else {
            echo 1;
        }
    }

}
