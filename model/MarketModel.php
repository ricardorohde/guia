<?php

class MarketModel {

    public $db;
    public $registry;
    public $ativos = array();
    public $inativos = array();
    public $perpage;
    public $modulo;
    public $config;

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
        $this->baseuri = Router::base();
    }

    //método destrutor 
    public function __destruct() {
        
    }

    public function getMod($mod_hash) {
        $pacote_dir = "modulos/$mod_hash";
        $pacote_file = "$mod_hash.zip";
        $pacote_remoto = "http://cmsplus.com.br/mplace/$pacote_file";
        if (!copy("$pacote_remoto", "$pacote_file")) {
            $errors = error_get_last();
            $this->response['erro'][] = $errors['type'] . " -> " . $errors['message'];
            echo $errors['type'] . " -> " . $errors['message'];
            exit;
        }
        $path = pathinfo(realpath($pacote_file), PATHINFO_DIRNAME);
        //echo "<br>path... $path";
        //echo "<br>dir ... $pacote_dir";
        //echo "<br>file...  $pacote_file";
        //echo "<br>zip...  $pacote_zip";
        //echo "<br>ext...  $path/$mod_hash";
        //@chmod("$path", 0777);
        @chmod("$pacote_zip", 0755);
        @chmod("$path/modulos/", 0755);
        $zip = new ZipArchive;
        $handle = $zip->open("$path/$pacote_file");
        if ($handle === true) {
            $zip->extractTo("$path/modulos/");
            $zip->close();
        } else {
            $this->response['erro'][] = 'Erro ao descompactar zip';
            echo 'Erro ao descompactar zip';
            exit;
        }
        function chmod_r($path) {
            $dir = new DirectoryIterator($path);
            foreach ($dir as $item) {
                @chmod($item->getPathname(), 0755);
                if ($item->isDir() && !$item->isDot()) {
                    @chmod_r($item->getPathname());
                }
            }
        }
        function xcopy($source, $dest, $permissions = 0755) {
            @chmod("$source", 0755);
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
            @chmod("$dest", 0755);
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                xcopy("$source/$entry", "$dest/$entry", $permissions);
                @chmod("$dest/$entry", 0755);
            }
            $dir->close();
            return true;
        }
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
        //xcopy("$path/temp/", "$path/modulos");
        chmod_r("$path/modulos/$mod_hash");
        //xremove("$path/temp/");
        //rmdir("$path/temp/");
        unlink($pacote_file);
        $_SESSION['__CMS_MODULE_INSTALL_FINISH__'] = true;
    }

    public function finish() {
        $this->response['error'] = "false";
        if (isset($_SESSION['__CMS_MODULE_INSTALL_FINISH__'])) {
            $this->response['finish'] = "true";
            if (isset($_SESSION['__CMS_MODULE_INSTALL_ERROS__'])) {
                $this->response['error'] = $_SESSION['__CMS_MODULE_INSTALL_ERROS__'];
            }
        } else {
            $this->response['finish'] = "false";
        }
        //echo json_encode($this->response);
        if (isset($_SESSION['__CMS_MODULE_INSTALL_FINISH__'])) {
            unset($_SESSION['__CMS_MODULE_INSTALL_FINISH__']);
        }
        return true;
    }

}
