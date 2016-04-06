//baseuri recupera a url atual do sistema
var baseUri = $('base').attr('href');
//controla tabs 
$(function () {
    summerNote();
});


//verifica ao enviar - se o form está vazio
$('#form-site').on('submit', function () {
    if ($.trim($('#site_title').val()) === '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um título deve ser informado!</p>');
        $('#site_title').focus();
        $('#site_title').addClass('text-danger');
        $('#site_title').parent().addClass('has-error');
        $('#site_title').parent().append('<span class="fa fa-trash form-control-feedback" id="remove-me"></span>');
        return false;
    }
});


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
    if ($(window).width() >= 480) {
        $('#side-bar').css('min-height', $('#panel-content').height() + 2);
    }
}
