var baseUri = $('base').attr('href');

//controla tabs 
$(function() {
    $('.fone').mask('(99) 9999-99990');
    $('a[href="#novo"]').on('click', function() {
        resetMe();
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
    })
    $('a[href="#lista"]').on('click', function() {
        resetMe();
        $(this).hide();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    })
})

//remove registro do banco
$('.btn-remove').on('click', function() {
    var depoimento_id = $(this).attr('id');
    var url = baseUri + "painel/depoimento/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function() {
        $.post(url, {depoimento_id: depoimento_id}, function(data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Depoimento removido com sucesso! ');
            //exclui a tr do registo    
            $('#tr_' + depoimento_id).fadeOut();
        })
    })
})

//remove registro do banco
$('.btn-edit').on('click', function() {

    var depoimento_id = $(this).attr('id');
    var depoimento_foto = $(this).attr('foto');
    var depoimento_autor = $(this).attr('autor');
    var depoimento_cargo = $(this).attr('cargo');
    var depoimento_status = $(this).attr('status');
    var depoimento_cliente = $(this).attr('cliente');
    var depoimento_texto = $(this).attr('texto');
    var depoimento_data = $(this).attr('data');

    if (depoimento_foto != "") {
        $('#slide-preview img').attr('src', baseUri + '/midia/cliente/' + depoimento_foto);
        $('#slide-preview').removeClass('hide').show();
    }
    $('#depoimento_id').val(depoimento_id);
    $('#depoimento_autor').val(depoimento_autor);
    $('#depoimento_cargo').val(depoimento_cargo);
    $('#depoimento_status').val(depoimento_status);
    $('#depoimento_cliente').val(depoimento_cliente);
    $('#depoimento_texto').val(depoimento_texto);
    $('#depoimento_data').val(depoimento_data);
    $('#a-tab-edit').tab('show');

    $('#btn-add-update-cliente').text('Atualizar');
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
});

//cancelar editar
$('#btn-add-update-cancel').on('click', function() {
    $('#btn-add-update-cliente').text('Gravar');
    $('#a-tab-lista').tab('show');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
});


function resetMe() {
    $('#slide-preview').hide();
    $('#depoimento_id').val('');
    $('#depoimento_autor').val('');
    $('#depoimento_cargo').val('');
    $('#depoimento_status').val('');
    $('#depoimento_cliente').val('');
    $('#depoimento_texto').val('');
    $('#depoimento_data').val('');
    $('#filedata').val('');
}
//verifica ao enviar - se o form está vazio
$('#form-cliente').on('submit', function() {
    if ($('#depoimento_id').val() <= 0 && $.trim($('#depoimento_cliente').val()) === '') {
        _alert_error('<p><i class="fa  fa-warning"></i> Um cliente deve ser selecionado!</p>');
        return false;
    }
});