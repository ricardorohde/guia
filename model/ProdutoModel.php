<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class ProdutoModel {

    public $produto_id;
    public $produto_url;
    public $foto_id;
    public $foto_url;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
    public $registry;
    public $condicao = "";

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

    //método retorna todos os produtos 
    public function getAllProduto() {
        //seta a query que será utilizada na consulta
        $this->db->query = "SELECT * FROM produto "
                . "INNER JOIN categoria ON (produto_categoria = categoria_id) $this->condicao "
                . " ORDER BY produto_id DESC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //alias para getAllProduto
    public function getAll() {
        return $this->getProdutoFoto();
    }

    //atualiza foto do produto
    public function fotoUpdate() {
        $this->foto_id = Filter:: parse_int(Post :: get('foto_id'));
        Post :: removeIndex('foto_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->foto_id >= 1) {
            //atualiza foto
            $sql = "UPDATE pfoto SET $post->sql_update WHERE foto_id = $this->foto_id;";
            $this->db->query($sql);
        }
    }

    //remove fotos selecionadas do produto
    public function fotoRemove() {
        if (isset($_POST['lista']) && !empty($_POST['lista'])) {
            $lista = $_POST['lista'];
            foreach ($lista as $foto) {
                //parse_str($post,$_POST['lista']);
                $this->foto_id = $foto['foto_id'];
                $this->foto_url = $foto['foto_url'];
                //verifica se o Id foi informado no post
                if ($this->foto_id >= 1) {
                    //atualiza foto            
                    $sql = "DELETE FROM pfoto WHERE foto_id = $this->foto_id;";
                    $this->db->query($sql);
                    if (file_exists("./midia/foto/$this->foto_url")) {
                        @unlink("./midia/foto/$this->foto_url");
                    }
                }
            }
        }
    }

    //método para upload multiplo de fotos
    public function upload() {
        //diretorio de destino das fotos
        $diretorio = './midia/produto/';
        $file = $_FILES['file'];
        if (!empty($file['name'])) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                $handle->image_convert = 'jpg';
                /*
                  if ($handle->image_src_x > 1300 || $handle->image_y > 1100) {
                  $handle->image_resize = true;
                  $handle->image_ratio_crop = true;
                  $handle->image_x = 1000;
                  $handle->image_y = 900;
                  }
                 */
                $handle->file_new_name_body = md5(uniqid($file['name']));
                $handle->Process($diretorio);
                if ($handle->processed) {
                    $this->foto_url = $handle->file_dst_name;
                    $this->produto_id = Post::get('produto_id');
                    $sql = "INSERT INTO pfoto (foto_produto,foto_url) VALUES ('$this->produto_id','$this->foto_url');";
                    $this->db->query($sql);
                    echo json_encode(array('status' => 'ok'));
                } else {
                    echo json_encode(array('status' => 'error', 'error' => $handle->error));
                }
            } else {
                echo json_encode(array('status' => 'error', 'error' => $handle->error));
            }
        }
    }

    //método atualiza registro vindo do fomulário produto_editar do método editar()
    public function atualizar() {
        Post :: addIndex('produto_url', Filter::slug(Post::get('produto_nome')));
        $this->produto_id = Filter:: parse_int(Post :: get('produto_id'));
        //converte quebra de linha em <br />
        //Post :: changeIndex('produto_descricao', nl2br(Post :: get('produto_descricao')));        
        Post :: removeIndex('produto_id');
        $post = Post::getAllObj();
        //verifica se o Id foi informado no post
        if ($this->produto_id >= 1) {
            //atualiza produto
            $sql = "UPDATE produto SET $post->sql_update WHERE produto_id = $this->produto_id;";
        } else {
            //insere nova produto
            $sql = "INSERT INTO produto $post->sql_insert;";
        }
        $this->db->query($sql);
        //Filter:: printr($post);exit;        
    }

    //remove produto via $.post jquery
    public function remover() {
        $this->produto_id = Filter::parse_int(Post::get('produto_id'));
        $this->db->query = "DELETE FROM produto WHERE produto_id = $this->produto_id";
        $this->db->query();
    }

    //retorna os dados do produto
    public function getProduto($produto_id) {
        $this->produto_id = Filter::parse_int($produto_id);
        $this->db->query = "SELECT * FROM produto LEFT JOIN categoria ON (produto.produto_categoria = categoria.categoria_id) "
                . "WHERE produto_id = $this->produto_id";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0])) {
            //reverte <br> em quebra de linha feito no metodo atualizar
            //$this->db->data[0]->{'produto_descricao'} = strip_tags($this->db->data[0]->{'produto_descricao'});        
            $produto = $this->db->data;
            $fotos = $this->getFotos();
            $this->db->data = $produto;
            $this->db->data['fotos'] = $fotos;
            return $this->db->data;
        } else {
            $this->db->data = 0;
            $this->db->data['fotos'] = 0;
            return $this->db->data;
        }
    }

    //retorna os dados do produto atraves do url-slug
    public function getProdutoBySlug() {
        $this->db->query = "SELECT * FROM produto LEFT JOIN categoria ON (produto.produto_categoria = categoria.categoria_id) "
                . "WHERE produto.produto_ativo = 1 AND produto.produto_show = 1 AND produto.produto_url = '$this->produto_url'";
        $this->db->query()->fetchAllObj();
        //reverte <br> em quebra de linha feito no metodo atualizar
        //$this->db->data[0]->{'produto_descricao'} = strip_tags($this->db->data[0]->{'produto_descricao'});        
        if (isset($this->db->data[0])) {
            $this->produto_id = $this->db->data[0]->{'produto_id'};
            $produto = $this->db->data[0];
            $fotos = $this->getFotos();
            return array('data' => $produto, 'foto' => $fotos);
        } else {
            return array('data' => '', 'foto' => '');
        }
    }

    //retorna os dados do produto atraves do url-slug
    public function getProdutoByCat() {
        $this->categoria_url = Filter::parse_string(Router::getUri(2));
        $this->db->query = "SELECT * FROM produto"
                . " INNER JOIN categoria ON (produto.produto_categoria = categoria.categoria_id) "
                . " LEFT JOIN pfoto ON (produto.produto_id = pfoto.foto_produto) "
                . " WHERE categoria.categoria_url = '$this->categoria_url' AND $this->condicao "
                . " GROUP BY produto.produto_id ORDER BY produto.produto_id DESC, pfoto.foto_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //retorna os dados do produto pelo id
    public function getById($produto_id) {
        $this->produto_id = $produto_id;
        $this->db->query = "SELECT * FROM produto"
                . " INNER JOIN categoria ON (produto.produto_categoria = categoria.categoria_id) "
                . " LEFT JOIN pfoto ON (produto.produto_id = pfoto.foto_produto) "
                . " WHERE produto_id = $this->produto_id "
                . " ORDER BY pfoto.foto_pos ASC";
        $this->db->query()->fetchAllObj();
        //reverte <br> em quebra de linha feito no metodo atualizar
        //$this->db->data[0]->{'produto_descricao'} = strip_tags($this->db->data[0]->{'produto_descricao'});        
        if (isset($this->db->data[0])) {
            $produto = $this->db->data[0];
            $fotos = $this->getFotos();
            return array('data' => $produto, 'foto' => $fotos);
        } else {
            return array('data' => '', 'foto' => '');
        }
    }

    //retorna os dados do produto
    public function getProdutoFoto() {
        //seta a query que será utilizada na consulta
        $this->db->query = "SELECT * FROM produto"
                . " INNER JOIN categoria ON (produto.produto_categoria = categoria.categoria_id) "
                . " LEFT JOIN pfoto ON (produto.produto_id = pfoto.foto_produto) $this->condicao "
                . " AND pfoto.foto_pos = ( SELECT MIN( foto_pos ) FROM pfoto where produto.produto_id = pfoto.foto_produto) "
                . " GROUP BY produto.produto_id ORDER BY produto.produto_id DESC";
        //returna array de obj em db->data os dados da consulta
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function getCategorias() {
        $this->db->query = "SELECT * FROM categoria ORDER BY categoria_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function get2Menu($nums = 10) {
        $this->db->paginate($nums);
        $this->db->query = "SELECT categoria_url,categoria_nome FROM categoria ORDER BY categoria_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function getFotos() {
        $this->db->query = "SELECT * FROM pfoto WHERE foto_produto = $this->produto_id ORDER BY foto_pos ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    public function UpdateFotosPos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['d'] as $pos => $id) {
                $this->db->query = "UPDATE pfoto SET foto_pos = '$pos' WHERE foto_id = $id;";
                $this->db->query();
            }
        }
    }

    public function UpdatePos() {
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            parse_str($item, $arr);
            foreach ($arr['li'] as $pos => $id) {
                $this->db->query = "UPDATE produto SET produto_pos = '$pos' WHERE produto_id = $id;";
                $this->db->query();
            }
        }
    }

}
