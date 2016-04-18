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

    public $zimaconn;

    // constantes com as querys SQL - BEGIN

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
    AND		  t.tipo_destaque_id in  (1)"; // 1 = Mapa Home

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
    AND		  t.tipo_destaque_id in  (2)"; // 2 = Footer Home

    const QUERY_SELECT_TODOS_CLIENTES = "
    SELECT	c.cliente_id,
		        c.cliente_nome,
            c.cliente_empresa,
            c.cliente_url,
            c.cliente_fone,
            c.cliente_bairro,
            c.cliente_email,
            g.grupo_id as id_categoria,
            g.grupo_nome as nome_categoria,
            g.grupo_icone as icone_categoria,
            g.grupo_url as url_categoria,
            b.id as id_bairro,
            b.nome as nome_bairro
    FROM	  cliente c,
    		    grupo g,
            z_bairro b
    WHERE	  c.cliente_grupo = g.grupo_id
    AND		b.id = c.bairro_id
    ORDER BY c.cliente_empresa ASC
    ";

    const QUERY_SELECT_CATEGORIAS_PRINCIPAIS = "
      SELECT  *
      FROM  grupo
      WHERE id_grupo_superior is NULL
      ORDER BY grupo_nome
    ";

    const QUERY_SELECT_BAIRROS_PICARRAS = "
      SELECT  id,
              nome
      FROM    z_bairro
      WHERE   cidade_id = 2
      ORDER BY nome;
    ";
    // constantes com as querys SQL - END

    public function __construct() {
        setlocale(LC_ALL, 'pt_BR.utf8');
        //conexao com banco
        $this->registry = Registry::getInstance();
        if ($this->registry->get('db') == null){
          $this->registry->set('db', new DB);
        }
        $this->db = $this->registry->get('db');
        //Verifica modulos que devem ser carregados
        $this->modulo = new ModuloModel($this->registry);


        $zimaRegistry = ZimaRegistry::getInstance();
        if ($zimaRegistry->get('zimaconnection') == null) {
          $opcoes = array(
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
          );
          $conn = new PDO(
            'mysql:host=localhost;port=3306;dbname=guiafacil',
            'root',
            '',
            $opcoes
          );
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

          $zimaRegistry->set('zimaconnection', $conn);
          $this->zimaconn = $zimaRegistry->get('zimaconnection');
        } else {
          $this->zimaconn = $zimaRegistry->get('zimaconnection');
        }
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
        $checkvalues = json_decode($_POST['checkvalues']);
        $categorias = implode(",", $checkvalues); // 22,21,10,8,4 etc
      }

      if(empty($categorias)){
        $categorias = 0;
      }

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
          $this->db->data[$k]['cliente_nome'] = $this->db->data[$k]['cliente_nome'];
          $this->db->data[$k]['cliente_empresa'] = $this->db->data[$k]['cliente_empresa'];
          unset($this->db->data[$k]['cliente_lat_lon']);
      }

      $myData = count($this->db->data);
      $return = $myData > 0 ? $this->db->toJson($this->db->data) : "";
      //echo "<script>console.log($return);</script>";
      echo $return;
    }

    public function getClientesDestaqueMapaHome(){
      $this->db->query(self::QUERY_SELECT_CLIENTES_DESTAQUE_MAPA_HOME)->fetchAll();

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

    public function getClientesDestaqueFooter(){
      $this->db->query(self::QUERY_SELECT_CLIENTES_DESTAQUE_FOOTER_HOME)->fetchAll();

      foreach($this->db->data as $k => $v) {
          $lat_lon = json_decode ($this->db->data[$k]['cliente_lat_lon']);
          $this->db->data[$k]['cliente_lat'] = $lat_lon->{'lat'};
          $this->db->data[$k]['cliente_lon'] = $lat_lon->{'lon'};
          $this->db->data[$k]['cliente_nome'] = $this->db->data[$k]['cliente_nome'];
          $this->db->data[$k]['cliente_empresa'] = $this->db->data[$k]['cliente_empresa'];
          unset($this->db->data[$k]['cliente_lat_lon']);
      }

      return count($this->db->data) > 0 ? $this->db->data : array();
    }

    // Retorna as categorias com id_superior = null (categorias pai)
    public function getCategoriasPrincipais(){
      $this->db->query(self::QUERY_SELECT_CATEGORIAS_PRINCIPAIS)->fetchAll();

      foreach($this->db->data as $k => $v) {
          $this->db->data[$k]['grupo_id'] = $this->db->data[$k]['grupo_id'];
          $this->db->data[$k]['grupo_url'] = $this->db->data[$k]['grupo_url'];
          $this->db->data[$k]['grupo_nome'] = utf8_encode($this->db->data[$k]['grupo_nome']);
      }

      return count($this->db->data) > 0 ? $this->db->data : array();
    }

    public function getClientesDestaqueFooterAjax(){
      $zimaRegistry = ZimaRegistry::getInstance();
      $this->zimaconn = $zimaRegistry->get('zimaconnection');

      $stmt = $this->zimaconn->prepare(self::QUERY_SELECT_CLIENTES_DESTAQUE_FOOTER_HOME);
      $stmt->execute();

      $json_data = array();
      foreach($stmt as $rec){
        $lat_lon = json_decode($rec['cliente_lat_lon']);
        $json_array['cliente_lat'] = $lat_lon->{'lat'};
        $json_array['cliente_lon'] = $lat_lon->{'lon'};
        $json_array['cliente_nome'] = $rec['cliente_nome'];
        $json_array['cliente_id']=$rec['cliente_id'];
        $json_array['cliente_empresa']= $rec['cliente_empresa'];

        //here pushing the values in to an array
        array_push($json_data,$json_array);
      }

      $v = json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      header("Content-Type: text/html; charset=UTF-8");
      echo $v;
    }

    public function getTodosClientesAjax(){
      $zimaRegistry = ZimaRegistry::getInstance();
      $this->zimaconn = $zimaRegistry->get('zimaconnection');

      $stmt = $this->zimaconn->prepare(self::QUERY_SELECT_TODOS_CLIENTES);
      $stmt->execute();

      $json_data = array();
      foreach($stmt as $rec){
        $lat_lon = json_decode($rec['cliente_lat_lon']);
        $json_array['cliente_lat'] = $lat_lon->{'lat'};
        $json_array['cliente_lon'] = $lat_lon->{'lon'};
        $json_array['cliente_nome'] = $rec['cliente_nome'];
        $json_array['cliente_id']=$rec['cliente_id'];
        $json_array['cliente_empresa']= $rec['cliente_empresa'];
        $json_array['cliente_url']=$rec['cliente_url'];
        $json_array['cliente_fone']= $rec['cliente_fone'];
        $json_array['cliente_email']= $rec['cliente_email'];

        $json_array['categoria_nome']=$rec['nome_categoria'];
        $json_array['categoria_icone']= $rec['icone_categoria'];
        $json_array['categoria_id']= $rec['id_categoria'];
        $json_array['categoria_url']= $rec['url_categoria'];

        $json_array['nome_bairro']= $rec['nome_bairro'];
        $json_array['id_bairro'] = $rec['id_bairro']; //para filtrar no typeahead com radiobutton "&id_bairro"
        $json_array['is_categoria'] = array($rec['url_categoria'] => true); //para filtrar no typeahead com checkbox "|is_categoria.bares ..."

        //here pushing the values in to an array
        array_push($json_data,$json_array);
      }

      $v = json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      header("Content-Type: text/html; charset=UTF-8");
      echo $v;
    }

    public function getBairrosPicarras(){
      $stmt = $this->zimaconn->prepare(self::QUERY_SELECT_BAIRROS_PICARRAS);
      $stmt->execute();

      return $stmt;
    }

    public function createRadioButtonBairro($myFilter){
      $value = $myFilter['id'];
      $id = 'bairro'.$myFilter['id']; //propriedade id do input
      $description = substr($myFilter ['nome'],0,30); //label do input
      $searchClass = "radiobutton-bairro-search-typeahead-class"; // para pegar todos os inputs via jQuery com essa classe

      // monta o input HTML baseado no retorno do banco
      $input = "<span>";
      $input .= "<input type='radio' ";
      $input.= " id='$id' ";
      $input.= " name='radiobutton-bairro' ";
      //$input.= " checked='$checked' ";
      $input.= " class='$searchClass'";
      $input.= " value='$value'>";
      $input.= "<label for='$id'>". $description ."</label>";
      $input.= "</span>";

      // joga na tela
      return $input;
    }

    public function createCheckboxCategoria($myFilter){
            $value = $myFilter['grupo_nome']; //nesse caso o "ID" é o nome, assim o typeahead pode filtrar a string
            $name = $myFilter['grupo_url']; //propriedade name do input
            $id = $myFilter['grupo_url']; //propriedade id do input
            $description = substr($myFilter ['grupo_nome'],0,30); //label do input
            $checked = "checked"; //default todos os checkboxs checados
            $searchClass = "checkbox-search-typeahead-class"; // para pegar todos os inputs via jQuery com essa classe

            // monta o input HTML baseado no retorno do banco
            $input = "<span>";
            $input .= "<input type='checkbox' ";
            $input.= " id='$id' ";
            $input.= " checked='$checked' ";
            $input.= " class='$searchClass' />";
            $input.= " $description ";
            $input.= "</span>";

            //$input.= "</input>";

            // joga na tela
            return $input;
    }
}
