var baseUri = $('base').attr('href');

//controla tabs 
$(function () {
    $('a[href="#novo"]').on('click', function () {
        resetMe();
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
    })
    $('a[href="#lista"]').on('click', function () {
        resetMe();
        $(this).hide();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
    })
})

//remove registro do banco
$('.btn-remove').on('click', function () {
    var usuario_id = $(this).attr('id');
    var url = baseUri + "painel/usuario/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {usuario_id: usuario_id}, function (data) {
            //reset form
            $('#ModalRemove').modal('hide');
            _alert('Usário removido com sucesso! ');
            //exclui a tr do registo    
            $('#tr_' + usuario_id).fadeOut();
        })
    })
})

//remove registro do banco
$('.btn-edit').on('click', function () {

    var usuario_id = $(this).attr('id');
    var usuario_nome = $(this).attr('data-nome');
    var usuario_login = $(this).attr('data-login');
    var usuario_email = $(this).attr('data-email');
    $('#usuario_senha').removeAttr('required');
    $('#usuario_id').val(usuario_id);
    $('#usuario_nome').val(usuario_nome);
    $('#usuario_email').val(usuario_email);
    $('#usuario_login').val(usuario_login);
    $('#a-tab-edit').tab('show');
    $('#btn-add-update-usuario').text('Atualizar');
    $('#btn-add-update-usuario').parent().find('i').removeClass('fa-plus-circle').addClass('fa-refresh');
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
});

//cancelar editar
$('#btn-add-update-cancel').on('click', function () {
    $('#btn-add-update-usuario').text('Cadastrar');
    $('#a-tab-lista').tab('show');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
});


function resetMe() {
    $('#usuario_id').val('');
    $('#usuario_email').val('');
    $('#usuario_nome').val('');
    $('#usuario_login').val('');
    $('#usuario_senha').val('');
    $('#usuario_senha').attr('required', 'required');
    $('#btn-add-update-usuario').parent().find('i').addClass('fa-plus-circle').removeClass('fa-refresh');
}
//verifica ao enviar - se o form está vazio
$('#form-usuario').on('submit', function () {

    if ($('#usuario_id').val() <= 0 && $.trim($('#usuario_senha').val()) === '') {
        _alert_error('<p><i class="fa  fa-warning"></i> A senha deve ser informada!</p>');
        return false;
    }

    if ($('#usuario_id').val() <= 0 && $.trim($('#usuario_login').val()) === '') {
        _alert_error('<p><i class="fa  fa-warning"></i> Um login deve ser informado!</p>');
        return false;
    }
});