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
    //sortable para ordenar pos das grupos
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
            var url = baseUri + '/painel/grupo/UpdatePos/';
            $.post(url, {
                item: sorted
            }, function(data) {
                //console.log(data)
            });
        }
    });
});
//editar grupo
$('.btn-edit').on('click', function() {
    var grupo_id = $(this).attr('id');
    var grupo_nome = $(this).attr('name');
    $('#grupo_id').val(grupo_id);
    $('#grupo_nome').val(grupo_nome);
    $('#a-tab-edit').tab('show');
    $("#btn-add-update span").text('Atualizar')
    $("#btn-add-update i").removeClass('fa-check-circle-o').addClass('fa-refresh');
    $('#grupo_nome').focus();
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
});
//remove registro do banco
$('.btn-remove').on('click', function() {
    var grupo_id = $(this).attr('id');
    var grupo_nome = $(this).attr('name');
    var url = baseUri + "painel/grupo/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function() {
        $.post(url, {grupo_id: grupo_id}, function(data) {
            $('#ModalRemove').modal('hide');
            _alert('Grupo removida com sucesso! ');
            //exclui a tr do registo    
            $('#li_' + grupo_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhuma grupo!</p>');
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
$('#form-grupo').on('submit', function() {
    if ($.trim($('#grupo_nome').val()) == '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um nome deve ser informado!</p>');
        $('#grupo_nome').focus();
        $('#grupo_nome').addClass('text-danger');
        $('#grupo_nome').parent().addClass('has-error');
        $('#grupo_nome').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
});

function resetMe()
{
    $("#btn-add-update span").text('Cadastrar');
    $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-check-circle-o');
    $('#grupo_id').val('');
    $('#grupo_nome').val('');
    $('#grupo_nome').removeClass('text-danger');
    $('#grupo_nome').parent().removeClass('has-error');
    $('#remove-me').remove();
}