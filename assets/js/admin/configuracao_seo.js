//baseuri recupera a url atual do sistema
var baseUri = $('base').attr('href');

//verifica ao enviar - se o form está vazio
$('#form-sep').on('submit', function () {
    if ($.trim($('#site_meta_title').val()) === '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um título deve ser informado!</p>');
        $('#site_meta_title').focus();
        $('#site_meta_title').addClass('text-danger');
        $('#site_meta_title').parent().addClass('has-error');
        $('#site_meta_title').parent().append('<span class="fa fa-trash form-control-feedback" id="remove-me"></span>');
        return false;
    }
});
