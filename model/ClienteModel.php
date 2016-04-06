<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.pc-click.com.br
 * Data: 08/2014
 */
class ClienteModel {

    public $cliente_id;
    public $cliente_logo = false;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
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
    }

    //método destrutor 
    public function __destruct() {
        /*
          $this->db->destroy();
          unset($this->db);
          unset($this->registry);
         */
    }

    //método recupera dados do cliente  
    public function getCliente() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE cliente_id = $this->cliente_id";
        $this->db->query()->fetchAll();
        if (isset($this->db->data[0]['cliente_id'])) {
            $this->cliente_logo = $this->db->data[0]['cliente_logo'];
            $this->cliente_nome = $this->db->data[0]['cliente_nome'];
            return $this->db->data[0];
        }
    }

    //método recupera dados do cliente  
    public function getClienteBySlug() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE cliente_url = '$this->cliente_url'";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            $this->cliente_logo = $this->db->data[0]->{'cliente_logo'};
            $this->cliente_nome = $this->db->data[0]->{'cliente_nome'};
            $this->cliente_id = $this->db->data[0]->{'cliente_id'};
            return $this->db->data[0];
        }
    }

    //método recupera dados do cliente  
    public function getClienteByCat() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE cliente_categoria = '$this->cliente_categoria' AND cliente_status = 2";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'grupo_id'})) {
            $this->grupo_url = $this->db->data[0]->{'grupo_url'};
            $this->grupo_nome = $this->db->data[0]->{'grupo_nome'};
            $this->grupo_id = $this->db->data[0]->{'grupo_id'};
            return $this->db->data;
        }
    }

    //método recupera dados do cliente  
    public function getClienteByCatSlug() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE grupo_url = '$this->grupo_url'  AND cliente_status = 2 GROUP BY cliente_id";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            $this->cliente_logo = $this->db->data[0]->{'cliente_logo'};
            $this->cliente_nome = $this->db->data[0]->{'cliente_nome'};
            $this->cliente_id = $this->db->data[0]->{'cliente_id'};
            return $this->db->data;
        }
    }

    //método recupera todos os clientes  
    public function getClientes() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE  cliente_status = 2  ORDER BY  cliente_tipo DESC, cliente_nome ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        }
    }

    //método recupera todos os clientes vips tipo 2
    public function getClientesVip() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE  cliente_status = 2 AND cliente_tipo = 2 ORDER BY cliente_nome ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        }
    }

    //método recupera clientes novos tipo 1 (nao vip)
    public function getClientesComum() {
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) WHERE  cliente_status = 2 AND cliente_tipo = 1 ORDER BY cliente_id DESC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        }
    }

    //método recupera todos os clientes  
    public function getClienteGrupo($limit = 10000000000) {
        $this->db->query = "SELECT * FROM grupo ORDER BY grupo_nome ASC, grupo_pos ASC LIMIT 0, $limit";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'grupo_id'})) {
            return $this->db->data;
        }
    }

    //método recupera todos os clientes  
    public function getGrupoByLetter($l,$limit = 1000000000) {
        $this->db->query = "SELECT * FROM grupo WHERE grupo_url like'$l%' ORDER BY grupo_nome ASC LIMIT 0, $limit";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'grupo_id'})) {
            return $this->db->data;
        }
    }

    //método busca no admin
    public function getBuscaCad($termos) {
        $termos = addslashes(Filter::trim_str($termos));
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) ";
        $this->db->query .= "WHERE ";
        $this->db->query .= "cliente_empresa  like'%$termos%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_nome  like'%$termos%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_meta_keywords  like'%$termos%' AND cliente_status = 2 OR ";
        //echo $this->db->query;exit;
        $this->db->query .= "ORDER BY  cliente_tipo DESC, cliente_empresa ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        }
    }

    //método busca
    public function getBusca($termos) {
        $termos = addslashes(Filter::trim_str($termos));
        $likes = explode(" ", $termos);
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id) ";
        $this->db->query .= "WHERE ";
        $this->db->query .= "cliente_empresa  like'%$termos%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_nome  like'%$termos%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_meta_keywords  like'%$termos%' AND cliente_status = 2 OR ";
        if (isset($likes[1])) {
            foreach ($likes as $l) {
                //$l = addslashes($l);
                $this->db->query .= "cliente_empresa  like'%$l%' AND cliente_status = 2 OR ";
                $this->db->query .= "cliente_nome  like'%$l%' AND cliente_status = 2 OR ";
                $this->db->query .= "cliente_meta_keywords  like'%$l%' AND cliente_status = 2 OR ";
                $this->db->query .= "grupo_nome like'%$l%' AND cliente_status = 2 OR ";
            }
        }
        $this->db->query .= "grupo_nome like'%$termos%' AND cliente_status = 2 ";

        //echo $this->db->query;exit;
        $this->db->query .= "ORDER BY  cliente_tipo DESC, cliente_empresa ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        } else {
            //echo $this->db->query;exit;
        }
    }

    //método busca direta
    public function getBuscaFree($negocio, $cidade) {
        $negocio = addslashes($negocio);
        $cidade = addslashes($cidade);
        $this->db->query = "SELECT * FROM cliente INNER JOIN grupo ON (cliente_grupo = grupo_id)  ";
        $this->db->query .= "WHERE ";
        $this->db->query .= "cliente_meta_keywords like'%$negocio em $cidade%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_empresa like'%$negocio%' AND cliente_cidade like'%$cidade%' AND cliente_status = 2 OR ";
        $this->db->query .= "grupo_nome like'%$negocio%' AND cliente_cidade like'%$cidade%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_meta_keywords like'%$negocio%' AND cliente_cidade like'%$cidade%' AND cliente_status = 2 OR ";
        $this->db->query .= "cliente_nome like'%$negocio%' AND cliente_cidade like'%$cidade%' AND cliente_status = 2 ";
        $this->db->query .= "ORDER BY  cliente_tipo DESC, cliente_empresa ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'cliente_id'})) {
            return $this->db->data;
        } else {
            //echo $this->db->query;exit;
        }
    }

    //método inclui ou atualiza cliente
    public function atualizar() {
        //faz upload da logo
        if (!empty($_FILES['filedata']['name'])) {
            $dir_dest = 'midia/cliente/';
            $file = $_FILES['filedata'];
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                $handle->image_convert = 'jpg';
                $handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->Process($dir_dest);
                if ($handle->processed) {
                    $this->cliente_logo = $handle->file_dst_name;
                } else {
                    $this->cliente_logo = false;
                }
                Post::addIndex('cliente_logo', $this->cliente_logo);
            }
        }
        if (!$this->cliente_logo) {
            Post::removeIndex('cliente_logo');
        }

        $this->cliente_id = Filter:: parse_int(Post:: get('cliente_id'));
        $this->cliente_nome = Filter:: parse_string(Post:: get('cliente_nome'));
        $this->cliente_empresa = Filter:: parse_string(Post:: get('cliente_empresa'));
        $this->cliente_senha = Filter:: parse_string(Post:: get('cliente_senha'));

        if ($this->cliente_senha == "") {
            Post::removeIndex('cliente_senha');
        } else {
            Post::changeIndex('cliente_senha', md5(Filter:: parse_string(Post:: get('cliente_senha'))));
        }
        if ($this->cliente_nome != "") {
            $this->cliente_url = Filter:: slug("$this->cliente_empresa $this->cliente_nome");
        } else {
            $this->cliente_url = Filter:: slug("$this->cliente_empresa");
        }

        $this->cliente_endereco = Filter:: parse_string(Post:: get('cliente_endereco')) . ", ";
        $this->cliente_endereco .= Filter:: parse_string(Post:: get('cliente_num')) . " - ";
        $this->cliente_endereco .= Filter:: parse_string(Post:: get('cliente_bairro')) . " - ";
        $this->cliente_endereco .= Filter:: parse_string(Post:: get('cliente_cidade')) . " - ";
        $this->cliente_endereco .= Filter:: parse_string(Post:: get('cliente_uf'));
        $lat_lon = Geo::getLatLon($this->cliente_endereco);
        if ($lat_lon['json'] != "") {
            $this->cliente_lat_lon = $lat_lon['json'];
            Post::addIndex('cliente_lat_lon', $this->cliente_lat_lon);
        }
        

        Post::addIndex('cliente_url', $this->cliente_url);
        if (Post::get('cliente_site')) {
            Post::changeIndex('cliente_site', Filter::parse_link(Post::get('cliente_site')));
        }
        if (Post::get('cliente_info')) {
            //Post::changeIndex('cliente_info', nl2br(Post::get('cliente_info')));
            //Post::changeIndex('cliente_info', Filter::nl2br(Post::get('cliente_info')));
        }
        Post::removeIndex('cliente_info_x');
        if ($this->cliente_id <= 0) {
            Post::removeIndex('cliente_id');
            $post = Post:: getAllObj();
            $post->sql_insert = ($post->sql_insert);
            $sql = "INSERT INTO cliente $post->sql_insert;";
            $this->cliente_id = $this->db->last_insert_id();
			//envia e-mail
			@$this->notificaStatus($this->cliente_email,$this->cliente_url);
			@$this->notificaCadastro(Post:: get('cliente_empresa'));
        } else {
            Post::removeIndex('cliente_id');
            $post = Post:: getAllObj();
            $post->sql_update = ($post->sql_update);
            $sql = "UPDATE cliente SET $post->sql_update WHERE cliente_id = $this->cliente_id;";
        }
        $this->db->query($sql);
    }

    //remove cliente via $.post jquery
    public function remover() {
        $this->cliente_id = Filter::parse_int(Post::get('cliente_id'));
        $this->getCliente();
        if ($this->cliente_logo != "") {
            if (file_exists("./midia/cliente/$this->cliente_logo")) {
                unlink("./midia/cliente/$this->cliente_logo");
            }
        }
        $this->db->query = "DELETE FROM cliente WHERE cliente_id = $this->cliente_id";
        $this->db->query();
    }

    public function get2Menu($nums = 10) {
        $this->db->paginate($nums);
        $this->db->query = "SELECT grupo_url,grupo_nome FROM grupo ORDER BY grupo_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método atualiza exibicao sim/nao do cliente
    public function status() {
        $this->cliente_id = Filter:: parse_int(Post:: getAndRemoveIndex('cliente_id'));
        $this->cliente_status = Filter:: parse_int(Post:: getAndRemoveIndex('cliente_status'));
        $this->cliente_url = Post:: getAndRemoveIndex('cliente_url');
        $this->cliente_email = Post:: getAndRemoveIndex('cliente_email');
        Post:: addIndex('cliente_status', $this->cliente_status);
        $post = Post::getAllObj();
        $this->db->query = "UPDATE cliente SET $post->sql_update WHERE cliente_id = $this->cliente_id;";
        $this->db->query();
        echo $this->cliente_status;
        @$this->notificaStatus($this->cliente_email,$this->cliente_url);
    }

    //método notifica alteração de status
    public function notificaStatus($email,$url) {
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
        $mail->Subject = utf8_decode("O status de seu cadastro foi alterado!");
        //$mail->AddBCC("$m->smtp_bcc");
        $mail->AddAddress("$email");
        //$mail->AddBCC("$msg->contato_email");
        //$mail->AddReplyTo("$msg->contato_email");        
        $mail->Body = utf8_decode("<p>Olá, seu cadastro foi atualizado em nosso site: <br> Acesse em: $url</p>");
        if (@$mail->Send()) {
            return true;
        } else {
            //echo $mail->ErrorInfo;exit;
            return false;
        }
    }    

    public function notificaCadastro($info) {
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
        $mail->Subject = utf8_decode("Nova empresa cadastrada [$info]!");
        //$mail->AddBCC("$m->smtp_bcc");
        if($m->smtp_user != ""){
            $mail->AddAddress("$m->smtp_user");
            //$mail->AddBCC("$msg->contato_email");
            //$mail->AddReplyTo("$msg->contato_email");        
            $mail->Body = utf8_decode("<p>Nova empresa cadastrada [$info] aguardando moderação</p>");
            if (@$mail->Send()) {
                return true;
            } else {
                //echo $mail->ErrorInfo;
                return false;
            }
        }
    }  


}
