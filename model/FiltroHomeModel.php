<?php

class FiltroHomeModel {

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

  public function getFiltros(){
    $this->db->query = "SELECT * FROM grupo WHERE id_grupo_superior is NULL";
    $this->db->query()->fetchAllObj();
    return $this->db->data;
  }

}

?>
