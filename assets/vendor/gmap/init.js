//var baseUri = 'http://clareslab.com.br/guiafacil';
function mapInit(lat, lon) {
    initMap(lat, lon);
    /*ZOOM CONTROL*/
    var zoomAtual = 11;
    $('#zoomIn').live('click', function () {
        zoomAtual++;
        if (zoomAtual < 21) {
            var posZoom = zoomAtual * 10;
            $("#zoom_slider").animate({
                marginLeft: posZoom
            }, 150);
        } else {
            zoomAtual = 21;
        }
        map.zoomIn(1);
    });
    $('#zoomOut').live('click', function () {
        zoomAtual--;
        if (zoomAtual > 0) {
            var posZoom = zoomAtual * 10;
            $("#zoom_slider").animate({
                marginLeft: posZoom
            }, 150);
        } else {
            zoomAtual = 0;
        }
        map.zoomOut(1);
    });
    /*END ZOOM CONTROL*/
    // getMarkers(); ORIGINAL
    doFiltroHomeSearch(); // Zima Cagando
}

// Faz um Ajax/POST para o arquivo Pontos.php
// que por sua vez faz um SELECT retornando TODOS os clientes cadastrados
// que tenham a coluna cliente_lat_lon preenchida (diferente de vazio)

// O retorno é jogado no callback pela variável data
// Se data != 'null' então é feito o parse do JSON (lista dos clientes)
// E iterado sobre essa lista, chamando a function addMarker(marcadorNoMapa) (markers.js)
function getMarkers() {
    var url = baseUri + '/pontos';
        console.log(url);
    $.post(url, {}, function (data) {
        if (data != 'null') {
            var data = $.parseJSON(data);
            $.each(data.rs, function (k, v) {
                addMarker(v)
            })
            setTimeout(function () {
                markerClusterer = new MarkerClusterer(map.map, mymarkers, {
                    maxZoom: 25,
                    gridSize: 30
                });
            }, 1500)
        }
    })
}
