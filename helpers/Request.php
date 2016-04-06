<?php

Class Request {

    static function getAll() {
        $sql_update = array();
        $sql_insert = array();
        foreach ($_REQUEST as $key => $input) {
            $_REQUEST[$key] = @addslashes(trim($input));
            //$_REQUEST[$key] = trim($input);
            $sql_update[] = $key . " = '" . $_REQUEST[$key] . "'";
        }
        $sql_update = implode(', ', $sql_update);
        $sql_insert = "(`" . implode("`,`", array_keys($_REQUEST)) . "`) VALUES "
                . "('" . implode("','", array_values($_REQUEST)) . "')";
        return array(
            'fields' => array_keys($_REQUEST),
            'values' => array_values($_REQUEST),
            'sql_update' => $sql_update,
            'sql_insert' => $sql_insert,
            'sql_fields' => "(`" . implode("`,`", array_keys($_REQUEST)) . "`)",
            'sql_values' => "('" . implode("','", array_values($_REQUEST)) . "')",
            'obj' => (object) array_combine(array_keys($_REQUEST), array_values($_REQUEST))
        );
    }

    static function getAllObj() {
        $obj = (object) Post::getAll();
        return $obj;
    }

    static function addIndex($key, $value) {
        $_REQUEST[$key] = trim($value);
    }

    static function removeIndex($key) {
        if (isset($_REQUEST[$key]))
            unset($_REQUEST[$key]);
    }

    static function changeIndex($key, $value) {
        if (isset($_REQUEST[$key]))
            $_REQUEST[$key] = trim($value);
    }

    static function removeBlank($key) {
        foreach ($_REQUEST as $key => $input) {
            $_REQUEST[$key] = trim($input);
            if (empty($_REQUEST[$key]))
                unset($_REQUEST[$key]);
        }
    }

    static function get($key) {
        if (isset($_REQUEST[$key]))
            return ltrim(rtrim(trim($_REQUEST[$key])));
    }

    static function parse($post) {
        parse_str($post, $arr);
        return $arr;
    }

    static function parse2Obj($post) {
        parse_str($post, $arr);
        return (object) $arr;
    }

    static function getAndRemoveIndex($key) {
        $str = "";
        if (isset($_REQUEST[$key])) {
            $str = ltrim(rtrim(trim($_REQUEST[$key])));
            if (isset($_REQUEST[$key])) {
                unset($_REQUEST[$key]);
            }
        }
        return $str;
    }

    static function openUrl($param = array()) {
        try {
            if (empty($param)) {
                throw new Exception('openUrl: Array de parâmetros vazio!');
            } else {
                if (isset($param['method'])) {
                    $method = strtoupper($param['method']);
                } else {
                    throw new Exception('openUrl: Parâmetro method deve ser informado no array de parâmetros!');
                }
                if ($method == 'C') {
                    $url = $param['url'];
                    $buffer = "";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $buffer = trim(curl_exec($ch));
                    if (curl_errno($ch)) {
                        throw new Exception('Curl error: ' . curl_error($ch));
                    } else {
                        $this->buffer = $buffer;
                        return $buffer;
                    }
                    curl_close($ch);
                } elseif ($method == 'F') {
                    $url = $param['url'];
                    $line = "";
                    $buffer = "";
                    $handle = @fopen("$url", "r");
                    if ($handle) {
                        while (!feof($handle)) {
                            $line = trim(@fgets($handle, 4096));
                            if (isset($param['return']) && $param['return'] == 'array') {
                                $buffer[] = explode(",", $line);
                            } else {
                                $buffer .= $line . "\n";
                            }
                        }
                        fclose($handle);
                        $this->buffer = $buffer;
                        return $buffer;
                    }
                } elseif ($method == 'FC') {
                    $url = $param['url'];
                    $buffer = trim(@file_get_contents($url, 0, null));
                    $this->buffer = $buffer;
                    return $buffer;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $this;
    }

}
