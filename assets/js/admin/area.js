var baseUri = $('base').attr('href');

//controla tabs 
$(function() {
    $('#a-tab-lista').tab('show');
    $('a[href="#novo"]').on('click', function() {
        $('#slide_id').val('');
        $('#slide_titulo').val('');
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
    })
    $('a[href="#lista"]').on('click', function() {
        $(this).hide();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    })
    //sortable para ordenar pos das categorias
    $(".dragger").sortable({
        opacity: 0.9,
        placeholder: "ui-state-highlight",
        cursor: "move",
        start: function(event, ui) {
            //$(this).find('.td-nome').css('width','90%');
            //$(this).find('.td-id').hide;
            //$(this).find('.td-ctrl').hide();
        },
        stop: function() {
            //$(this).find('.td-ctrl').show();
            var sorted = $(this).sortable('serialize');
            //console.log(sorted)
            var url = baseUri + '/painel/area/UpdatePos/'
            $.post(url, {
                item: sorted
            }, function(data) {
                //console.log(data)
            })
        }
    });    
})

//editar area
$('.btn-edit').on('click', function() {
    var area_id = $(this).attr('id');
    var area_nome = $(this).attr('name');
    $('#area_id').val(area_id);
    $('#area_nome').val(area_nome);
    $('#a-tab-edit').tab('show');
    $("#btn-add-update span").text('Atualizar')
    $("#btn-add-update i").removeClass('glyphicon-plus-sign').addClass('glyphicon-refresh');
    $('#area_nome').focus();
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
})
//remove registro do banco
$('.btn-remove').on('click', function() {
    var area_id = $(this).attr('id');
    var area_nome = $(this).attr('name');
    var url = baseUri + "painel/area/remover/";
    $('#ModalRemove').modal('show');

    $('#btn-confirm-remove').on('click', function() {
        $.post(url, {area_id: area_id}, function(data) {
            $('#ModalRemove').modal('hide');
            _alert('Área removida com sucesso! ');
            //exclui a tr do registo    
            $('#tr_' + area_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="glyphicon glyphicon-info-sign"></i> Você ainda não cadastrou nenhuma área!</p>')
            }
            //reset form
            $('#area_id').val('');
            $('#area_nome').val('');
            $("#btn-add-update span").text('Cadastrar')
            $("#btn-add-update i").removeClass('glyphicon-refresh').addClass('glyphicon-plus-sign');
        })
    })

})

//cancela editar // reset form
$('#btn-add-update-cancel').on('click', function() {
    $('#a-tab-lista').tab('show');
    $("#btn-add-update span").text('Cadastrar')
    $("#btn-add-update i").removeClass('glyphicon-refresh').addClass('glyphicon-plus-sign');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
})


//cancela novo // reset form
$('#btn-add-update-cancel').on('click', function() {
    $('#a-tab-lista').tab('show');
    $("#btn-add-update span").text('Cadastrar')
    $("#btn-add-update i").removeClass('glyphicon-refresh').addClass('glyphicon-plus-sign');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
})

//verifica ao enviar - se o form está vazio
$('#form-area').on('submit', function() {
    if ($.trim($('#area_nome').val()) == '') {
        _alert_error('<p><i class="glyphicon glyphicon-warning-sign"></i> Um nome deve ser informado!</p>');
        $('#area_nome').focus();
        $('#area_nome').addClass('text-danger');
        $('#area_nome').parent().addClass('has-error');
        $('#area_nome').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" id="remove-me"></span>');
        return false;
    }
})

function resetMe()
{
    $('#area_id').val('');
    $('#area_nome').val('');
    $('#area_nome').removeClass('text-danger');
    $('#area_nome').parent().removeClass('has-error');
    $('#remove-me').remove();
}