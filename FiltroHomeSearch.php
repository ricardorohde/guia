<?php

/**
 * Autor: Rafael Clares <rafael@clares.com.br>
 * Web: www.phpstaff.com.br
 * Data: 08/2014

 * 'http://localhost/guiafacil/filtrohomesearch/myaction/param_1/param_2/param_3';
 * Router::getUri(0); = Controller
 * Router::getUri(1); = Action
 * Router::getUri(2); = Param_1
 * Router::getUri(3); = Param_2
 */
@session_start();
class FiltroHomeSearch {

    public $db;
    public $registry;
    public $tpl;

    // constantes com as querys SQL - BEGIN
    private static $ID_DESTAQUE_MAPA_HOME = 1;
    private static $ID_DESTAQUE_FOOTER_HOME = 2;

    // *** Select retorna os clientes que tenham a categoria em destaque no MAPA da home ***
    // Ele busca também as clientes da categoria filha da categoria destaque
    // Exemplo: Categoria destaque é Gastronomia, mas lanchonete e pizzaria são filhas da Gastronomia
    // então clientes de lanchonete e pizzaria aparecem também

    const QUERY_SELECT_CLIENTES_CATEGORIA_DESTAQUE_MAPA = "
    SELECT	c.cliente_lat_lon,
            c.cliente_id,
            c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_logo,
            g.grupo_id,
            g.grupo_nome,
            g.id_grupo_superior
    FROM	  cliente c,
      		  grupo g,
      		  grupo_tipo_destaque t
    WHERE	  t.grupo_id = g.grupo_id
    AND		  c.cliente_grupo = g.grupo_id
    AND		  t.tipo_destaque_id in (1)
    UNION
    SELECT	c.cliente_lat_lon,
            c.cliente_id,
            c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_logo,
            g.grupo_id,
            g.grupo_nome,
            g.id_grupo_superior
    FROM	  cliente c,
    		    grupo g
    WHERE	  c.cliente_grupo = g.grupo_id
    AND		  g.id_grupo_superior in (SELECT	g.grupo_id
    FROM	  cliente c,
    		    grupo g,
    		    grupo_tipo_destaque t
    WHERE	  t.grupo_id = g.grupo_id
    AND		  c.cliente_grupo = g.grupo_id
    AND		  t.tipo_destaque_id in (1))";

    // *** Select retorna os clientes que tenham a categoria em destaque no FOOTER da home ***
    const QUERY_SELECT_CLIENTES_CATEGORIA_DESTAQUE_FOOTER_HOME = "
    SELECT	c.cliente_lat_lon,
            c.cliente_id,
            c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_logo,
            g.grupo_id,
            g.grupo_nome,
            g.id_grupo_superior
    FROM	  cliente c,
      		  grupo g,
      		  grupo_tipo_destaque t
    WHERE	  t.grupo_id = g.grupo_id
    AND		  c.cliente_grupo = g.grupo_id
    AND		  t.tipo_destaque_id in (2)
    UNION
    SELECT	c.cliente_lat_lon,
            c.cliente_id,
            c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_logo,
            g.grupo_id,
            g.grupo_nome,
            g.id_grupo_superior
    FROM	  cliente c,
    		    grupo g
    WHERE	  c.cliente_grupo = g.grupo_id
    AND		  g.id_grupo_superior in (SELECT	g.grupo_id
    FROM	  cliente c,
      		  grupo g,
      		  grupo_tipo_destaque t
    WHERE	  t.grupo_id = g.grupo_id
    AND		  c.cliente_grupo = g.grupo_id
    AND		  t.tipo_destaque_id in (2))";

    // *** Select retorna os clientes que tenham o seu destaque no Mapa Home (não é por categoria, sim por cliente - tabela cliente_tipo_destaque) ***
    const QUERY_SELECT_CLIENTES_DESTAQUE_MAPA_HOME = "
    SELECT	c.cliente_lat_lon,
            c.cliente_id,
            c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_logo,
            g.grupo_id,
            g.grupo_nome,
            g.id_grupo_superior,
            t.tipo_destaque_id
    FROM	  cliente c,
		        grupo g,
		        cliente_tipo_destaque t
    WHERE	  c.cliente_grupo = g.grupo_id
    AND		  c.cliente_id = t.cliente_id
    AND		  t.tipo_destaque_id in  (1)";

    const QUERY_SELECT_CLIENTES_DESTAQUE_FOOTER_HOME = "
    SELECT	c.cliente_lat_lon,
            c.cliente_id,
            c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_logo,
            g.grupo_id,
            g.grupo_nome,
            g.id_grupo_superior,
            t.tipo_destaque_id
    FROM	  cliente c,
		        grupo g,
		        cliente_tipo_destaque t
    WHERE	  c.cliente_grupo = g.grupo_id
    AND		  c.cliente_id = t.cliente_id
    AND		  t.tipo_destaque_id in  (2)";
    // constantes com as querys SQL - END

    public function __construct() {
        //conexao com banco
        $this->registry = Registry::getInstance();
        $this->registry->set('db', new DB);
        $this->db = $this->registry->get('db');
        //Verifica modulos que devem ser carregados
        $this->modulo = new ModuloModel($this->registry);
    }

    public function indexController() {
      echo "<script>console.log('caiu aqui de novo')</script>";

      $sql = QUERY_SELECT_CLIENTES_DESTAQUE_MAPA_HOME;
      $this->db->query($sql)->fetchAll();

      foreach($this->db->data as $k => $v){
          //$this->db->data[$k]->{'client_lat'} = $this->db->data[$k]->{'client_lat_lon'};
          $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
          $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
          $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
          $this->db->data[$k]['cliente_nome'] = utf8_decode($this->db->data[$k]['cliente_nome']);
          $this->db->data[$k]['cliente_empresa'] = utf8_decode($this->db->data[$k]['cliente_empresa']);
          unset($this->db->data[$k]['cliente_lat_lon']);
      }
      //$this->db->data = (object) $this->db->data;

      echo $this->db->toJson($this->db->data);
    }

    // Retorna todos os clientes das categorias selecionadas no filtro da página Home
    public function getClientesPorCategoria(){
      if (isset($_POST['checkvalues']) && !empty($_POST['checkvalues']) && count($_POST['checkvalues']) > 0) {
        //$teste = $_POST['checkvalues'];
        //echo "<script> console.log($teste); </script>";

        $checkvalues = json_decode($_POST['checkvalues']);
        $categorias = implode(",", $checkvalues); // 22,21,10,8,4 etc
      }

      if(empty($categorias)){
        $categorias = 0;
      }

      //echo "<script> console.log('$categorias'); </script>";

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

      foreach($this->db->data as $k => $v) {
          $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
          $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
          $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
          $this->db->data[$k]['cliente_nome'] = utf8_decode($this->db->data[$k]['cliente_nome']);
          $this->db->data[$k]['cliente_empresa'] = utf8_decode($this->db->data[$k]['cliente_empresa']);
          unset($this->db->data[$k]['cliente_lat_lon']);
      }

      $myData = count($this->db->data);
//      echo "<script> console.log('count retorno banco = $myData'); </script>";
      //die();
      $return = $myData > 1 ? $this->db->toJson($this->db->data) : "";
      //{'rs': [{'retorno': 'Retorno vazio do banco'}]}
      echo $return;
    }

    public function getClientesCategoriaDestaqueMapaHome(){
      $this->db->query(QUERY_SELECT_CLIENTES_DESTAQUE_MAPA_HOME)->fetchAll();

      foreach($this->db->data as $k => $v) {
          //$this->db->data[$k]->{'client_lat'} = $this->db->data[$k]->{'client_lat_lon'};
          $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
          $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
          $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
          $this->db->data[$k]['cliente_nome'] = utf8_decode($this->db->data[$k]['cliente_nome']);
          $this->db->data[$k]['cliente_empresa'] = utf8_decode($this->db->data[$k]['cliente_empresa']);
          unset($this->db->data[$k]['cliente_lat_lon']);
      }
      //$this->db->data = (object) $this->db->data;

      echo $this->db->toJson($this->db->data);
    }


}
