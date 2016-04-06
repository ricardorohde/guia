var baseUri = $('base').attr('href');
$(function () {
    //hide elementos
    $('.hider').hide();
    //tootips
    $('.tip').tooltip();
    $('.tip-l').tooltip({
        placement: 'left'
    });
    $('.tip-r').tooltip({
        placement: 'right'
    });
    $('.tip-b').tooltip({
        placement: 'bottom'
    });
    //evento ir para o topo
    $('.go-to-top').on('click', function () {
        $('#top-menu').show();
        $('html, body').animate({
            scrollTop: $('#top-menu').offset().top - 60
        }, 500);
    });
    //fade em elementos  .fadeable
    $('.fadeable').each(function () {
        var fadeTime = $(this).attr('data-time');
        var elm = $(this).attr('id');
        setTimeout(function () {
            $('#' + elm).fadeOut(1000);
        }, fadeTime);
    });
    //windows resize
    $(window).on('resize', function () {
        if ($(window).width() <= 600) {
            $('.collapse').removeClass('in');
        } else {
            $('.collapse').addClass('in');
        }
    });
    //evita o colapse no menu
    $('.panel-title a:not(".link-click")').on('click', function () {
        return false;
    });
});


function setMenuAtivo(uri) {
    var CurrentController = $('base').attr('href');
    var CurrentUri = window.location.href;
    var UriFragment = CurrentUri.split('/painel/');
    UriFragment = UriFragment[1].split('/');
    UriFragment = UriFragment[0];
    $('#' + uri).addClass('active');
    $('#collapse-' + uri).addClass('in');
    if ($(window).width() >= 680) {
        $('.collapse').addClass('in');
    }
    $('#collapse-' + uri).parent().find('.panel-heading').addClass('active');
    token = false;
    $('#collapse-' + uri).find('a').each(function () {
        if ($(this).attr('href') === CurrentUri || $(this).attr('href') + 'ok-now/' === CurrentUri) {
            $(this).addClass('active');
            token = true;
        }
    });
    if (token === false) {
        $('#collapse-' + uri).find('a:first').addClass('active');
    }
    if ($(window).width() >= 680) {
        $('#side-bar').css('min-height', $('#panel-content').height() + 2);
    }
}

function _alert(msg) {
    notif({
        //type: type,
        msg: msg,
        multiline: true,
        position: "right",
        //fade: 500
        timeout: 2500,
        //width: 'all',
        //height: '100'
        opacity: 0.8,
        //autohide: true
        bgcolor: "#555",
        color: "#fff"
    });
}

function _alert_info(msg, pos) {
    notif({
        //type: type,
        msg: msg,
        multiline: true,
        position: pos,
        //fade: 500
        timeout: 1800,
        //width: 'all',
        //height: '100'
        opacity: 0.8,
        //autohide: true
        bgcolor: "#555",
        color: "#fff"
    });
}

function _alert_help(msg, time) {
    notif({
        //type: type,
        msg: msg,
        multiline: true,
        position: 'center',
        //fade: 500
        timeout: time,
        //width: 'all',
        //height: '100'
        opacity: 0.8,
        //autohide: true
        bgcolor: "#555",
        color: "#fff"
    });
}

function _alert_error(msg) {
    notif({
        //type: 'error',
        msg: msg,
        multiline: true,
        position: "center",
        //fade: 500
        timeout: 2500,
        //width: 'all',
        //height: '100'
        //opacity: 0.8,
        //autohide: true
        bgcolor: "#FF0039",
        color: "#fff"
    });
}

function _alert_error_time(msg,time) {
    notif({
        //type: 'error',
        msg: msg,
        multiline: true,
        position: "center",
        //fade: 500
        timeout: time,
        //width: 'all',
        //height: '100'
        //opacity: 0.8,
        //autohide: true
        bgcolor: "#FF0039",
        color: "#fff"
    });
}
