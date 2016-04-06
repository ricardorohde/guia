<?php

class Imagem {

    static function get($dir, $file) {
        $filepath = "$dir/$file";
        $filepath = (!is_dir($filepath)) ? $filepath : "$dir/nopic.gif";
        return (file_exists("$filepath")) ? $filepath : "$dir/nopic.gif";
    }

}
