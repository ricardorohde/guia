class HtmlGenerator {

  /*
  $type = text, checkbox, radio, textarea...

  $config é um array com as configurações do input html
  Exemplo: array(
    'name' => 'grupo_gastronomia',
    'id'  => 'grupo_gastronomia',
    'value' => 33,
    'class' => 'my_css_class'
    ...
  )
  */

  static function createInput($type, $config){
    $strInput = '';
    $type = isset($type) ? $type : "text";
    $id = isset($config['id']) ? ($config['id'] : 'id_default';
    $name = isset($config['name']) ? ($config['name'] : 'name_default';
    $value = isset($config['value']) ? ($config['value'] : 'value_default';

    $strInput+= "<input type='$type' id='$id' name='$name' value='$value'/>";
    return $strInput;
  }

}
