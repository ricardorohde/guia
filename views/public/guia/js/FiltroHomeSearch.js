// Action disparada no click dos checkboxes que filtram o mapa
function doFiltroHomeSearch(){
  //var url = 'http://localhost/guiafacil/filtrohomesearch/getClientesPorCategoria';
  var url = baseUri+'/filtrohomesearch/getClientesPorCategoria';
  console.log("POST filtrohomeSearch: " + url);

  // pegando os checkboxs selecionados
  var checkboxValues = [];
  $('.filtrohome-search-class').map(function(){
    if ($(this).is(':checked'))
      checkboxValues.push($(this).val());
  });
  checkboxValues = JSON.stringify(checkboxValues);

  // function executada quando o AJAX é feito com sucesso
  var callbackSuccess = function(dataJSON){
    if (dataJSON != null && dataJSON != "") {
      var data = $.parseJSON(dataJSON);
      $.each(data.rs, function(key, value){
        addMarker(value);
      })
    } else {
      removeAllMarkers();
      console.log('Retorno do AJAX sem nada. A query não retornou nenhum registro ou o arquivo .php está com problema de sintaxe.');
    }
  }

  $.ajax({
    type: "POST",
    url: url,
    data: {'checkvalues': checkboxValues},
    cache: false,
    success: callbackSuccess
  });
}
