<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class ContatoModel {

    public $db;
    public $tpl;
    public $endereco;
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

    //método atualiza registro vindo do fomulário
    public function atualizar() {
        $post = Post::getAllObj();
        $this->db->query = "UPDATE contato SET $post->sql_update;";
        $this->db->query();
    }

    public function get() {
        $this->db->query = "SELECT * FROM contato";
        $this->db->query()->fetchAllObj();
        $str = '';
        if (isset($this->db->data)) {
            if ($this->db->data[0]->{'contato_endereco'} != "") {
                $str .= $this->db->data[0]->{'contato_endereco'};
            }
            if ($this->db->data[0]->{'contato_complemento'} != "") {
                $str .= ', ' . $this->db->ata[0]->{'contato_complemento'};
            }
            if ($this->db->data[0]->{'contato_bairro'} != "") {
                $str .= ', ' . $this->db->data[0]->{'contato_bairro'};
            }
            if ($this->db->data[0]->{'contato_cidade'} != "") {
                $str .= ', ' . $this->db->data[0]->{'contato_cidade'};
            }
            if ($this->db->data[0]->{'contato_uf'} != "") {
                $str .= ', ' . $this->db->data[0]->{'contato_uf'};
            }
        }
        $this->endereco = $str;
        return $this->db->data[0];
    }
}
