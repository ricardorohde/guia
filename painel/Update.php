<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class Update extends UpdateModel {

    public $site;
    public $usuario;
    public $tpl;
    public $registry;
    public $response = array();

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
        
        $sql = "SELECT * FROM versao ORDER BY versao_id DESC";
        $this->db->query($sql);
        $this->db->query()->fetchAllObj();
        $this->versao_id = $this->db->data[0]->{'versao_id'};
        $this->versao_num_info = $this->db->data[0]->{'versao_num_info'};
        $this->versao_num = $this->db->data[0]->{'versao_num'};
    }

    public function indexController() {
        $this->checkup();
        $this->tpl->tpl('admin/update.phtml')->render();
    }

    //método destrutor encerra DB
    public function __destruct() {
        //unset($this->db);
    }

    public function checkup() {
        $server = preg_replace('/www\./', '', $_SERVER['SERVER_NAME']);
        $cURL = curl_init('http://cmsplus.com.br/check/');
        @curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        @curl_setopt($cURL, CURLOPT_POST, 1);
        @curl_setopt($cURL, CURLOPT_POSTFIELDS, array(
                    'server' => $server,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'host' => "$this->baseuri",
                    'versao_num' => $this->versao_num,
                    'versao_num_info' => $this->versao_num_info,
                    'versao_num_id' => $this->versao_num_id
        ));
        @curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        $resultado = @curl_exec($cURL);
        $resposta = @curl_getinfo($cURL, CURLINFO_HTTP_CODE);
        @curl_close($cURL);
        $info = json_decode($resultado);
        if ($info->status == true) {
            $_SESSION['__CMS_UPDATE__'] = $info->versao_num_info;
        }
        if ($info->migrate == true) {
            $_SESSION['__CMS_UPDATE_MIGRATE__'] = true;
        }
        $this->tpl->assign('_ALERT_', $info->alert);
        $this->tpl->assign('_LOG_', $info->log);
        $this->tpl->assign('_INFO_', $info->info);
        $this->tpl->assign('_SHOW_BTN_UPDATE_', ($info->status == 1) ? '' : 'hide');
    }

    public function atualizar() {
        if (isset($_SESSION['__CMS_UPDATE__'])) {
            $versao = $_SESSION['__CMS_UPDATE__'];
            $local = "update.zip";
            $remoto = "/$versao/update.zip";
            $remoto_file = "http://cmsplus.com.br/update/$remoto";
            if (!copy("http://cmsplus.com.br/update/$remoto", $local)) {
                $errors = error_get_last();
                $this->response['erro'][] = $errors['type'] . " -> " . $errors['message'];
                exit;
            }
            /*
              $path = pathinfo(realpath(__FILE__), PATHINFO_DIRNAME);
              $local_file = preg_replace("/painel\//",'',"$path/$local");
              $data = file_get_contents($remoto_file);
              $file = fopen($local_file, 'w+');
              fputs($file, $data);
              fclose($file);
             */
            $path = pathinfo(realpath($local), PATHINFO_DIRNAME);
            @chmod("$path", 0777);
            $zip = new ZipArchive;
            $handle = $zip->open("$path/$local");
            if ($zip->open("$path/$local") === true) {
                $zip->extractTo($path);
                $zip->close();
            } else {
                //echo 'erro ao descompactar....';
                $this->response['erro'][] = 'Erro ao descompactar zip';
                exit;
            }
            @chmod("$local/update", 0777);
            function xcopy($source, $dest, $permissions = 0777) {
                if (is_link($source)) {
                    return symlink(readlink($source), $dest);
                }
                if (is_file($source)) {
                    return copy($source, $dest);
                }
                if (!is_dir($dest)) {
                    mkdir($dest, $permissions);
                }
                $dir = dir($source);
                @chmod("$dest", 0777);
                while (false !== $entry = $dir->read()) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    xcopy("$source/$entry", "$dest/$entry", $permissions);
                    @chmod("$dest/$entry", 0777);
                }
                $dir->close();
                return true;
            }
            xcopy("update", "./");
            function xremove($source, $excludeSvnFolders = true, $recusion = false) {
                $dir_handle = opendir($source);
                if (!$dir_handle)
                    return false;
                while ($file = readdir($dir_handle)) {
                    if ($file == '.' || $file == '..')
                        continue;
                    if ($excludeSvnFolders && $file == '.svn')
                        continue;
                    if (!is_dir($source . '/' . $file)) {
                        unlink($source . '/' . $file);
                    } else {
                        xremove($source . '/' . $file, $excludeSvnFolders, true);
                    }
                }
                closedir($dir_handle);
                if ($recusion) {
                    rmdir($source);
                }
                return true;
            }
            xremove("update");
            rmdir("update");
            unlink($local);
            $_SESSION['__CMS_UPDATE_FINISH__'] = true;
        }
    }

    public function finish() {
        $this->response['migrate'] = "false";
        $this->response['error'] = "false";
        if (isset($_SESSION['__CMS_UPDATE_FINISH__'])) {
            $this->response['finish'] = "true";
            if (isset($_SESSION['__CMS_UPDATE_ERROS__'])) {
                $this->response['error'] = $_SESSION['__CMS_UPDATE_ERROS__'];
            } else {
                if (isset($_SESSION['__CMS_UPDATE_MIGRATE__'])) {
                    $this->response['migrate'] = "true";
                }
            }
        } else {
            $this->response['finish'] = "false";
        }
        echo json_encode($this->response);
        if (isset($_SESSION['__CMS_UPDATE_FINISH__'])) {
            unset($_SESSION['__CMS_UPDATE_FINISH__']);
        }
    }
}
