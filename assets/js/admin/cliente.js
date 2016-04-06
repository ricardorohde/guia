var baseUri = $('base').attr('href');

//controla tabs 
$(function () {
    $('.fone').mask('(99) 9999-99990');
    $('a[href="#novo"]').on('click', function () {
        resetMe();
        $(this).hide();
        $('.form-busca').hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
    });
    $('a[href="#lista"]').on('click', function () {
        resetMe();
        $(this).hide();
        $('.form-busca').removeClass('hide').show();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    });

    var elm = $('#top-menu'), pos = elm.offset();
    $(window).scroll(function () {
        if ($(this).scrollTop() >= pos.top + $('#top-menu').height()) {
            $('.toolbar-save').removeClass('hide').fadeIn();
            //$('#top-menu').fadeOut();
        } else {
            $('.toolbar-save').fadeOut();
            //$('#top-menu').fadeIn();
        }
    });
    //botao da barra suspensa atualiza /submete o formulario
    $("#trig-submit").on("click", function () {
        $("#form-cliente").trigger("submit");
    });
});

//remove registro do banco
$('.btn-remove').on('click', function () {
    var cliente_id = $(this).attr('id');
    var url = baseUri + "painel/cliente/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {cliente_id: cliente_id}, function (data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Cliente removido com sucesso! ');
            //exclui a tr do registo    
            $('#tr_' + cliente_id).fadeOut();
        })
    })
})

function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}

//remove registro do banco
$('.btn-edit').on('click', function () {

    var cliente_id = $(this).attr('id');
    var cliente_grupo = $(this).attr('grupo');
    var cliente_empresa = $(this).attr('name');
    var cliente_nome = $(this).attr('empresa');
    var cliente_fone = $(this).attr('fone');
    var cliente_fone2 = $(this).attr('fone2');
    var cliente_email = $(this).attr('email');
    var cliente_site = $(this).attr('site');
    var cliente_logo = $(this).attr('logo');
    //var cliente_info = replaceAll('<br />', '\n', $('#cliente_info_' + cliente_id).val());
    var cliente_info = $('#cliente_info_' + cliente_id).val();

    if (cliente_logo != "") {
        $('#slide-preview img').attr('src', baseUri + '/midia/cliente/' + cliente_logo);
        $('#slide-preview').removeClass('hide').show();
    }
    $('#cliente_id').val(cliente_id);
    $('#cliente_grupo').val(cliente_grupo);
    $('#cliente_nome').val(cliente_nome);
    $('#cliente_empresa').val(cliente_empresa);
    $('#cliente_email').val(cliente_email);
    $('#cliente_site').val(cliente_site);
    $('#cliente_fone').val(cliente_fone);
    $('#cliente_fone2').val(cliente_fone2);
    $('#cliente_info').val(cliente_info);
    $('#a-tab-edit').tab('show');

    $('#btn-add-update-cliente').text('Atualizar');
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
    $('.form-busca').hide();
});

//cancelar editar
$('#btn-add-update-cancel').on('click', function () {
    $('#btn-add-update-cliente').text('Gravar');
    $('#a-tab-lista').tab('show');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    $('.form-busca').removeClass('hide').show();
    resetMe();
});


function resetMe() {
    $('#slide-preview').hide();
    $('#cliente_email').val('');
    $('#cliente_fone').val('');
    $('#cliente_nome').val('');
    $('#cliente_empresa').val('');
    $('#cliente_logo').val('');
    $('#filedata').val('');
}
//verifica ao enviar - se o form está vazio
$('#form-cliente').on('submit', function () {
    if ($('#cliente_id').val() <= 0 && $.trim($('#cliente_empresa').val()) === '') {
        _alert_error('<p><i class="fa  fa-warning"></i> Um nome de empresa deve ser informado!</p>');
        return false;
    }
});