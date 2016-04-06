var baseUri = $('base').attr('href');

//controla tabs 
$(function() {
    $('#a-tab-lista').tab('show');
    $('a[href="#novo"]').on('click', function() {
        resetMe();
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
    });
    $('a[href="#lista"]').on('click', function() {
        resetMe();
        $(this).hide();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    });
    //sortable para ordenar pos das categorias
    $(".dragger").sortable({
        opacity: 0.9,
        placeholder: "ui-state-highlight",
        cursor: "move",
        start: function(event, ui) {
            //$(this).find('.td-nome').css('width','90%');
            //$(this).find('.td-id').hide;
            $(this).find('.td-ctrl').hide();
        },
        stop: function() {
            $(this).find('.td-ctrl').show();
            var sorted = $(this).sortable('serialize');
            //console.log(sorted)
            var url = baseUri + '/painel/categoria/UpdatePos/';
            $.post(url, {
                item: sorted
            }, function(data) {
                //console.log(data)
            });
        }
    });
});
//editar categoria
$('.btn-edit').on('click', function() {
    var categoria_id = $(this).attr('id');
    var categoria_nome = $(this).attr('name');
    $('#categoria_id').val(categoria_id);
    $('#categoria_nome').val(categoria_nome);
    $('#a-tab-edit').tab('show');
    $("#btn-add-update span").text('Atualizar')
    $("#btn-add-update i").removeClass('fa-check-circle-o').addClass('fa-refresh');
    $('#categoria_nome').focus();
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
});
//remove registro do banco
$('.btn-remove').on('click', function() {
    var categoria_id = $(this).attr('id');
    var categoria_nome = $(this).attr('name');
    var url = baseUri + "painel/categoria/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function() {
        $.post(url, {categoria_id: categoria_id}, function(data) {
            $('#ModalRemove').modal('hide');
            _alert('Categoria removida com sucesso! ');
            //exclui a tr do registo    
            $('#li_' + categoria_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhuma categoria!</p>');
            }
            //reset form
            resetMe();
        });
    });
});
//cancela novo // reset form
$('#btn-add-update-cancel').on('click', function() {
    $('#a-tab-lista').tab('show');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
});
//verifica ao enviar - se o form está vazio
$('#form-categoria').on('submit', function() {
    if ($.trim($('#categoria_nome').val()) == '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um nome deve ser informado!</p>');
        $('#categoria_nome').focus();
        $('#categoria_nome').addClass('text-danger');
        $('#categoria_nome').parent().addClass('has-error');
        $('#categoria_nome').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
});

function resetMe()
{
    $("#btn-add-update span").text('Cadastrar');
    $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-check-circle-o');
    $('#categoria_id').val('');
    $('#categoria_nome').val('');
    $('#categoria_nome').removeClass('text-danger');
    $('#categoria_nome').parent().removeClass('has-error');
    $('#remove-me').remove();
}