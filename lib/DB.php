<?php

class DB {

    private $database;
    public $query;
    public $data;
    public $data_all;
    public $result;
    public $rows;
    public $page = 0;
    public $perpage = 10;
    public $current = 1;
    public $url = null;
    public $pages = '';
    public $total = '';
    public $pagination = false;
    private $host;
    private $port;
    private $user;
    private $pass;
    public $dbname;
    public $link;

    public function __construct() {
        try {
            //echo "construct DB \n";
            #array com dados do banco
            require_once 'config/database.conf.php';
            $this->database = $database;
            # Recupera os dados de conexao do config
            $this->dbname = $this->database['dbname'];
            $this->host = $this->database['host'];
            $this->port = $this->database['port'];
            $this->user = $this->database['user'];
            $this->pass = $this->database['password'];


            # instancia e retorna objeto
            $this->link = mysqli_connect("$this->host", "$this->user", "$this->pass", "$this->dbname") or die(@mysqli_connect_error($this->link));
            mysql_query("SET NAMES 'utf8'");
            mysql_query('SET character_set_connection=utf8');
            mysql_query('SET character_set_client=utf8');
            mysql_query('SET character_set_results=utf8');

            $this->url = $_SERVER['SCRIPT_NAME'];
            return $this->link;
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function query($query = '') {
        try {
            if ($query != '') {
                $this->query = $query;
            }
            if (is_null($this->query)) {
                throw new Exception('mysqli_query: A query deve ser informada como parâmetro do método.');
            } else {
                if ($this->pagination == true) {
                    $this->result = mysqli_query($this->link, $this->query);
                    $this->rows = mysqli_num_rows($this->result);
                    $this->paginateLink();
                    $this->query .= " LIMIT $this->page, $this->perpage";
                    $this->pagination = false;
                }
                $this->result = mysqli_query($this->link, $this->query);
                if (!$this->result) {
                    throw new Exception(mysqli_error($this->link));
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    public function find($c = null) {
        try {
            $this->data = "";
            $this->rows = 0;
            if (empty($c)) {
                throw new Exception('');
            }
            $table = (is_array($c) && isset($c[0])) ? $c[0] : $c;
            $this->query = "SELECT * FROM " . $table;
            if (is_array($c) && isset($c[1])) {
                $this->query .= " WHERE " . $c[1];
            }
            $this->query($this->query);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this->fetch(true);
    }

    public function fetch($type = false) {
        $this->data = ($type) ? mysqli_fetch_object($this->result) : mysqli_fetch_array($this->result, MYSQL_ASSOC);
        $this->rows = mysqli_num_rows($this->result);
        return $this->data;
    }

    public function fetchAll($type = false) {
        $this->data_all = "";
        $this->rows = 0;
        while ($row = $this->fetch($type)) {
            $this->data_all[] = $row;
        }
        $this->rows = mysqli_num_rows($this->result);
        mysqli_free_result($this->result);
        $this->data = $this->data_all;
        return $this->data;
    }

    public function fetchAllObj() {
        return $this->fetchAll(true);
    }

    public function last_insert_id() {
        return mysqli_insert_id($this->link);
    }

    public function rowCount() {
        return @mysqli_affected_rows($this->result);
    }

    public function numRows() {
        return $this->rows;
    }

    public function paginate($perpage) {
        $this->pagination = true;
        $this->perpage = $perpage;
        return $this;
    }

    protected function paginateLink() {
        if ($this->url != null) {
            $this->url .= "/";
        }
        $this->route = explode("/", $_SERVER['REQUEST_URI']);
        if (in_array('page', $this->route)) {
            $pagenum = explode("page", $_SERVER['REQUEST_URI']);
            $this->pagenum = preg_replace('/\//', '', end($pagenum));
            $this->current = $this->pagenum;
            $this->page = $this->perpage * $this->pagenum - $this->perpage;
            if ($this->pagenum == 1) {
                $this->page = 0;
            }
        }
        if ($this->rows > $this->perpage) {
            $this->pages = "<ul class=\"pagination\">\n";
            //primeira
            $this->pages .= "<li><a href=\"" . $this->url . "page/1/\" class=\"tip\" title=\"primeira\">&laquo;&laquo;</a></li>\n";
            $prox = "javascript:void(0)";
            $ant = "javascript:void(0)";
            if ($this->current >= 2) {
                $ant = $this->url . "page/" . ($this->current - 1) . "/";
            }
            if ($this->current >= 1 && $this->current < ($this->rows / $this->perpage)) {
                $prox = $this->url . "page/" . ($this->current + 1) . "/";
            }
            $this->pages .= "<li><a href=\"$ant\" class=\"tip\" title=\"anterior\">&laquo;</a></li>\n";
            $from = ceil($this->rows / $this->perpage);
            if ($from == 1) {
                $from++;
            }

            //$this->pages .= "<li><a href=\"javascript:void(0);\"><span class=\"tip\" title=\"página atual $this->current\"> $this->current de $from</span></a></li>\n";
            for ($i = 1; $i <= $from; $i++) {
                if ($this->current == $i) {
                    $this->pages .= "<li class=\"active\"><a>$i</a></li>\n";
                } else {
                    $this->pages .= "<li><a href=\"" . $this->url . "page/$i/\">$i</a></li>\n";
                    //$this->salt .= "<li><a href=\"" . $this->url . "page/$i/\">$i</a></li>\n";
                }
            }
            $this->pages .= "<li><a href=\"$prox\" class=\"tip\" title=\"próxima\">&raquo;</a></li>\n";
            $this->pages .= "<li><a href=\"" . $this->url . "page/$from/\" class=\"tip\" title=\"última\">&raquo;&raquo;</a></li>\n";
            $this->pages .= "</ul>\n";
            $this->pages .= "\n";
        }

        $this->paginacao = $this->pages;
        return $this;
    }

    public function __destruct() {
        @mysqli_close($this->link);
        if ($this->link == true) {
            @mysqli_close($this->link);
            unset($this->link);
        }
    }

    public function destroy() {
        if ($this->link == true) {
            @mysqli_close($this->link);
            unset($this->link);
        }
    }

    public function limit($limit, $offset) {
        return "LIMIT " . (int) $limit . "," . (int) $offset;
    }

    public function map($arr = array()) {
        try {
            if (!is_array($arr) && empty($arr)) {
                throw new Exception('ArrayMapNull');
            } else {
                foreach ($arr as $k => $v) {
                    if (!isset($this->$k)) {
                        $this->$k = "";
                    }
                    $this->$k = $v;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    public function factory($arr = array()) {
        try {
            if (!is_array($arr) && empty($arr)) {
                throw new Exception('ArrayFactoryNull');
            } else {
                $arr = $this->find($arr);
                if (isset($arr[0])) {
                    $this->map($arr[0]);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    /**
     * Utilizado para função preg_replace
     * @name preg
     * @param String $key
     * @param String $pattern
     * @param String $replace
     * @example $obj->preg('/./','-','key_data_cad');
     *
     */
    public function preg($pattern, $replace, $key) {
        try {
            if (!empty($this->data)) {
                foreach ($this->data as $idx => $item) {
                    if (isset($item[trim($key)])) {
                        if (strlen($this->data[$idx][trim($key)]) <= 0) {
                            $this->data[$idx][trim($key)] = "NULL";
                        }
                        $this->data[$idx][trim($key)] = preg_replace($pattern, $replace, $this->data[$idx][trim($key)]);
                    }
                }
            } else {
                $this->response = "preg: O array de origem está vazio.";
                //throw  new Exception("preg: O array de origem está vazio.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    /**
     * Utilizado para cortar uma string do array ...
     * @name cut
     * @param String $key
     * @param int $chars
     * @example $obj->cut('name',40);
     *
     */
    public function cut($key, $chars, $info) {
        try {
            if (!empty($this->data)) {
                foreach ($this->data as $idx => $item) {
                    if (isset($item[trim($key)])) {
                        $str = $item[trim($key)];
                        if (strlen($str) >= $chars) {
                            $str = preg_replace('/\s\s+/', ' ', $str);
                            $str = strip_tags($str);
                            $str = preg_replace('/\s\s+/', ' ', $str);
                            $str = substr($str, 0, $chars);
                            $str = preg_replace('/\s\s+/', ' ', $str);
                            $arr = explode(' ', $str);
                            array_pop($arr);
                            $final = implode(' ', $arr) . $info;
                        } else {
                            $final = $str;
                        }
                        $this->data[$idx][trim($key)] = strip_tags($final);
                    }
                }
            } else {
                //throw  new Exception("cut: O array de origem está vazio.");
                $this->response = "cut: O array de origem está vazio.";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    /**
     * Incrementa determinado campo da tabela
     *
     * @name increment
     * @param String $table Nome da tabela
     * @param String $field Nome do campo
     * @param Int $value valor a ser incrementado
     * @example $obj->increment('visitas','count',1,'id = 1');
     */
    public function increment($table = null, $field = null, $value = null, $cond = null) {
        try {
            if ($table == null || $field == null || $value == null) {
                throw new Exception('increment: O nome da tabela,campo e valor devem ser informados!');
            } else {
                $this->query = "UPDATE $table SET $field = $field+$value where $cond";
                $this->query($this->query);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    /**
     * Decrementa determinado campo da tabela
     *
     * @name decrement
     * @param String $table Nome da tabela
     * @param String $field Nome do campo
     * @param Int $value valor a ser incrementado
     * @example $obj->decrement('visitas','count',1,'id=1');
     */
    public function decrement($table = null, $field = null, $value = null, $cond = null) {
        try {
            if ($table == null || $field == null || $value == null) {
                throw new Exception('decrement: O nome da tabela,campo e valor devem ser informados!');
            } else {
                $this->query = "UPDATE $table SET $field = $field-$value where $cond";
                $this->query($this->query);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

    public function toJson($param = null, $nokey = null) {
        try {
            $jarray = array();
            if ($param == null && empty($this->data[0])) {
                //throw new Exception( 'toJson: array vazio' );
                $this->jsonData = '-1';
            } else {
                if ($param == null) {
                    $param = $this->data;
                }
                $json = "";
                if ($nokey == null) {
                    $json .= "{ \"rs\" : [";
                } else {
                    $json .= "[";
                }
                foreach ($param as $p) {
                    if ($nokey == null) {
                        $json .= "{";
                    }
                    foreach ($p as $k => $v) {
                        $v = utf8_encode($v);
                        if ($nokey != null) {
                            $json .= "\"$v\",";
                        } else {
                            $json .= "\"$k\":\"$v\",";
                        }
                    }
                    if ($nokey == null) {
                        $json .= "},";
                    }
                }
                $json = substr_replace($json, '', -1, 1);
                $json = preg_replace('/,}/', '}', $json);
                if ($nokey == null) {
                    $json .= "]}";
                } else {
                    $json .= "]";
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        $this->jsonData = $json;
        return $this->jsonData;
    }

    public function slug($key, $nkey = null, $reverse = null) {
        $group_a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç',
            'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú',
            'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä',
            'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í',
            'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø',
            'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A',
            'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E',
            'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g',
            'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
            'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L',
            'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l',
            'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o',
            'O', 'o', 'O', 'o', '?', '?', 'R', 'r', 'R',
            'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's',
            '?', '?', 'T', 't', 'T', 't', 'T', 't', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', '?', 'Z', 'z',
            'Z', 'z', '?', '?', '?', '?', 'O', 'o', 'U',
            'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?',
            '?', '?', '?', '?', '?');
        $group_b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C',
            'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D',
            'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U',
            'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a',
            'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i',
            'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A',
            'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E',
            'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g',
            'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
            'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', '', '', 'J', 'j', 'K', 'k', 'L',
            'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l',
            'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
            'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R',
            'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's',
            'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z',
            'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U',
            'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A',
            'a', 'AE', 'ae', 'O', 'o');

        $pattern = array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/');
        $replace = array(' ', '-', '');
        try {
            if ($reverse != null) {
                $replace = array('-', '-', '');
            }
            if (!empty($this->data)) {
                foreach ($this->data as $idx => $item) {
                    if (isset($item[trim($key)])) {
                        $replaced = str_replace($group_a, $group_b, $this->data[$idx][trim($key)]);
                        if ($nkey == null) {
                            $this->data[$idx]["$key"] = strtolower(preg_replace($pattern, $replace, $replaced));
                        } else {
                            $this->data[$idx]["$nkey"] = strtolower(preg_replace($pattern, $replace, $replaced));
                        }
                    }
                }
            } else {
                $this->response = "urlmode: O array de origem está vazio.";
                //throw  new Exception("urlmod: O array de origem está vazio.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * Utilizado para clonar ou concatenar 1 ou mais indices
     * @name clonekey
     * @param String $new
     * @param String $separator
     * @param Array $keys
     * @example $obj->clonekey('new key',array('key a','key b'));
     * @example $obj->clonekey('new key',array('key a','key b'),' - ');
     * @example $obj->clonekey('new key',array('key a'));
     * @example $obj->clonekey('new key',array('New Value'));
     *
     */
    public function clonekey($new, $keys, $sep = " ") {
        try {
            if (!empty($this->data)) {
                foreach ($this->data as $idx => $item) {
                    $t = array();
                    foreach ($keys as $key) {
                        if (isset($this->data[$idx][$key])) {
                            $t[] = $this->data[$idx][$key];
                        } else {
                            $t[] = $key;
                        }
                    }
                    $this->data[$idx][$new] = implode($sep, $t);
                }
            } else {
                $this->response = "clonekey: O array de origem está vazio.";
                //throw  new Exception("concat: O array de origem está vazio.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

}

/* end file */
