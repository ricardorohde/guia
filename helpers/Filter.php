<?php

class Filter {

    static function parse_string($str) {
        return addslashes(strip_tags($str));
    }

    static function trim_str($str) {
        return preg_replace('/\s+/', ' ', $str);
    }

    static function trim_rl($str) {
        return ltrim(rtrim($str));
    }

    static function parse_int($str) {
        return intVal($str);
    }

    static function parse_numeric($str) {
        return preg_replace("([[:punct:]]|[[:alpha:]]| )", '', $str);
    }

    static function parse_email($str) {
        return preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $str);
    }

    static function parse_link($url) {
        $regex = array('/http|https\:\/\//', '/www./', '/\:\/\//');
        $link = preg_replace($regex, array('', '', ''), $url);
        return ($link != "") ? "http://" . $link : $link;
    }

    static function parse_cpf($str) {
        return preg_match("preg_match('/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/'", $str);
    }

    static function parse_cnpj($str) {
        return preg_match("#^[0-9]{2}\.[0-9]{3}\.[0-9]{3}/[0-9]{4}-[0-9]{2}$#", $str);
    }

    static function parse_date($str) {
        $str = preg_replace('/\//', '-', $str);
        $str = date('Y-m-d', strtotime($str));
        return $str;
    }

    static function br2nl($string, $separator = PHP_EOL) {
        $separator = in_array($separator, array("\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL)) ? $separator : PHP_EOL;  // Checks if provided $separator is valid.
        return preg_replace('/\<br(\s*)?\/?\>/i', $separator, $string);
    }

    static function nl2br($string, $separator = PHP_EOL) {
        $separator = in_array($separator, array("\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL)) ? $separator : PHP_EOL;  // Checks if provided $separator is valid.
        return preg_replace(array("/\n/", "/\r/", "/\r\n/", "/\n\r/"), array("<br />", "<br />", "<br />", "<br />"), $string);
    }

    static function decode_utf8($key, $data) {
        foreach ($data as $k => $v) {
            $data[$k][$key] = utf8_decode($data[$k][$key]);
        }
        return $data;
    }

    static function printr($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    static function pre($data) {
        echo "<pre>", @print_r($data, true), "</pre>";
    }

    static function moedaArr($key, $data) {
        foreach ($data as $k => $v) {
            $data[$k][$key] = number_format($data[$k][$key], 2, ',', '.');
        }
        return $data;
    }

    static function moeda($valor, $moeda = 'brl', $mostrar_zero = false) {
        return $valor ? number_format($valor, 2, ',', '.') : ($mostrar_zero ? '0,00' : '');
    }

    static function caracteresEsquerda($string, $num) {
        return substr($string, 0, $num);
    }

    static function caracteresDireita($string, $num) {
        return substr($string, strlen($string) - $num, $num);
    }

    static function memoryHuman($size) {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
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

        if ($reverse != null) {
            $replace = array('-', ' ', '');
        }
        $replaced = str_replace($group_a, $group_b, $key);
        return strtolower(Filter:: parse_string(preg_replace($pattern, $replace, $replaced)));
    }

    public function cut($str, $chars, $info) {
        try {
            $str = strip_tags($str);
            if (strlen($str) >= $chars) {
                $str = preg_replace('/\s\s+/', ' ', $str);
                $str = preg_replace('/\s\s+/', ' ', $str);
                $str = substr($str, 0, $chars);
                $str = preg_replace('/\s\s+/', ' ', $str);
                $arr = explode(' ', $str);
                array_pop($arr);
                $final = implode(' ', $arr) . $info;
            } else {
                $final = $str;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $final;
    }

    function genpass($width = 8) {
        $chars = 'abcdefghijlmnopqrstuvxwzABCDEFGHIJLMNOPQRSTUVXYWZ0123456789';
        $max = strlen($chars) - 1;
        $pass = "";
        for ($i = 0; $i < $width; $i++) {
            $pass .= $chars{mt_rand(0, $max)};
        }
        return $pass;
    }

}
