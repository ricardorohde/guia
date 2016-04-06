var baseUri = $('base').attr('href');
//remove registro do banco
$('.btn-remove').on('click', function () {
    var servico_id = $(this).attr('id');
    var url = baseUri + "painel/servico/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {servico_id: servico_id}, function (data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Serviço removido com sucesso! ');
            //exclui a tr do registo    
            $('#li_' + servico_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhum serviço!</p>');
            }
        });
    });
});

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
        $("#form-servico").trigger("submit");
    });
});

//sortable para ordenar pos dos servicos
if ($(".dragger").length >= 1) {
    $(".dragger").sortable({
        opacity: 0.9,
        placeholder: "ui-state-highlight",
        cursor: "move",
        start: function (event, ui) {
            //$(this).find('.td-ctrl').hide();
        },
        stop: function () {
            //$(this).find('.td-ctrl').show();
            var sorted = $(this).sortable('serialize');
            var url = baseUri + '/painel/servico/UpdatePos/';
            $.post(url, {
                item: sorted
            }, function (data) {
                //console.log(data)
                _alert('Posições atualizadas! ');
            });
        }
    });
}
//abre modal help
$('.link-help').on('click', function () {
    $('#ModalHelp').modal('show');
});

//verifica ao enviar - se o form está vazio
$('#form-servico').on('submit', function () {
    if ($.trim($('#servico_titulo').val()) == '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um título deve ser informado!</p>');
        $('#servico_titulo').focus();
        $('#servico_titulo').addClass('text-danger');
        $('#servico_titulo').parent().addClass('has-error');
        $('#servico_titulo').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
});

function resetMe()
{
    $('#servico_titulo').val('');
    $('#servico_titulo').addClass('text-primary');
    $('#servico_titulo').parent().removeClass('has-error');
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
            ['insert', ['picture', 'video', 'link', 'table']],
            ['misc', ['undo', 'redo', 'codeview', 'fullscreen']]
        ],
        height: 550,
        lang: 'pt-BR'
    });
    $('#side-bar').css('min-height', $('#panel-content').height() + 2);
}