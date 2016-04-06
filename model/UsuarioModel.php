<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br> 
 * Web: www.phpstaff.com.br
 * Data: 08/2014
 */
class UsuarioModel {

    public $usuario_id = null;
    public $usuario_nome;
    public $usuario_login;
    public $usuario_email;
    public $usuario_nivel;
    public $usuario_senha;
    public $usuario_fone;
    public $site;
    public $db;
    public $session;
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

    public function setId($id) {
        $this->usuario_id = $id;
    }

    public function getId() {
        return $this->usuario_id;
    }

    public function setNome($nome) {
        $this->usuario_nome = $nome;
    }

    public function getNome() {
        return $this->usuario_nome;
    }

    public function setLogin($login) {
        $this->usuario_login = $login;
    }

    public function getLogin() {
        return $this->usuario_login;
    }

    public function setEmail($email) {
        $this->usuario_email = $email;
    }

    public function getEmail() {
        return $this->usuario_email;
    }

    public function setNivel($nivel) {
        $this->usuario_nivel = $nivel;
    }

    public function getNivel() {
        return $this->usuario_nivel;
    }

    public function setSenha($senha) {
        $this->usuario_senha = $senha;
    }

    public function getSenha() {
        return $this->usuario_senha;
    }

    public function setFone($fone) {
        $this->usuario_fone = $fone;
    }

    public function getFone() {
        return $this->usuario_fone;
    }

    //recupera os dados do usuario por ID
    public function getById() {
        $this->db->query = "SELECT * FROM usuario WHERE usuario_id = $this->usuario_id";
        $this->db->query()->fetchAllObj();
        if ($this->db->numRows() >= 1) {
            $this->setId(intval($this->db->data[0]->usuario_id));
            $this->setNome($this->db->data[0]->usuario_nome);
            $this->setLogin($this->db->data[0]->usuario_login);
            $this->setNivel($this->db->data[0]->usuario_nivel);
            $this->setSenha($this->db->data[0]->usuario_senha);
            $this->setEmail($this->db->data[0]->usuario_email);
            $this->setFone($this->db->data[0]->usuario_fone);
            /*
              $this->sessao->start();
              $this->sessao->init(72000, $this->baseuri);
              $this->sessao->check("$this->baseuri");
              $this->sessao->addNode('usuario_id', $this->getId());
              $this->sessao->addNode('usuario_login', $this->getLogin());
              $this->sessao->addNode('usuario_email', $this->getEmail());
              $this->sessao->addNode('usuario_nome', $this->getNome());
              $this->sessao->addNode('usuario_nivel', $this->getNivel());
             */
        }
    }

    //efetua o login apenas com ID
    public function loginById() {
        $this->db->query = "SELECT * FROM usuario WHERE usuario_id = $this->usuario_id";
        $this->db->query()->fetchAllObj();
        if ($this->db->numRows() >= 1) {
            $this->setId(intval($this->db->data[0]->usuario_id));
            $this->setNome($this->db->data[0]->usuario_nome);
            $this->setLogin($this->db->data[0]->usuario_login);
            $this->setNivel($this->db->data[0]->usuario_nivel);
            $this->setSenha($this->db->data[0]->usuario_senha);
            $this->setEmail($this->db->data[0]->usuario_email);
            $this->setFone($this->db->data[0]->usuario_fone);
        }
    }

    //efetua login usuario
    public function loginPass() {
        $sql = "SELECT * FROM usuario WHERE usuario_login = '$this->usuario_login' AND usuario_senha = '$this->usuario_senha' ";
        $this->db->query("$sql")->fetchAllObj();
        if ($this->db->rows >= 1) {
            $this->setId($this->db->data[0]->usuario_id);
            $this->setNome($this->db->data[0]->usuario_nome);
            $this->setLogin($this->db->data[0]->usuario_login);
            $this->setNivel($this->db->data[0]->usuario_nivel);
            $this->setSenha($this->db->data[0]->usuario_senha);
            $this->setEmail($this->db->data[0]->usuario_email);
            $this->setFone($this->db->data[0]->usuario_fone);
        } else {
            $this->setId(0);
        }
    }

    //método inclui ou atualiza usuario
    public function atualizar() {
        $this->usuario_id = Filter:: parse_int(Post:: get('usuario_id'));
        $this->usuario_senha = Filter:: parse_string(Post:: get('usuario_senha'));
        if ($this->usuario_senha === "") {
            Post::removeIndex('usuario_senha');
        } else {
            Post::changeIndex('usuario_senha', md5($this->usuario_senha));
        }
        if ($this->usuario_id <= 0) {
            Post::removeIndex('usuario_id');
            $post = Post:: getAllObj();
            $sql = "INSERT INTO usuario $post->sql_insert;";
        } else {
            $post = Post:: getAllObj();
            $sql = "UPDATE usuario SET $post->sql_update WHERE usuario_id = $this->usuario_id;";
        }
        $this->db->query($sql);
    }

    //remover usuario
    public function remover() {
        $this->usuario_id = Filter:: parse_int(Post:: get('usuario_id'));
        $this->setId($this->usuario_id);
        $this->db->query = "DELETE FROM usuario WHERE usuario_id = $this->usuario_id";
        $this->db->query();
    }

}
