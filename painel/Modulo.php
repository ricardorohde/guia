<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Modulo extends ModuloModel {

    public $site;
    public $usuario;
    public $tpl;
    public $registry;

    public function __construct($registry = null) {

        parent:: __construct();
        //recupera ou add site do registry 
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
            $this->site = $this->registry->get('site');
        }
        $this->sessao = new Session;
        $this->sessao->start();

        $this->login = new Login($this->registry);
        $this->login->status();

        $this->user = new Usuario($this->registry);
        $this->user->setId($this->sessao->getNode('usuario_id'));
        $this->user->getById();

        $this->tpl = new Template;
        $this->tpl->assign('MENU_ATIVO', 'dashboard')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_SLOGAN', $this->site->getSlogan())
                ->assign('TEMPO_SESSAO', $this->sessao->getLeftTime())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('USER_NOME', $this->user->getNome())
                ->assign('USER_LOGIN', ucwords($this->user->getLogin()))
                ->assign('BASEURI', Router::base());
        //recupera modulos ativos para montar o menu
        $this->modulo = new ModuloModel($this->registry);
        foreach ($this->modulo->ativos as $mod) {
            $this->tpl->addMod($mod, array('a' => 'b'));
        }
        //modulos que possuem menu no admin
        $this->tpl->menu_mod = $this->modulo->menu;        
    }

    public function indexController() {
        $opts = "";
        foreach ($this->indexes as $m) {
            $status = ($m[5] == 1) ? " - Habilitado" : " - Desabilitado";            
            $id = $m[2];
            $name = $m[1];
            $core = getcwd() . "/model/" . $m[3] . ".php";
            $module = getcwd() . "/modulos/" . $m[4] . "/" . $m[3] . ".php";
            if (file_exists("$core") || file_exists("$module")) {
                $opts .= "<option value='$id'>" . $name . $status . "</a><Br>";
            }
        }
        //echo '</select>';
        $this->modulo_id = Filter::parse_int(Router::getUri(2));
        $conf = "";
        if ($this->modulo_id >= 1) {
            $conf = Filter::trim_str($this->indexes[$this->modulo_id][0]);
        }
        $this->tpl->assign('_MODCONF_', $conf);
        $this->tpl->assign('_MODULO_', $this->modulo_id);
        $this->tpl->assign('_OPTIONS_', $opts);
        $this->tpl->tpl('admin/modulo.phtml')->render();
    }

    public function save() {
        $this->modulo_id = Post :: getAndRemoveIndex('modulo_id');
        Post :: changeIndex('modulo_config_options', Filter::trim_str(Post::get('modulo_config_options')));
        if ($this->modulo_id >= 1) {
            $post = Post::getAllObj();
            $sql = "UPDATE modulo SET $post->sql_update WHERE modulo_id = $this->modulo_id;";
            //echo $sql;exit;
            $this->db->query($sql);
        }
        //redireciona para página de conclusão
        Router::redirect("$this->baseuri/painel/modulo/$this->modulo_id/");
    }

    //método destrutor encerra DB
    public function __destruct() {
        //unset($this->db);
    }

}
