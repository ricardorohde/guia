<?php

class Login {

    public $db;
    public $site;
    public $usuario;
    public $sessao;
    public $router;
    public $cookie;
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
        $this->site = $this->registry->get('site');
        if (!$this->site) {
            $this->site = new Site($this->registry);
            $this->registry->set('site', $this->site);
        }

        $this->sessao = $this->registry->get('sessao');
        if (!$this->sessao) {
            $this->registry->set('sessao', new Session(__FILE__));
            $this->sessao = $this->registry->get('sessao');
        }
        $this->sessao->start();
        $this->baseuri = Router::base();
    }

    //método destrutor
    public function __destruct() {

    }

    public function indexController() {
        $tpl = new Template;
        $tpl->tpl('admin/login.html')
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_TEMA', $this->site->getTema())
                ->assign('BASEURI', $this->baseuri);
        /*
          if ($this->getcooky()) {
          Router::redirect("$this->baseuri/painel/");
          }
         */
        if (in_array('incorreto', Router::getUriParts())) {
            $tpl->assign('LOGIN_INCORRETO', "Login ou senha incorretos");
        } else {
            $tpl->assign('LOGIN_INCORRETO', "");
        }
        $tpl->render();
    }

    public function status() {
        if (!$this->sessao->check("$this->baseuri")) {
            $this->logout();
            return false;
        } else {
            return true;
        }
    }

    public function autenticar() {
        if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['senha']) && !empty($_POST['senha'])) {
            $this->usuario = new UsuarioModel($this->registry);
            $this->usuario->setLogin(Filter:: parse_string($_POST['login']));
            //$this->usuario->setSenha(md5(Filter:: parse_string($_POST['senha'])));
            $this->usuario->setSenha(Filter:: parse_string($_POST['senha']));
            $this->usuario->loginPass();
            if ($this->usuario->getId() >= 1) {
                $this->sessao->init(720000, $this->baseuri);
                $this->sessao->check("$this->baseuri");
                $this->sessao->addNode('usuario_id', $this->usuario->getId());
                $this->sessao->addNode('usuario_login', $this->usuario->getLogin());
                $this->sessao->addNode('usuario_email', $this->usuario->getEmail());
                $this->sessao->addNode('usuario_nome', $this->usuario->getNome());
                $this->sessao->addNode('usuario_nivel', $this->usuario->getNivel());
                if (isset($_POST['lembrar']) && !empty($_POST['lembrar'])) {
                    @setcookie("_COOKY_", $this->usuario->getId(), time() + 3600, "/", "", 0);
                } else {
                    @setcookie("_COOKY_", "", time() - 3600, "/", "", 0);
                }
                Router::redirect("$this->baseuri/painel/");
            } else {
                $this->sessao->destroy();
                Router::redirect("$this->baseuri/painel/login/incorreto/");
            }
        }
        // Filter:: printr($_COOKIE);
    }

    public function logout() {
        //$this->usuario = new UsuarioModel;
        //$this->usuario->setId(0);
        $this->sessao->destroy();
        //@setcookie("_COOKY_", "", time() - 3600, "/", "", 0);
        Router::redirect("$this->baseuri/painel/login/");
    }

    public function getcooky() {
        if (isset($_COOKIE["_COOKY_"])) {
            $this->usuario = new UsuarioModel($this->registry);
            $this->usuario->setId($_COOKIE["_COOKY_"]);
            $this->usuario->loginById();
            $this->sessao->start();
            $this->sessao->init(72000, $this->baseuri);
            $this->sessao->check("$this->baseuri");
            $this->sessao->addNode('usuario_id', $this->usuario->getId());
            $this->sessao->addNode('usuario_login', $this->usuario->getLogin());
            $this->sessao->addNode('usuario_email', $this->usuario->getEmail());
            $this->sessao->addNode('usuario_nome', $this->usuario->getNome());
            $this->sessao->addNode('usuario_nivel', $this->usuario->getNivel());
            return true;
        } else {
            return false;
        }
    }

}
