<?php

/**
 * TemplateFy
 *
 * @author Rafael Clares <rafadinix@gmail.com>
 * @version 1.0  <10/2010>
 * web: www.clares.wordpress.com
 *
 *  Utilizacao de templates para separar camadas (MVC)
 *
 */
class Template {

    public $tpl;
    public $contents = null;
    public $target;
    public $html;
    public $dataArr;
    public $site = null;
    public $dataview = array();
    public $baseuri;
    public $tpldata = array();
    public $assigndata = array();
    public $tpldir = 'views/';
    public $local = 'public';
    public $compressed = false;
    public $menu_mod;

    public function __construct() {
        $this->baseuri = Router::base();
        $this->assign('BASEURI', $this->baseuri);
        $this->assign('SITE_BASEURI', $this->baseuri);
    }

    public function local($local) {
        $this->local = $local;
    }

    public function site($site) {
        $this->site = $site;
        $this->assign('MENU_ATIVO', 'index')
                ->assign('BASEURI', Router::base())
                ->assign('SITE_LOGO', $this->site->getLogo())
                ->assign('SITE_LOGO_Y', $this->site->getLogoY())
                ->assign('SITE_LOGO_X', $this->site->getLogoX())
                ->assign('SITE_TITLE', $this->site->getTitle())
                ->assign('SITE_KEYWORDS', $this->site->getMetaKeyWords())
                ->assign('SITE_DESCRIPTION', $this->site->getMetaDescription())
                ->assign('SITE_AUTOR', $this->site->getMetaAuthor())
                ->assign('SITE_GA', $this->site->getGoogleAnalytics())
                ->assign('SITE_SLOGAN', $this->site->getSlogan())
                ->assign('SITE_TEMPLATE', $this->baseuri . "/$this->tpldir{$this->local}/" . $this->site->getTemplate())
                ->assign('SITE_TEMPLATE_BOX', $this->site->getTemplateBox())
                ->assign('SITE_TEMPLATE_SKIN', $this->site->getTemplateSkin())
                ->assign('SITE_TEMPLATE_DIR', $this->site->getTemplateDir());
        $this->tpldir = "$this->tpldir/$this->local/" . $this->site->getTemplate() . "/";
    }

    public function setTplDir($tpldir) {
        $this->tpldir = $tpldir;
    }

    public function assign($key = null, $value = null) {
        $this->assigndata[$key] = trim($value);
        $this->tpldata[] = $this->assigndata;
        return $this;
    }

    public function append($key = null, $data = null) {
        if (!isset($this->data[$key])) {
            $this->data[$key] = $data;
        }
        return $this;
    }

    public function addMod($key = null, $data = null) {
        if (!isset($this->modulo[$key])) {
            $this->modulo[$key] = $data;
        }
        return $this;
    }

    public function addOn($key = null, $data = null) {
        $this->$key = $data;
        return $this;
    }

    public function loadMod($key) {
        if (isset($this->modulo[$key])) {
            return $this->modulo[$key];
        }
    }

    public function isMod($key) {
        if (isset($this->modulo[$key])) {
            return true;
        } else {
            return false;
        }
    }

    public function menuMod() {
        if (!empty($this->menu_mod))
            foreach ($this->menu_mod as $menu) {
                if (file_exists($menu)) {
                    require_once $menu;
                }
            }
    }

    public function assignAll($dataArr) {
        try {
            foreach ($dataArr as $data) {
                foreach ($data as $key => $value) {
                    $this->assigndata[$key] = trim($value);
                }
                $this->tpldata[] = $this->assigndata;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    public function tpl($tpl) {
        $this->tpl = $tpl;
        ob_start();
        $this->path_views = str_replace("/admin", "", pathinfo(realpath($this->tpldir . $this->tpl), PATHINFO_DIRNAME));
        $this->path_views = str_replace("/public", "", $this->path_views);
        require_once($this->tpldir . $this->tpl);
        $this->contents = ob_get_contents();
        ob_end_clean();
        return $this;
    }

    public function dataview($key = null, $value = null) {
        $this->dataview["$key"] = $value;
        return $this;
    }

    public function render($printable = null) {
        foreach ($this->tpldata as $item) {
            while (list( $key, $value ) = each($item)) {
                $pat = array('/\{' . $key . '\}/i', '/\[' . $key . '\]/i');
                $rep = array($value, $value);
                $this->contents = preg_replace($pat, $rep, $this->contents);
            }
        }
        $this->clear();
        if ($this->compressed == true) {
            $this->compress();
        }
        if ($printable == null) {
            echo $this->contents;
            exit;
        }
        return $this->contents;
    }

    public function fetch($target, $data) {
        $this->html = array();
        $this->target = $target;
        if (!empty($data)) {
            if (preg_match_all("/(\{$this->target\})(.*?)\s*(\{\/$this->target\})/s", $this->contents, $loop, PREG_SET_ORDER)) {
                foreach ($data as $item) {
                    $html = $loop[0][2];
                    while (list( $key, $value ) = each($item)) {
                        if (preg_match_all("/$this->target.$key/msi", $html, $m)) {
                            if (!is_array($value)) {
                                $pattern = array("/\{(?<!\.)\b$this->target.$key\b(?!\.)\}/");
                                $replace = array("$value");
                                $html = preg_replace($pattern, $replace, $html);
                            }
                        }
                        if (preg_match_all("/(\{$key\})(.*?)\s*(\{\/$key\})/s", $html, $iloop, PREG_SET_ORDER)) {
                            if (is_array($item[$key]) && !empty($item[$key])) {
                                $iloop_html = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $iloop[0][2]);
                                $ihtml = $this->ifetch($item[$key], $key, $iloop_html);
                                $html = preg_replace("/(\{$key\})(.*?)\s*(\{\/$key\})/s", "$ihtml", $html);
                            }
                        }
                    }
                    $this->html[] = $html;
                }
                $this->html = implode("\r", $this->html);
                $this->contents = preg_replace("/(\{$this->target\})(.*?)\s*(\{\/$this->target\})/msi", $this->html, $this->contents);
            }
        } else {
            $this->contents = preg_replace("/(\{$this->target\})(.*?)\s*(\{\/$this->target\})/msi", "", $this->contents);
        }
        return $this;
    }

    //inner loop
    private function ifetch($data, $target, $html) {
        $patt = array();
        $rep = array();
        $ihtml = "";
        $aux = array();
        foreach ($data as $item) {
            $patt = array();
            $rep = array();
            foreach ($item as $key => $value) {
                if (preg_match_all("/$target.$key/", $html, $m)) {
                    $patt[] = "/\{$target.$key\}/";
                    $rep[] = "$value";
                }
            }
            $aux[] = array($patt, $rep);
        }
        foreach ($aux as $k) {
            $ihtml .= preg_replace($k[0], $k[1], $html);
        }
        return $ihtml;
    }

    private function clear() {
        //$this->contents = preg_replace( "/\{\s*(.*?)\s*\}/i", "", $this->contents );
        //$this->contents = @preg_replace( array( "/<li[^>]*>([\s]?)*<\/li>/i" ), array( "" ), $this->contents, -1 );
        //$this->contents = @preg_replace( array( "/<a[^>]*>([\s]?)*<\/a>/i" ), array( "" ), $this->contents, -1 );
        //$this->contents = @preg_replace( array( "/<li[^>]*>([\s]?)*<\/li>/i" ), array( "" ), $this->contents, -1 );
        //$this->contents = @preg_replace( array( "/<ul[^>]*>([\s]?)*<\/ul>/i" ), array( "" ), $this->contents, -1 );
        //$this->contents = preg_replace( "/<button[^>]*>([\s]?)*<\/button>/", "", $this->contents );
        //$this->contents = preg_replace( "/<p[^>]*>([\s]?)*<\/p>/", "", $this->contents );
        //$this->contents = preg_replace( "/<td[^>]*>([\s]?)*<\/td>/", "", $this->contents );
        //$this->contents = preg_replace( "/<th[^>]*>([\s]?)*<\/th>/", "", $this->contents );
        //$this->contents = preg_replace( "/<tr[^>]*>([\s]?)*<\/tr>/", "", $this->contents );
        //$this->contents = preg_replace( "/<thead[^>]*>([\s]?)*<\/thead>/", "", $this->contents );
        //$this->contents = preg_replace( "/<tfoot[^>]*>([\s]?)*<\/tfoot>/", "", $this->contents );
        //$this->contents = preg_replace( "/<tbody[^>]*>([\s]?)*<\/tbody>/", "", $this->contents );
        //$this->contents = preg_replace( "/<img [^\.>]*>([\s]?)*/", "", $this->contents );
        //$this->contents = preg_replace( "/\[\s*(.*?)\s*\]/i", "", $this->contents );
        //$this->contents = @preg_replace( "/\[[a-z]\s*(.*?)[a-z]\s*]/i", "", $this->contents );
        $this->contents = @preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $this->contents);
        $this->contents = @preg_replace( '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $this->contents );
    }

    //pouco usada
    public function compress() {
        $this->contents = @preg_replace("/\s+/i", ' ', $this->contents);
    }

    public function baseuri() {
        $this->baseuri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') .
                (isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
                (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
                        (isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] === 443 ||
                        $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) .
                substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
        return $this->baseuri;
    }

}

/* end file */
