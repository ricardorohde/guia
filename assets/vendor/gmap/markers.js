//var baseUri = 'http://clareslab.com.br/guiafacil';
var markers;
var markerx;
var mymarkers = [];
var address;
var lat;
var lng;
var routes;
var routesLL = [];
var statLoop = 1;
var posY;
var posX;
var map;
var cid = 0;
function initMap(lat,lng)
{
    //lat = -23.5385287; //altere a latitude e logintude conforme sua cidade
    //lng = -46.3108648;
    var myStyles =[{
        featureType: "poi.business",
        elementType: "labels",
        stylers: [
        {
            visibility: "off"
        }]
    }];
    map = new GMaps({
        div: 'map',
        lat: lat,
        lng: lng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        disableDefaultUI: true,
        styles: myStyles,
        zoom: 8
    })
    map.setCenter(lat, lng);
}


function addMarker(p) {
    var htmlr = '<div class="infowindow">';
    htmlr += '<h1>'+p.cliente_empresa+'</h1>';
    if(p.cliente_logo != ''){
        htmlr += '<div class="mywell" style="padding-left:30px"><center><img src="midia/cliente/'+p.cliente_logo+'" class="img-responsive" style="max-width:220px !important" /></center></div>';
    }else{
       // htmlr += '<div class="mywell">'+p.cliente_empresa+'</div>';
    }
    htmlr += '<p class="text-center"><a href="'+baseUri+'/encontre/'+p.grupo_url+'/'+p.cliente_url+'/" class="btn btn-primary btn-sm" style="color:#fff"><span class="fa fa-phone-square"></span> Ver mais informações</a></p>';
    htmlr += '</div>';

    // Cria um "marcador", que nada mais que é um item destacanado no mapa
    var marker = map.addMarker({
        lat: p.cliente_lat,
        lng: p.cliente_lon,
        icon: "assets/images/icons/marker.png", //+p.grupo_icone, // valor da coluna que vem lá do banco ;)
        id: p.cliente_id,
        title: p.cliente_nome,
        content: htmlr,
        click: function(){
            map.setCenter(p.cliente_lat, p.cliente_lon);
            var zoomAtual = 8;
            var posZoom = zoomAtual*10;
            if($("#zoom_slider")){
                $("#zoom_slider").animate({
                    marginLeft: posZoom
                }, 150);
            }
        },
        infoWindow: {
            content: htmlr
        },
        close: function(){
        //map.cleanRoute();
        }
    });
    mymarkers.push(marker);


}
function getAddr(address) {
    GMaps.geocode({
        address: address,
        callback: function(results, status) {
            if (status == 'OK') {
                var latlng = results[0].geometry.location;
                lat = latlng.lat();
                lng = latlng.lng();
                map.setCenter(lat, lng);
                map.setZoom(12);
            }
        }
    })
}
function closeAllInfoWindow() {
    if(map.markers){
        markers  = mymarkers;
        $.each(markers,function(i,item){
            if(mymarkers[i].infoWindow){
                mymarkers[i].infoWindow.close();
            }
        })
    }
}

function panTo(id)
{
    setTimeout(function(){
        if(map.markers){
            markers  = mymarkers;
            $.each(markers,function(i,item){
                mid = markers[i].id
                if(markers[i].id == id){
                    var m = mymarkers[i];
                    //var n = m.getPosition();
                    map.setZoom(13);
                    //map.panTo(m.getPosition());
                    map.setCenter(m.position.pb,m.position.qb);
                    //console.log(m.position.qb,m.position.pb)
                    mymarkers[i].infoWindow.open(map, mymarkers[i])
                }
            })
        }
    },500)
}


function fbshare(title,pic,id)
{
    var obj = {
        method: 'feed',
        link: baseUri+'/mapa/'+id+'/',
        picture: pic,
        name: window.title,
        caption: title,
        description: '�tima oportunidade'
    };
    function callback(response) {
        if(response){
            var postid = response['post_id'];
        }
    }
    FB.ui(obj, callback);
}

function removeAllMarkers(){
  map.removeMarkers();
}
