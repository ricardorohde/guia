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
        $("#form-pagina").trigger("submit");
    });
});
//remove registro do banco
$('.btn-remove').on('click', function () {
    var pagina_id = $(this).attr('id');
    var url = baseUri + "painel/pagina/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {pagina_id: pagina_id}, function (data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Página removida com sucesso! ');
            //exclui a tr do registo    
            $('#tr_' + pagina_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhuma página!</p>')
            }
        })
    })
})

//abre modal help
$('.link-help').on('click', function () {
    $('#ModalHelp').modal('show');
})

//verifica ao enviar - se o form está vazio
$('#form-pagina').on('submit', function () {
    if ($.trim($('#pagina_nome').val()) == '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um título deve ser informado!</p>');
        $('#pagina_nome').focus();
        $('#pagina_nome').addClass('text-danger');
        $('#pagina_nome').parent().addClass('has-error');
        $('#pagina_nome').parent().append('<span class="fa fa-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
})

function resetMe()
{
    $('#pagina_nome').val('');
    $('#pagina_nome').addClass('text-primary');
    $('#pagina_nome').parent().removeClass('has-error');
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