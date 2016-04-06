var baseUri = $('base').attr('href');
//remove registro do banco
$('.btn-remove').on('click', function () {
    var faq_id = $(this).attr('id');
    var url = baseUri + "painel/faq/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {faq_id: faq_id}, function (data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Pergunta removida com sucesso! ');
            //exclui a tr do registo    
            $('#li_' + faq_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhuma pergunta!</p>');
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
        $("#form-faq").trigger("submit");
    });
});

//sortable para ordenar pos dos faqs
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
            var url = baseUri + '/painel/faq/UpdatePos/';
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
$('#form-faq').on('submit', function () {
    if ($.trim($('#faq_pergunta').val()) == '') {
        _alert_error('<p><i class="fa fa-warning"></i> Uma pergunta deve ser informado!</p>');
        $('#faq_pergunta').focus();
        $('#faq_pergunta').addClass('text-danger');
        $('#faq_pergunta').parent().addClass('has-error');
        $('#faq_pergunta').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
});

function resetMe()
{
    $('#faq_pergunta').val('');
    $('#faq_pergunta').addClass('text-primary');
    $('#faq_pergunta').parent().removeClass('has-error');
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
        height: 300,
        lang: 'pt-BR'
    });
    $('#side-bar').css('min-height', $('#panel-content').height() + 2);
}