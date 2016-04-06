<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class MailerModel {

    public $db;
    public $tpl;
    public $smtp_id;
    public $smtp_host;
    public $smtp_user;
    public $smtp_bcc;
    public $smtp_pass;
    public $smtp_port;
    public $smtp_tls;
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

    //método destrutor encerra DB
    public function __destruct() {
        /*
          $this->db->destroy();
          unset($this->db);
          unset($this->registry);
         */
    }

    //método atualiza registro vindo do fomulário
    public function atualizar() {
        Post:: ajax2post('dados');
        if (Post:: get('smtp_pass') == "") {
            Post:: removeIndex('smtp_pass');
        }
        $post = Post::getAllObj();
        $this->db->query = "UPDATE smtp SET $post->sql_update;";
        $this->db->query();
    }

    public function get() {
        $this->db->query = "SELECT * FROM smtp";
        $this->db->query()->fetchAllObj();
        return $this->db->data[0];
    }

    public function enviar($msg) {
        require_once "plugins/phpmailer/class.phpmailer.php";
        $mail = new PHPMailer();
        $mail->SMTPSecure = "tls";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->WordWrap = 80;
        $mail->IsHTML(true);
        $m = $this->get();
        $mail->Port = "$m->smtp_port";
        $mail->Host = "$m->smtp_host";
        $mail->Username = "$m->smtp_user";
        $mail->Password = "$m->smtp_pass";
        $mail->From = "$m->smtp_user";
        $mail->FromName = "$m->smtp_from";
        $mail->Subject = utf8_decode($msg->contato_assunto);
        $mail->AddBCC("$m->smtp_bcc");
        $mail->AddAddress("$m->smtp_user");
        //$mail->AddBCC("$msg->contato_email");
        $mail->AddReplyTo("$msg->contato_email");
        $mensagem = '<html><body>';
        $mensagem .= '<h1 style="font-size:15px;font-family: Helvetica,tahoma,verdana;">' . $msg->contato_assunto . ' - ' . date('d/m/Y H:s') . '</h1>';
        $mensagem .= '<table style="width:95%;border-color: #666;font-family: Helvetica,tahoma,verdana; font-size:12px" cellpadding="10" >';
        $mensagem .= '<tr style="background: #eee;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>Nome:</strong>     ' . $msg->contato_nome . '</td></tr>';
        $mensagem .= '<tr style="background: #fff;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>E-mail:</strong>    ' . $msg->contato_email . '</td></tr>';
        $mensagem .= '<tr style="background: #eee;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>Telefone:</strong> ' . $msg->contato_telefone . '</td></tr>';
        $mensagem .= '<tr style="background: #fff;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>Mensagem:</strong> ' . nl2br($msg->contato_mensagem) . '</td></tr>';
        $mensagem .= '</table>';
        $mensagem .= '</body></html>';

        $mail->Body = utf8_decode($mensagem);
        if (@$mail->Send()) {
            return true;
        } else {
            echo $mail->ErrorInfo;
            return false;
        }
    }

    public function enviar_empresa($msg) {
        require_once "plugins/phpmailer/class.phpmailer.php";
        $mail = new PHPMailer();
        $mail->SMTPSecure = "tls";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->WordWrap = 80;
        $mail->IsHTML(true);
        $m = $this->get();
        $mail->Port = "$m->smtp_port";
        $mail->Host = "$m->smtp_host";
        $mail->Username = "$m->smtp_user";
        $mail->Password = "$m->smtp_pass";
        $mail->From = "$m->smtp_user";
        $mail->FromName = utf8_decode("$m->smtp_from");
        $mail->Subject = utf8_decode($msg->contato_assunto);
        $mail->AddBCC("$m->smtp_bcc");
        $mail->AddAddress("$m->smtp_user");
        //$mail->AddBCC("$msg->contato_email");
        $mail->AddReplyTo("$msg->contato_cliente_email");
        $mensagem = '<html><body>';
        $mensagem .= '<h1 style="font-size:15px;font-family: Helvetica,tahoma,verdana;">' . $msg->contato_assunto . ' - ' . date('d/m/Y H:s') . '</h1>';
        $mensagem .= '<table style="width:95%;border-color: #666;font-family: Helvetica,tahoma,verdana; font-size:12px" cellpadding="10" >';
        $mensagem .= '<tr style="background: #eee;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>Nome:</strong>     ' . $msg->contato_nome . '</td></tr>';
        $mensagem .= '<tr style="background: #fff;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>E-mail:</strong>    ' . $msg->contato_email . '</td></tr>';
        $mensagem .= '<tr style="background: #eee;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>Telefone:</strong> ' . $msg->contato_fone . '</td></tr>';
        $mensagem .= '<tr style="background: #fff;"><td style="font-size:15px;font-family: Helvetica,tahoma,verdana;"><strong>Mensagem:</strong> ' . nl2br($msg->contato_mensagem) . '</td></tr>';
        $mensagem .= '</table>';
        $mensagem .= '</body></html>';

        $mail->Body = utf8_decode($mensagem);
        if (@$mail->Send()) {
            return true;
        } else {
            echo $mail->ErrorInfo;
            return false;
        }
    }

    public function teste() {
        require_once "plugins/phpmailer/class.phpmailer.php";
        $mail = new PHPMailer();
        $mail->SMTPSecure = "tls";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->WordWrap = 80;
        $mail->IsHTML(true);
        $m = $this->get();
        $mail->Port = "$m->smtp_port";
        $mail->Host = "$m->smtp_host";
        $mail->Username = "$m->smtp_user";
        $mail->Password = "$m->smtp_pass";
        $mail->From = "$m->smtp_user";
        $mail->FromName = "$m->smtp_from";
        $mail->Subject = "TESTE CONFIGURACAO SMTP";
        $mail->AddBCC("$m->smtp_bcc");
        $mail->AddAddress("$m->smtp_user");
        $mail->Body = "TESTE CONFIGURACAO SMTP OK";
        if ($mail->Send()) {
            return true;
        } else {
            echo $mail->ErrorInfo;
            return false;
        }
    }

}
