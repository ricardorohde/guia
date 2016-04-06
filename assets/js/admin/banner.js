var baseUri = $('base').attr('href');

//controla tabs 
$(function () {
    $('#a-tab-lista').tab('show');
    $('a[href="#novo"]').on('click', function () {
        $('#slide_id').val('');
        $('#slide_titulo').val('');
        $('#slide_local').val('');
        $('#slide_link').val('');
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
    })
    $('a[href="#lista"]').on('click', function () {
        $(this).hide();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    })

    //ordenar posicoes 
    $(".dragger").sortable({
        opacity: 0.9,
        placeholder: "ui-state-highlight",
        cursor: "move",
        start: function (event, ui) {
            $(this).find('.td-ctrl').hide();
        },
        stop: function () {
            $(this).find('.td-ctrl').show();
            var sorted = $(this).sortable('serialize');
            var url = baseUri + '/painel/slide/UpdatePos/';
            $.post(url, {
                item: sorted
            }, function (data) {
                _alert('Posição alterada com sucesso!');
            });
        }
    });
})

//remove registro do banco
$('.btn-remove').on('click', function () {
    var slide_id = $(this).attr('id');
    var url = baseUri + "painel/slide/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {slide_id: slide_id}, function (data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Banner removido com sucesso! ');
            //exclui a tr do registo    
            $('#tr_' + slide_id).fadeOut();
        })
    })
})

//remove registro do banco
$('.btn-edit').on('click', function () {
    $('#a-tab-edit').tab('show');
    var slide_id = $(this).attr('id');
    var slide_titulo = $(this).attr('name');
    var slide_local = $(this).attr('data-local');
    var slide_url = $(this).attr('data-url');
    var slide_link = $(this).attr('data-link');
    $('#slide_id').val(slide_id);
    $('#slide_titulo').val(slide_titulo);
    $('#slide_local').val(slide_local);
    $('#slide_link').val(slide_link);
    $('#slide-preview img').attr('src', baseUri + '/midia/banner/' + slide_url);
    $('#slide-preview').removeClass('hide').show();
    $('#btn-add-update-slide').text('Atualizar');
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
})

//cancelar editar
$('#btn-add-update-cancel').on('click', function () {
    $('#slide_id').val('');
    $('#slide_titulo').val('');
    $('#slide_local').val('');
    $('#slide_link').val('');
    $('#btn-add-update-slide').text('Cadastrar');
    $('#a-tab-lista').tab('show');
    $('a[href="#lista"]').hide();
    $('#slide-preview').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
})

//verifica ao enviar - se o form está vazio
$('#form-slide').on('submit', function () {
    if ($('#slide_id').val() <= 0 && $.trim($('#file').val()) == '') {
        _alert_error('<p><i class="glyphicon glyphicon-warning-sign"></i> Uma imagem deve ser selecionada!</p>');
        return false;
    }
})
