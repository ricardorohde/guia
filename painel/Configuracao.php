<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Configuracao {

    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
    public $smtp;
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
        //recupera ou add site do registry 
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new SiteModel($this->registry);
            $this->registry->set('site', $this->site);
            $this->site = $this->registry->get('site');
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

        $this->tpl->assign('MENU_ATIVO', 'configuracao')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('SITE_TEMPLATE', $this->site->getTemplate())
                ->assign('SITE_TEMPLATE_BOX', $this->site->getTemplateBox())
                ->assign('SITE_TEMPLATE_SKIN', $this->site->getTemplateSkin())
                ->assign('SITE_TEMPLATE_DIR', $this->site->getTemplateDir())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()));
        //verifica se existe na url o parametro "ok-now" indica sucesso no procedimento realizado
        if (in_array('ok-now', Router::getUriParts())) {
            $this->tpl->assign('FN_ONLOAD', '_alert("<p><i class=\"fa fa-check-circle-o\"></i> Procedimento realizado com sucesso!</p>");');
        } else {
            $this->tpl->assign('FN_ONLOAD', '/**NOTHING_FOR_LOAD**/');
        }
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos_json as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;
        $this->baseuri = Router::base();
    }

    //método destrutor encerra DB
    public function __destruct() {
        
    }

    //método principal da classe  
    public function indexController() {
        
    }

    //método configura o layout e tema
    public function tema() {
        //verifica se existe na url o parametro "atualizar" 
        if (in_array('atualizar', Router::getUriParts())) {
            $this->atualizarTema();
        } else {
            //recupera os dados do site
            $this->tpl->site = $this->site;
            $tema = Router::getUri(3);
            if ($tema != 'ok-now' && $tema != '') {
                $this->tpl->site->site_template = $tema;
                $this->tpl->site->site_template_dir = $tema;
            }

            $this->db->query = "SELECT * FROM tema ORDER BY tema_nome";
            $this->db->query()->fetchAllObj();
            $this->tpl->append('temas', $this->db->data);
            //seleciona o arquivo configuracao_tema.phtml 
            $this->tpl->tpl('admin/configuracao_tema.phtml')
                    ->append('path_views', $this->tpl->path_views)
                    ->append('site', $this->tpl->site)
                    ->assign('TEMA', $tema)
                    ->render();
        }
    }

    //método configura dados comuns do site (titulo, meta tags)
    public function seo() {
        //verifica se existe na url o parametro "atualizar" dados do site
        if (in_array('atualizar', Router::getUriParts())) {
            $this->atualizarSeo();
        } else {
            //recupera os dados do site
            $this->tpl->site = $this->site;
            //seleciona o arquivo configuracao_seo.phtml 
            $this->tpl->tpl('admin/configuracao_seo.phtml')->render();
        }
    }

    //método configura dados comuns do site (nome, logo, quem somos)
    public function site() {
        //verifica se existe na url o parametro "atualizar" dados do site
        if (in_array('atualizar', Router::getUriParts())) {
            $this->atualizarSite();
        } else {
            //recupera os dados do site
            $this->tpl->site = $this->site;
            //seleciona o arquivo configuracao_site.phtml             
            $this->tpl->tpl('admin/configuracao_site.phtml')->render();
        }
    }

    //método editar quem somos
    public function sobre() {
        //verifica se existe na url o parametro "atualizar" e
        if (in_array('atualizar', Router::getUriParts())) {
            $this->atualizarSobre();
        } else {
            //recupera os dados do site
            $this->tpl->site = $this->site;
            //seleciona o arquivo configuracao_site.phtml 
            $this->tpl->tpl('admin/configuracao_sobre.phtml')->render();
        }
    }

    //método editar contato
    public function contato() {
        //verifica se existe na url o parametro "atualizar" e
        if (in_array('atualizar', Router::getUriParts())) {
            $this->atualizarContato();
        } else {
            //recupera os dados do site
            $this->tpl->site = $this->site;
            //recupera os dados de contato
            $contato = new ContatoModel($this->registry);
            $this->tpl->contato = $contato->get();
            //seleciona o arquivo configuracao_site.phtml 
            $this->tpl->tpl('admin/configuracao_contato.phtml')->render();
        }
    }

    //método configura dados de smtp para envio de e-mail autenticado
    public function email() {
        //verifica se existe na url o parametro "atualizar" dados do site
        $this->smtp = new MailerModel($this->registry);
        if (in_array('atualizar', Router::getUriParts())) {
            $this->smtp->atualizar();
            //redireciona para editar
            //Router::redirect("$this->baseuri/painel/configuracao/email/ok-now/");
        } else {
            //recupera os dados do site
            $this->tpl->smtp = $this->smtp->get();
            //seleciona o arquivo configuracao_site.phtml 
            $this->tpl->tpl('admin/configuracao_email.phtml')->render();
        }
    }

    //método configura dados comuns do site (titulo, meta tags)
    public function social() {
        //verifica se existe na url o parametro "atualizar" dados do site
        $this->social = new SocialModel($this->registry);
        if (in_array('atualizar', Router::getUriParts())) {
            $this->social->atualizar();
            //redireciona para editar
            Router::redirect("$this->baseuri/painel/configuracao/social/ok-now/");
        } else {
            //recupera os dados do site
            $this->tpl->site = $this->site;
            //recupera os dados de rede social
            $this->tpl->social = $this->social->get();
            //seleciona o arquivo configuracao_social.phtml 
            $this->tpl->tpl('admin/configuracao_social.phtml')->render();
        }
    }

    //método atualiza dados do site
    public function atualizarSite() {
        //define o caminho de armazenamento da logo
        $dir_dest = 'midia/layout/';
        $file = $_FILES['site_logo'];
        //verifica se a logo foi enviada 
        if (!empty($file['name'])) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                //$handle->image_resize = true;
                //$handle->image_ratio_crop = true;
                if ($handle->image_src_x > 200 || $handle->image_y > 200) {
                    //$handle->image_ratio = true;
                    //$handle->image_x = 100;
                    //$handle->image_y = 600;
                }
                //$handle->image_convert = 'png';
                //$handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->file_new_name_body = 'main-logo';
                $handle->Process($dir_dest);
                if ($handle->processed) {
                    $this->site_logo = $handle->file_dst_name;
                    Post::addIndex('site_logo', $this->site_logo);
                } else {
                    //echo $handle->error;
                    echo $handle->log;
                    exit;
                    Post::removeIndex('site_logo');
                }
            }
        }
        //atualiza site
        Post::parseString('site_about');
        $post = Post::getAllObj();
        $this->db->query = "UPDATE site SET $post->sql_update;";
        $this->db->query();
        //redireciona para editar
        Router::redirect("$this->baseuri/painel/configuracao/site/ok-now/");
    }

    //método atualiza dados do site
    public function atualizarSeo() {
        $post = Post::getAllObj();
        //atualiza site
        $this->db->query = "UPDATE site SET $post->sql_update;";
        $this->db->query();
        //redireciona para editar
        Router::redirect("$this->baseuri/painel/configuracao/seo/ok-now/");
    }

    //método atualiza tema do front
    public function atualizarTema() {
        $post = Post::getAllObj();
        //atualiza site
        $this->db->query = "UPDATE site SET $post->sql_update;";
        $this->db->query();
        $tema = Router::getUri(3);
        //redireciona para editar
        Router::redirect("$this->baseuri/painel/configuracao/tema/ok-now/");
    }

    //método atualiza dados do site
    public function atualizarSobre() {
        $post = Post::getAllObj();
        //atualiza site
        $this->db->query = "UPDATE site SET $post->sql_update;";
        $this->db->query();
        //redireciona para editar
        Router::redirect("$this->baseuri/painel/configuracao/sobre/ok-now/");
    }

    //método atualiza dados de contato
    public function atualizarContato() {
        $end = Post::get('contato_endereco') . ", " .
                Post::get('contato_bairro') . ", " .
                Post::get('contato_cidade') . ", " .
                Post::get('contato_uf');
        $lat_lon = Geo ::getLatLon($end);
        //Filter:: printr($end);
        //Filter:: printr($lat_lon);
        if (isset($lat_lon['lat']) && isset($lat_lon['lon']) && !empty($lat_lon['lat'])) {
            Post::changeIndex('contato_lat', $lat_lon['lat']);
            Post::changeIndex('contato_lon', $lat_lon['lon']);
        }
        $contato = new ContatoModel($this->registry);
        $contato->atualizar();
        //redireciona para editar
        Router::redirect("$this->baseuri/painel/configuracao/contato/ok-now/");
    }

}
