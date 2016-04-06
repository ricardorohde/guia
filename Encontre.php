<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
@session_start();
class Encontre
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
        $this->cliente_url = Filter::parse_string(Router::getUri(2));
        $rota = new Router;
        $rota->routeRedirect($this->registry, Router::getUri(1) . "/$obj->cliente_url/");

        //novo template view + site obj
        $this->tpl = new Template;
        $this->tpl->site($this->site);

        //recupera ou add menu do registry
        $this->registry->set('menu', new Menu($this->registry));
        $this->menu = $this->registry->get('menu');
        $this->tpl->append('area', $this->menu->categoriaPaginas());

        //Verifica modulos ativos
        $this->modulo = new ModuloModel($this->registry);

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
        $this->tpl->sessao = $this->sessao;
    }

    //metodo principal /pagina/
    public function indexController() {
      if (isset($_POST['checkvalues']) && !empty($_POST['checkvalues'])) {
        $perfisCheckeds = json_decode($_POST['checkvalues']);
        $this->tpl->append('checkvalues', $perfisCheckeds);

        $sql = "SELECT *
                FROM cliente";
        $this->db->query($sql)->fetchAll();

        foreach($this->db->data as $k => $v){
            //$this->db->data[$k]->{'client_lat'} = $this->db->data[$k]->{'client_lat_lon'};
            $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
            $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
            $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
            $this->db->data[$k]['cliente_nome'] = utf8_decode($this->db->data[$k]['cliente_nome']);
            $this->db->data[$k]['cliente_empresa'] = utf8_decode($this->db->data[$k]['cliente_empresa']);
            unset($this->db->data[$k]['cliente_lat_lon']);
        }

        echo $this->db->toJson($this->db->data);
        return;
      }

        if (isset($_POST['termos']) && !empty($_POST['termos'])) {
            $this->busca();
        } else {
            $obj = $this->registry->get('cliente');
            //recupera o id do cliente no parametro 2 da url /encontre/url-slug/id_cliente/
            $obj->cliente_url = Filter::parse_string(Router::getUri(2));
            $obj->cliente_id = Filter::parse_int(Router::getUri(3));
            if ($obj->cliente_id >= 1 && $obj->cliente_url != 'page') {
                //recupera dados da pagina a partir do id recebido como parametro na url
                $this->tpl->append('cliente', $obj->getCliente());
                //stats - incrementa visitas
                $this->stats->up('cliente', $obj->cliente_id);
            } elseif ($obj->cliente_url != "" && $obj->cliente_url != 'page') {
                //recupera dados da cliente a partir da url-slug recebida como parametro
                $this->tpl->append('cliente', $obj->getClienteBySlug());
                if ($obj->cliente_id <= 0) {
                    $obj->grupo_url = Router::getUri(1);
                    $this->tpl->modulo['cliente']['data'] = $this->cliente->getClienteByCatSlug();
                    $this->tpl->tpl("resultado_busca.phtml")->render();
                    exit;
                } else {
                    //stats - incrementa visitas
                    $this->stats->up('cliente', $obj->cliente_id);
                }
            } else {
                //recupera todos as cliente - nenhum parametro na url
                $obj->db->url = Router::base() . "/encontre";
                //$obj->db->paginate($this->modulo->config['pagina']->{'perpage'});
                $this->tpl->append('cliente', $obj->getClientes());
            }
            $this->tpl->append('paginacao', $obj->db->paginacao);
            //configura o template utilizado e renderiza
            $this->tpl->tpl("detalhe.phtml")->render();
        }
    }

    public function busca()
    {
        $this->cliente = $this->registry->get('cliente');
        parse_str(file_get_contents('php://input'), $_PUT);
        $termos = $_PUT['termos'];
        $_SESSION['__TERMOS__'] = $termos;
        $see = Filter::trim_str(explode(" em ", $termos));
        if (isset($see[0]) && isset($see[1])) {
            $negocio = Filter::trim_rl(Filter::trim_str($see[0]));
            $cidade = Filter::trim_rl(Filter::trim_str($see[1]));
            if ($negocio !== "" && $cidade !== "") {
                $this->tpl->modulo['cliente']['data'] = $this->cliente->getBuscaFree($negocio, $cidade);
                $this->tpl->tpl("resultado_busca.phtml")->render();
            }
        } else {
            $this->tpl->modulo['cliente']['data'] = $this->cliente->getBusca($termos);
            $this->tpl->tpl("resultado_busca.phtml")->render();
        }

    }

    public function em()
    {
        $this->cliente = $this->registry->get('cliente');
        $this->cliente->grupo_url = Router::getUri(2);
        $this->tpl->modulo['cliente']['data'] = $this->cliente->getClienteByCatSlug();
        $this->tpl->modulo['cliente']['menu-lista'] = $this->cliente->getClienteGrupo();
        $this->tpl->tpl("resultado_busca.phtml")->render();
    }

    public function __destruct()
    {
        // echo Server:: memory_end();
    }

}
