<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Galeria extends GaleriaModel {

    public $galeria_nome;
    public $galeria_id;
    public $galeria_url;
    public $foto_id;
    public $foto_url;
    public $video_id;
    public $video_titulo;
    public $video_url;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
    public $db;
    public $registry;

    public function __construct($registry = null) {
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
        //inicializa template com variaveis comuns de configuracao do site
        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'midia')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()))
                ->assign('BASEURI', Router::base());
        //verifica se existe na url o parametro "ok-now" indica sucesso no procedimento realizado
        if (in_array('ok-now', Router::getUriParts())) {
            $FN_ONLOAD = '_alert("<p><i class=\"fa fa-check-circle-o\"></i> Procedimento realizado com sucesso!</p>");';
        } else {
            $FN_ONLOAD = "/**NOTHING_FOR_LOAD**/\n";
        }
        if (in_array('no-mirror', Router::getUriParts())) {
            $FN_ONLOAD = '_alert_error("<p><i class=\"fa fa-times-circle-o\"></i> A fonte de vídeo deve ser Youtube ou Vimeo !</p>");';
        }
        $this->tpl->assign("FN_ONLOAD", "$FN_ONLOAD");
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos_json as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }      
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;        
    }

    //método principal da classe - exibe áreas cadastradas
    public function indexController() {
        //defina a url base da paginacao
        $this->db->url = Router::base() . "/painel/galeria";
        //define 25 itens  por pagina
        $this->db->paginate(25);
        //tpl->data é enviado ao template galeria.html para listagem no foreach
        $this->tpl->data = $this->getAllGaleria();
        //categorias de galeria
        $this->tpl->categorias = $this->getCategorias();        
        //seleciona o arquivo template phtml e repassa com assign os links da paginacao (db->pages)
        $this->tpl->tpl('admin/galeria.phtml')
                ->assign('PAGINACAO', $this->db->pages)
                ->render();
    }

    //método para exibir galeria de fotos de uma galeria
    public function foto() {
        //recupera o id da galeria passado no parametro 3 da url /galeria/foto/id_galeria/
        $this->galeria_id = Filter::parse_int(Router::getUri(3));
        //recupera dados da galeria a partir do id recebido como parametro
        $this->tpl->data = $this->getGaleria($this->galeria_id);
        //fotos da galeria
        $this->tpl->fotos = $this->tpl->data->{'fotos'};
        //videos da galeria
        //$this->tpl->videos = $this->tpl->data->{'videos'};
        //categorias de galeria
        $this->tpl->categorias = $this->tpl->data->{'categorias'};
         //Filter:: printr($this->tpl->categorias);exit;
        //seta o template html utilizado e o renderiza na tela
        $this->tpl->tpl('admin/galeria_foto.phtml')
                ->assign('GALERIA_ID', $this->galeria_id)
                ->assign('GALERIA_NOME', $this->galeria_nome)
                ->assign('GALERIA_CATEGORIA', $this->gcategoria_nome)
                ->assign('PAGINACAO', $this->data->pages)
                //->fetch('FOTOS', $this->tpl->data['fotos'])
                //->fetch('MODAL', $this->tpl->data['fotos'])
                ->render();
    }

    //método para exibir galeria de videos
    public function video() {
        //recupera o id da galeria passado no parametro 3 da url /galeria/foto/id_galeria/
        $this->galeria_id = Filter::parse_int(Router::getUri(3));
        //envia registry para o layout
        $this->tpl->galeria = $this;
        //recupera dados da galeria a partir do id recebido como parametro
        $this->tpl->data = $this->getGaleria($this->galeria_id);
        //videos da galeria
        $this->tpl->videos = $this->tpl->data->{'videos'};
        //seta o template html utilizado e o renderiza na tela
        $this->tpl->tpl('admin/galeria_video.phtml')
                ->assign('GALERIA_ID', $this->galeria_id)
                ->assign('GALERIA_NOME', $this->galeria_nome)
                ->assign('PAGINACAO', $this->data->pages)
                //->fetch('VIDEOS', $this->tpl->data['videos'])
                ->render();
    }
    //método incluir vídeo youtube/vimeo
    public function incluirVideo() {
        parent:: incluirVideo();
    }

    //método atualiza registro vindo do fomulário galeria_editar do método editar()
    public function atualizar() {
        parent::atualizar();
        //redireciona para página de conclusão
        Router::redirect("$this->baseuri/painel/galeria/ok-now/");
    }

}
