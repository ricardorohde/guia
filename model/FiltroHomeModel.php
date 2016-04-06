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

  public function getClientesPorCategoria($categoriasCheckeds){
    echo "<script>console.log(22)</script>";
    /*$categorias = 0;
    if (isset($categoriasCheckeds) && !empty($categoriasCheckeds)) {
      $checkvalues = json_decode($categoriasCheckeds); // $_POST['checkvalues']
      $categorias = implode(",", $checkvalues); // 22,21,10,8,4 etc
    }
    echo "<script>console.log(2)</script>";

    $query = "SELECT  c.cliente_lat_lon,
                      c.cliente_id,
                      c.cliente_nome,
                      c.cliente_empresa,
                      c.cliente_url,
                      c.cliente_logo,
                      g.grupo_id,
                      g.grupo_nome,
                      g.grupo_url,
                      g.grupo_icone
              FROM    cliente c,
                      grupo g
              WHERE   c.cliente_grupo = g.grupo_id
              AND     g.grupo_id in ($categorias)";

    $this->db->query($query)->fetchAll();
    echo "<script>console.log(3)</script>";

    foreach($this->db->data as $k => $v) {
        $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
        $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
        $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
        $this->db->data[$k]['cliente_nome'] = utf8_decode($this->db->data[$k]['cliente_nome']);
        $this->db->data[$k]['cliente_empresa'] = utf8_decode($this->db->data[$k]['cliente_empresa']);
        unset($this->db->data[$k]['cliente_lat_lon']);
    }

    echo $this->db->toJson($this->db->data);*/
  }

}

?>
