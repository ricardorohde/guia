<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class SlideModel {

    public $slide_id;
    public $slide_url = false;
    public $slide_link;
    public $slide_pos;
    public $slide_msg;
    public $slide_local_dir = 'midia/slide/';
    public $login;
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

    //método recupera dados do slide  
    public function getSlide() {
        $this->db->query = "SELECT * FROM slide WHERE slide_id = $this->slide_id";
        $this->db->query()->fetchAll();
        if (isset($this->db->data[0]['slide_id'])) {
            $this->slide_url = $this->db->data[0]['slide_url'];
            $this->slide_titulo = $this->db->data[0]['slide_titulo'];
            $this->slide_msg = $this->db->data[0]['slide_msg'];
            $this->slide_pos = $this->db->data[0]['slide_pos'];
            return $this->db->data[0];
        }
    }

    //método recupera todos os slides
    public function getSlideS() {
        $this->db->query = "SELECT * FROM slide WHERE slide_local = 1 ORDER BY slide_pos ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'slide_id'})) {
            return $this->db->data;
        }
    }

    //método recupera todos os slides
    public function getBannerS() {
        $this->db->query = "SELECT * FROM slide WHERE slide_local <> 1 ORDER BY slide_pos ASC";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0]->{'slide_id'})) {
            return $this->db->data;
        }
    }

    //método upload do slide
    public function upload() {
        $this->slide_local = Filter:: parse_int(Post:: get('slide_local'));
        if ($this->slide_local <> 1) {
            $this->slide_local_dir = 'midia/banner/';
        }
        $dir_dest = $this->slide_local_dir;
        if (isset($_FILES['filedata'])) {
            $file = $_FILES['filedata'];
            if (!empty($file['name'])) {
                $file = $_FILES['filedata'];
                $handle = new Upload($file);
                if ($handle->uploaded) {
                    $handle->file_overwrite = true;
                    $handle->image_convert = 'jpg';
                    /*
                      if ($handle->image_src_x > 1300 || $handle->image_y > 1100) {
                      $handle->image_resize = true;
                      $handle->image_ratio_crop = true;
                      $handle->image_x = 1000;
                      $handle->image_y = 600;
                      }
                     */
                    $handle->file_new_name_body = md5(uniqid($file['name']));
                    $handle->Process($dir_dest);
                    if ($handle->processed) {
                        $this->slide_url = $handle->file_dst_name;
                    } else {
                        $this->slide_url = false;
                        echo $handle->error;
                        exit;
                    }
                    Post::addIndex('slide_url', $this->slide_url);
                }
            }
        }
        if (!$this->slide_url) {
            Post::removeIndex('slide_url');
        }
        $this->slide_id = Filter:: parse_int(Post:: get('slide_id'));
        $this->slide_titulo = Filter:: parse_string(Post:: get('slide_titulo'));
        Post::changeIndex('slide_link', Filter::parse_link(Post::get('slide_link')));
        Post::removeIndex('slide_id');

        if ($this->slide_id <= 0 && !empty($file['name'])) {
            $post = Post:: getAllObj();
            $sql = "INSERT INTO slide $post->sql_insert;";
        } elseif ($this->slide_id >= 1 && !empty($file['name'])) {
            $post = Post:: getAllObj();
            $sql = "UPDATE slide SET $post->sql_update WHERE slide_id = $this->slide_id;";
        } elseif ($this->slide_id >= 1 && $this->slide_titulo != '') {
            $post = Post:: getAllObj();
            $sql = "UPDATE slide SET $post->sql_update WHERE slide_id = $this->slide_id;";
        } elseif ($this->slide_id >= 1 && $this->slide_titulo == '') {
            $post = Post:: getAllObj();
            $sql = "UPDATE slide SET $post->sql_update WHERE slide_id = $this->slide_id;";
        }
        $this->db->query($sql);
    }

    //remove pagina via $.post jquery
    public function remover() {
        $this->slide_id = Filter::parse_int(Post::get('slide_id'));
        $this->getSlide();
        if ($this->slide_url != "") {
            if (file_exists("midia/slide/$this->slide_url")) {
                unlink("midia/slide/$this->slide_url");
            }
            if (file_exists("midia/banner/$this->slide_url")) {
                unlink("midia/banner/$this->slide_url");
            }
        }
        $this->db->query = "DELETE FROM slide WHERE slide_id = $this->slide_id";
        $this->db->query();
    }

    public function UpdatePos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['tr'] as $pos => $id) {
                $sql = "UPDATE slide SET slide_pos = '$pos' WHERE slide_id = $id;";
                $this->db->query($sql);
            }
        }
    }

}
