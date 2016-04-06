$(function () {    
    $('#form-contato').on('submit', function () {
        //var browser = navigator.userAgent.toLowerCase();
        //if (browser.indexOf("safari") != -1 && browser.indexOf("chrome") == -1) {
        //browser = 'safari';
        if ($.trim($('#contato_nome').val()) === "") {
            $('#contato_nome').focus();
            return false;
        }
        if ($.trim($('#contato_email').val()) === "") {
            $('#contato_email').focus();
            return false;
        }
        /*
        if ($.trim($('#contato_telefone').val()) === "") {
            $('#contato_telefone').focus();
            return false;
        }
        if ($.trim($('#contato_assunto').val()) === "") {
            $('#contato_assunto').focus();
            return false;
        }
        */
        if ($.trim($('#contato_mensagem').val()) === "") {
            $('#contato_mensagem').focus();
            return false;
        }
        //}
        $('#btn-contato-enviar').html('<i class="fa fa-refresh fa-spin"></i> Enviando...');
        var url = baseUri + '/contato/enviar/';
        var dados = $('#form-contato').serialize();
        
        $.post(url, {dados: dados}, function (data) {
            $('#contato_nome').focus();
            if (data == 0) {
                $('#btn-contato-enviar').html('<i class="fa fa-thumbs-up"></i> Enviada');
                setTimeout(function () {
                    $('#btn-contato-enviar').html('<i class="fa fa-send-o"></i> Enviar');
                    $('#form-contato :input').val('');
                }, 7000);
            } else {
                $('#btn-contato-enviar').html('<i class="fa fa-send-o"></i> Tentar Novamente');
            }
            return false;
        });
        return false;
    });
    
    $('#form-contato-cliente').on('submit', function () {
        if ($.trim($('#contato_nome').val()) === "") {
            $('#contato_nome').focus();
            return false;
        }
        if ($.trim($('#contato_email').val()) === "") {
            $('#contato_email').focus();
            return false;
        }
        /*
        if ($.trim($('#contato_telefone').val()) === "") {
            $('#contato_telefone').focus();
            return false;
        }
        */
        if ($.trim($('#contato_mensagem').val()) === "") {
            $('#contato_mensagem').focus();
            return false;
        }
        //}
        $('#btn-contato-enviar').html('<i class="fa fa-refresh fa-spin"></i> Enviando...');
        var url = baseUri + '/contato/enviar_empresa/';
        var dados = $('#form-contato-cliente').serialize();
        
        $.post(url, {dados: dados}, function (data) {
            $('#contato_nome').focus();
            if (data == 0) {
                $('#btn-contato-enviar').html('<i class="fa fa-thumbs-up"></i> Enviada');
                setTimeout(function () {
                    $('#btn-contato-enviar').html('<i class="fa fa-send-o"></i> Enviar');
                    $('#form-contato-cliente :input').val('');
                }, 7000);
            } else {
                $('#btn-contato-enviar').html('<i class="fa fa-send-o"></i> Tentar Novamente');
            }
            return false;
        });
        return false;
    });

});
