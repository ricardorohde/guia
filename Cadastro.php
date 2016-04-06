<?php
/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.pc-click.com.br
 * Data: 08/2014
 */
class Cadastro extends ClienteModel {

    public $db;
    public $registry;
    public $tpl;
    public $menu;
    public $cliente;
    public $sessao;
    public $site;
    public $modulo;
    public $busca;
    public $busca_terms;
    public $busca_grupo;
    public $abseuri;

    public function __construct() {
        $this->baseuri = Router::base();
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
        $this->tpl->sessao = $this->sessao;
    }

    public function indexController() {
        $this->tpl->tpl("cadastro.phtml")->render();
    }

    //método inclui ou atualiza cliente
    public function atualizar() {
        parent:: atualizar();
        //Router::redirect("$this->baseuri/cadastro/editar/novo/");
            $this->cliente_email = Filter:: parse_string(Post:: get('cliente_email'));
            $this->cliente_senha =  Post:: get('cliente_senha');
            $sql = "SELECT * FROM cliente WHERE cliente_email = '$this->cliente_email' AND cliente_senha = '$this->cliente_senha'";
            $this->db->query("$sql")->fetchAllObj();
            if ($this->db->rows >= 1) {
                $this->sessao->init(720000, $this->baseuri);
                $this->sessao->check("$this->baseuri");
                $this->sessao->addNode('cliente_id', $this->db->data[0]->{'cliente_id'});
                $this->sessao->addNode('cliente_empresa', $this->db->data[0]->{'cliente_empresa'});
                $this->sessao->addNode('cliente_email', $this->db->data[0]->{'cliente_email'});
                $this->sessao->addNode('cliente_senha', $this->db->data[0]->{'cliente_senha'});
		        Router::redirect("$this->baseuri/cadastro/editar/");
	    }else{
			    Router::redirect("$this->baseuri/cadastro/entrar/");
		}
    }

    //método login
    public function entrar() {
        if (isset($this->sessao) && $this->sessao->getNode('cliente_id') >= 1) {
            Router::redirect("$this->baseuri/cadastro/editar/");
        }
        $msg_login = "";
        if (in_array('incorreto', Router::getUriParts())) {
            $msg_login = "<h5 class='alert alert-danger'><i class='fa fa-warning'></i> E-mail ou senha inválidos!</h5>";
        }
        $this->tpl->assign('_LOGIN-MSG_', $msg_login);
        $this->tpl->tpl('cadastro_entrar.phtml')->render();
    }

    //método recuperar senha
    public function recuperar() {
        $this->tpl->tpl('cadastro_recuperar.phtml');
        if (in_array('enviado', Router::getUriParts())) {
            $repass = "<p class='alert alert-info'> Sua nova senha foi enviada ṕor e-mail com sucesso!</p>";
        } else {
            $repass = "<p class='alert alert-danger'> E-mail não cadastrado em nossa base de dados!</p>";
        }
        $this->tpl->assign('MSG_REPASS', $repass);
        $this->tpl->render();
    }

    //método editar cadastro
    public function editar() {
        if ($this->status()) {
            $this->cliente_id = $this->sessao->getNode('cliente_id');
            //tpl->data é enviado ao template cadastro_editar.phtml
            $this->tpl->data['cliente'] = (object) $this->getCliente();
            $this->tpl->tpl('cadastro_editar.phtml')->render();
        }
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
        if (isset($_POST['cliente_email']) && !empty($_POST['cliente_email']) && isset($_POST['cliente_senha']) && !empty($_POST['cliente_senha'])) {
            $this->cliente = new ClienteModel($this->registry);
            $this->cliente_email = Filter:: parse_string($_POST['cliente_email']);
            $this->cliente_senha = md5(Filter:: parse_string($_POST['cliente_senha']));
            $sql = "SELECT * FROM cliente WHERE cliente_email = '$this->cliente_email' AND cliente_senha = '$this->cliente_senha'";
            $this->db->query("$sql")->fetchAllObj();
            if ($this->db->rows >= 1) {
                $this->sessao->init(720000, $this->baseuri);
                $this->sessao->check("$this->baseuri");
                $this->sessao->addNode('cliente_id', $this->db->data[0]->{'cliente_id'});
                $this->sessao->addNode('cliente_empresa', $this->db->data[0]->{'cliente_empresa'});
                $this->sessao->addNode('cliente_email', $this->db->data[0]->{'cliente_email'});
                $this->sessao->addNode('cliente_senha', $this->db->data[0]->{'cliente_senha'});
                if (isset($_POST['lembrar']) && !empty($_POST['lembrar'])) {
                    @setcookie("_COOKY_", $this->db->data[0]->{'cliente_id'}, time() + 3600, "/", "", 0);
                } else {
                    @setcookie("_COOKY_", "", time() - 3600, "/", "", 0);
                }
                Router::redirect("$this->baseuri/cadastro/editar/");
            } else {
                $this->sessao->destroy();
                Router::redirect("$this->baseuri/cadastro/entrar/incorreto/");
            }
        }
        // Filter:: printr($_COOKIE);
    }

    public function novasenha() {
        if (isset($_POST['cliente_email']) && !empty($_POST['cliente_email'])) {
            $this->cliente = new ClienteModel($this->registry);
            $this->cliente_email = Filter:: parse_string($_POST['cliente_email']);
            $sql = "SELECT * FROM cliente WHERE cliente_email = '$this->cliente_email' ";
            $this->db->query("$sql")->fetchAllObj();
            if ($this->db->rows >= 1) {
                $this->cliente_senha = Filter::genpass();
                $sql = "UPDATE cliente SET cliente_senha = '" . md5($this->cliente_senha) . "' WHERE cliente_email = '$this->cliente_email' ";
                $this->db->query("$sql");
                $this->enviarNovaSenha($this->cliente_email, $this->cliente_senha);
            } else {
                Router::redirect("$this->baseuri/cadastro/recuperar/incorreto/");
            }
        }
    }

    public function enviarNovaSenha($email, $pass) {
        $body = '<html><body>';
        $body .= '<h1 style="font-size:15px;">Sua nova senha foi gerada!</h1>';
        $body .= '<table style="border-color: #666; font-size:11px" cellpadding="10">';
        $body .= '<tr style="background: #eee;"><td><strong>IP Solicitante:</strong> </td><td>' . $_SERVER['REMOTE_ADDR'] . '</td></tr>';
        $body .= '<tr style="background: #fff;"><td><strong>Data:</strong> </td><td>' . date('d/m/Y h:s') . '</td></tr>';
        $body .= '<tr style="background: #eee;"><td><strong>Nova Senha:</strong> </td><td>' . $pass . '</td></tr>';
        $body .= '</table>';
        $body .= '<br/><br/>';
        $body .= "<a href='$this->baseuri/cadastro/'> Clique aqui para entrar </a>";
        $body .= '<br/><br/>';
        $body .= '</body></html>';
        if ($this->MySendMail($this->site->{'site_title'} . " - Recuperação de senha", $body, $email)) {
            Router::redirect("$this->baseuri/cadastro/recuperar/enviado/");
        } else {
            $message = "Houve um erro ao enviar o e-mail! Entre em contato com suporte!";
        }
        return $message;
    }

    public function MySendMail($subject, $body, $email) {
        $this->db->query = "SELECT * FROM smtp";
        $this->db->query()->fetchAllObj();
        require_once "plugins/phpmailer/class.phpmailer.php";
        $m = $this->db->data[0];
        $mail = new PHPMailer();
        $mail->SMTPSecure = "tls";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->WordWrap = 80;
        $mail->IsHTML(true);
        $mail->Port = "$m->smtp_port";
        $mail->Host = "$m->smtp_host";
        $mail->Username = "$m->smtp_user";
        $mail->Password = "$m->smtp_pass";
        $mail->From = "$m->smtp_user";
        $mail->FromName = "$m->smtp_from";
        $mail->Subject = utf8_decode($subject);
        //$mail->AddBCC("$m->smtp_bcc");
        $mail->AddAddress("$email");
        //$mail->AddBCC("$msg->contato_email");
        //$mail->AddReplyTo("$msg->contato_email");        
        $mail->Body = utf8_decode($body);
        if (@$mail->Send()) {
            return true;
        } else {
            echo $mail->ErrorInfo;
            return false;
        }
    }

    public function logout() {
        //$this->usuario = new UsuarioModel;
        //$this->usuario->setId(0);
        $this->sessao->destroy();
        //@setcookie("_COOKY_", "", time() - 3600, "/", "", 0);
        Router::redirect("$this->baseuri/");
    }

    public function __destruct() {
        //echo Server:: memory_end();
    }

}
