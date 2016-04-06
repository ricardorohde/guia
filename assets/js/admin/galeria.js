var baseUri = $('base').attr('href');
//exibe a barra suspensa atualizar quando rolar a tela
$(function () {
    var elm = $('#top-menu'), pos = elm.offset();
    $(window).scroll(function () {
        if ($(this).scrollTop() >= pos.top + $('#top-menu').height()) {
            $('.toolbar-save').removeClass('hide').fadeIn();
            $('#top-menu').fadeOut();
        } else {
            $('.toolbar-save').fadeOut();
            $('#top-menu').fadeIn();
        }
    });
    //botao da barra suspensa atualiza /submete o formulario
    $("#trig-submit").on("click", function () {
        $("#form-galeria").trigger("submit");
    });
});
//controla tabs 
$(function () {
    $('#a-tab-lista').tab('show');
    $('a[href="#novo"]').on('click', function () {
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
        resetMe();
        summerNote();
    });
    $('a[href="#lista"]').on('click', function () {
        $(this).hide();
        resetMe();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    });
    //zoom nas fotos para edição
    $('.foto-zoom').on('click', function () {
        var foto_id = $(this).attr('id');
        var foto_nome = $(this).attr('data-nome');
        var foto_url = $(this).attr('data-url');
        $('#ModalZoom #foto_spot').html('');
        $('#ModalZoom #foto_nome').val('');
        $('#ModalZoom .modal-title').html('Legenda: ' + foto_nome);
        $('#ModalZoom #foto_nome').val(foto_nome);
        $('#ModalZoom #foto_id').val(foto_id);
        $('<img />')
                .addClass('img-responsives')
                .attr('src', foto_url)
                .css('max-width', '700px')
                .css('max-height', '300px')
                .appendTo($('#ModalZoom #foto_spot'));
        $('#ModalZoom').modal('show');
        setTimeout(function () {
            $('#ModalZoom #foto_nome').focus();
        }, 1000);

        $('#ModalZoom #foto_nome').on('keyup', function () {
            $('#ModalZoom .modal-title').html('Legenda: ' + $(this).val());
            //$('#d_' + foto_id + ' #f_' + foto_id).text($(this).val());                     
        });
    });
    //exibe modal para upload
    $('#btn-upload').on('click', function () {
        $('#ModalUpload').modal('show');
    });
    //apos upload completo
    $('#btn-upload-complete').on('click', function () {
        window.location.reload();
    });

    //ordenar posicoes das galerias
    $(".dragger").sortable({
        opacity: 0.9,
        placeholder: "ui-state-highlight",
        cursor: "move",
        start: function (event, ui) {
            //$(this).find('.td-nome').css('width','90%');
            //$(this).find('.td-id').hide;
            $(this).find('.td-ctrl').hide();
        },
        stop: function () {
            $(this).find('.td-ctrl').show();
            var sorted = $(this).sortable('serialize');
            var url = baseUri + '/painel/galeria/UpdateGalPos/';
            $.post(url, {
                item: sorted
            }, function (data) {

            });
        }
    });
    //atualiza posicao das fotos
    $(".dragger_foto").sortable({
        opacity: 0.8,
        placeholder: "ui-state-highlight",
        cursor: "move",
        stop: function () {
            var sorted = $(this).sortable('serialize');
            var url = baseUri + '/painel/galeria/UpdateFotosPos/';
            $.post(url, {
                item: sorted
            }, function (data) {

            });
        }
    });
    //atualiza posicao dos videos
    $(".dragger_video").sortable({
        opacity: 0.8,
        placeholder: "ui-state-highlight",
        cursor: "move",
        stop: function () {
            var sorted = $(this).sortable('serialize');
            var url = baseUri + '/painel/galeria/UpdateVideoPos/'
            $.post(url, {
                item: sorted
            }, function (data) {
                //  console.log(data)
            })
        }
    });

});

//editar galeria
$('.btn-edit').on('click', function () {
    var galeria_id = $(this).attr('id');
    $('#galeria_id').val(galeria_id);
    var url = baseUri + '/painel/galeria/getGaleriaJSON/' + galeria_id + '/';
    $.getJSON(url, function (data) {
        $('#galeria_nome').val(data.galeria_nome);
        $('#galeria_desc').val(data.galeria_desc);
        $('#galeria_gcategoria').val(data.galeria_gcategoria);
        summerNote();
        $('.summernote').code(data.galeria_desc);
    });
    //return false;
    $('#a-tab-edit').tab('show');
    $("#btn-add-update span").text('Atualizar');
    $("#btn-add-update i").removeClass('fa-check-circle-o').addClass('fa-refresh');
    $('#galeria_nome').focus();
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
});



$('#btn-remove-selecao').on('click', function () {
    if ($('.checkbox-remove:checked').length >= 1) {
        $('#ModalRemove').modal('show');
    } else {
        _alert_error('<p><i class="fa fa-warning-sign"></i> Nenhuma foto selecionada</p>');
    }
});

//remove galeria do banco
$('.btn-remove').on('click', function () {
    var galeria_id = $(this).attr('id');
    var galeria_nome = $(this).attr('name');
    var url = baseUri + "painel/galeria/remover/";
    $('#ModalRemove').modal('show');

    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {galeria_id: galeria_id}, function (data) {
            $('#ModalRemove').modal('hide');
            _alert('Galeria removida com sucesso! ');
            //exclui a tr do registo    
            $('#li_' + galeria_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhuma galeria!</p>')
            }
            //reset form
            resetMe();
            $("#btn-add-update span").text('Cadastrar')
            $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-check-circle-o');
        })
    })
});

//confirma remocao das fotos selecionadas
$('#btn-confirm-remove').on('click', function () {
    var lista = [];
    $('.checkbox-remove:checked').each(function (k, v) {
        lista.push({foto_id: $(this).val(), foto_url: $(this).attr('data-url')});
    });
    var url = baseUri + '/painel/galeria/fotoRemove/';
    $.post(url, {lista: lista}, function (data) {
        $(lista).each(function (k, v) {
            $('#d_' + v.foto_id).fadeOut();
        });
        $('#ModalRemove').modal('hide');
        $('#btn-remove-selecao').html('<i class="fa fa-trash"></i>');
        _alert('Fotos removidas com sucesso! ');
    });
});

//atualiza dados da foto aberta no modal
$('#btn-update-foto').on('click', function () {
    var foto_nome = $.trim($('#ModalZoom #foto_nome').val());
    var foto_id = $('#ModalZoom #foto_id').val();
    var url = baseUri + '/painel/galeria/fotoUpdate/';
    $('#' + foto_id).attr('data-nome', foto_nome);
    $.post(url, {foto_id: foto_id, foto_nome: foto_nome}, function (data) {
        $('#ModalZoom').modal('hide');
        $('#d_' + foto_id + ' #f_' + foto_id).text(foto_nome);
    });
});

//cancela editar // reset form
$('#btn-add-update-cancel').on('click', function () {
    $('#a-tab-lista').tab('show');
    $("#btn-add-update span").text('Cadastrar')
    $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-check-circle-o');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
});


//cancela novo // reset form
$('.btn-add-novo').on('click', function () {
    $('#a-tab-lista').tab('show');
    $("#btn-add-update span").text('Cadastrar');
    $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-check-circle-o');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
});

//verifica ao enviar - se o form está vazio
$('#form-galeria').on('submit', function () {
    if ($.trim($('#galeria_nome').val()) == '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um nome deve ser informado!</p>');
        $('#galeria_nome').focus();
        $('#galeria_nome').addClass('text-danger');
        $('#galeria_nome').parent().addClass('has-error');
        $('#galeria_nome').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
});
//reset form
function resetMe()
{
    $('#galeria_id').val('');
    $('#galeria_desc').val('');
    $('#galeria_nome').val('');
    $('#galeria_nome').removeClass('text-danger');
    $('#galeria_nome').parent().removeClass('has-error');
    $('#remove-me').remove();
}



function summerNote() {
    $('.summernote').code('');
    $('.summernote').destroy();
    $('.summernote').summernote({
        toolbar: [
            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'table']],
            ['misc', ['undo', 'redo', 'codeview', 'fullscreen']]
        ],
        height: 150,
        lang: 'pt-BR'
    });
}