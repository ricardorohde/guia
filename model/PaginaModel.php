<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class PaginaModel {

    public $pagina_id;
    public $site;
    public $user;
    public $login;
    public $session;
    public $tpl;
    public $db;
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

    //método recuperar página
    public function getPagina() {
        $this->db->query = "SELECT * FROM pagina LEFT JOIN area ON (pagina_area = area_id) WHERE pagina_id = $this->pagina_id";
        $this->db->query()->fetchAllObj();
        return $this->db->data[0];
    }

    //método recuperar páginas
    public function getAllPagina() {
        $this->db->query = "SELECT * FROM pagina LEFT JOIN area ON (pagina_area = area_id) ORDER BY area_pos ASC, area_nome ASC, pagina_nome ASC";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

    //método recuperar pagina by slug
    public function getPaginaBySlug() {
        $this->db->query = "SELECT * FROM pagina LEFT JOIN area ON (pagina_area = area_id) WHERE pagina_url = '$this->pagina_url'";
        $this->db->query()->fetchAllObj();
        if (isset($this->db->data[0])) {
            $this->pagina_id = $this->db->data[0]->{'pagina_id'};
            return $this->db->data[0];
        }
    }

    //método inclui registro vindo do fomulário pagina_nova do método nova()
    public function incluir() {
        Post::addIndex('pagina_url', Filter::slug(Post::get('pagina_nome')));
        $post = Post::getAllObj();
        //Filter::printr($post);
        $sql = "INSERT INTO pagina $post->sql_insert;";
        $this->db->query($sql);
    }

    //método atualiza registro vindo do fomulário pagina_editar do método editar()
    public function atualizar() {
        Post::addIndex('pagina_url', Filter::slug(Post::get('pagina_nome')));
        $this->pagina_id = Post :: get('pagina_id');
        Post :: removeIndex('pagina_id');
        $post = Post::getAllObj();
        $sql = "UPDATE pagina SET $post->sql_update WHERE pagina_id = $this->pagina_id;";
        $this->db->query($sql);
    }

    //remove pagina via $.post jquery
    public function remover() {
        $this->pagina_id = Filter::parse_int(Post::get('pagina_id'));
        $this->db->query = "DELETE FROM pagina WHERE pagina_id = $this->pagina_id";
        $this->db->query();
    }

    public function getAreas() {
        $this->db->query = "SELECT * FROM area order by area_pos asc, area_nome asc";
        $this->db->query()->fetchAllObj();
        return $this->db->data;
    }

}
