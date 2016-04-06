//baseuri recupera a url atual do sistema
var baseUri = $('base').attr('href');

//exibe a barra suspensa atualizar quando rolar a tela
$(function () {
    var elm = $('#top-menu'), pos = elm.offset();
    $(window).scroll(function () {
        if ($(this).scrollTop() >= pos.top + $('#top-menu').height()) {
            $('.toolbar-save').removeClass('hide').fadeIn();
            //$('#top-menu').fadeOut();
        } else {
            $('.toolbar-save').fadeOut();
            //$('#top-menu').fadeIn();
        }
    });
    //botao da barra suspensa atualiza /submete o formulario
    $("#trig-submit").on("click", function () {
        $("#form-produto").trigger("submit");
    });
});
//controla tabs 
$(function () {
    //$('#a-tab-novo').tab('show');
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('a[href="#novo"]').on('click', function () {
        $('#slide_id').val('');
        $('#slide_titulo').val('');
        $(this).hide();
        $('a[href="#lista"]').removeClass('hide').fadeIn();
        resetMe();
        summerNote();
    });
    $('a[href="#lista"]').on('click', function () {
        //$('.summernote').code('');
        $(this).hide();
        $('a[href="#novo"]').removeClass('hide').fadeIn();
        summerNote();
    });
    //sortable para ordenar pos das produtos
    $(".dragger").sortable({
        opacity: 0.9,
        placeholder: "ui-state-highlight",
        cursor: "move",
        start: function (event, ui) {
            //$(this).find('.td-nome').css('width','90%');
            //$(this).find('.td-id').hide;
            $(this).find('.td-ctrl').hide();
        },
        stop: function () {
            $(this).find('.td-ctrl').show();
            var sorted = $(this).sortable('serialize');
            //console.log(sorted)
            var url = baseUri + '/painel/produto/UpdateFotosPos/';
            $.post(url, {
                item: sorted
            }, function (data) {
                //console.log(data)
            });
        }
    });
});

//sortable fotos
$(".dragger").sortable({
    opacity: 0.8,
    placeholder: "ui-state-highlight",
    cursor: "move",
    stop: function () {
        var sorted = $(this).sortable('serialize');
        var url = baseUri + '/painel/produto/UpdateFotosPos/';
        $.post(url, {
            item: sorted
        }, function (data) {

        });
    }
});
$('.foto-zoom').on('click', function () {
    var foto_id = $(this).attr('id');
    var foto_nome = $(this).attr('data-nome');
    var foto_url = $(this).attr('data-url');
    $('#ModalZoom #foto_spot').html('');
    $('#ModalZoom #foto_nome').val('');
    $('#ModalZoom .modal-title').html('Legenda: ' + foto_nome);
    $('#ModalZoom #foto_nome').val(foto_nome);
    $('#ModalZoom #foto_id').val(foto_id);
    $('<img />')
            .addClass('img-responsives')
            .attr('src', foto_url)
            .css('max-width', '500px')
            .css('max-height', '300px')
            .appendTo($('#ModalZoom #foto_spot'));
    $('#ModalZoom').modal('show');
    setTimeout(function () {
        $('#ModalZoom #foto_nome').focus();
    }, 1000);
    $('#foto_nome').on('keyup', function () {
        $('#ModalZoom .modal-title').html('Legenda: ' + $(this).val());
        //$('#d_' + foto_id + ' #f_' + foto_id).text($(this).val());                   
    });
});
$('#btn-update-foto').on('click', function () {
    var foto_nome = $.trim($('#ModalZoom #foto_nome').val());
    var foto_id = $('#ModalZoom #foto_id').val();
    var url = baseUri + '/painel/produto/fotoUpdate/';
    $('#' + foto_id).attr('data-nome', foto_nome);
    $.post(url, {foto_id: foto_id, foto_nome: foto_nome}, function (data) {
        $('#ModalZoom').modal('hide');
        $('#d_' + foto_id + ' #f_' + foto_id).text(foto_nome);
    });
});
$('#btn-remove-selecao').on('click', function () {
    if ($('.checkbox-remove:checked').length >= 1) {
        $('#ModalRemove').modal('show');
    } else {
        _alert_error('<p><i class="fa fa-warning"></i> Nenhuma foto selecionada</p>');
    }
})
//confirma remocao
$('#btn-confirm-remove').on('click', function () {
    var lista = [];
    $('.checkbox-remove:checked').each(function (k, v) {
        lista.push({foto_id: $(this).val(), foto_url: $(this).attr('data-url')});
    });
    var url = baseUri + '/painel/produto/fotoRemove/';
    $.post(url, {lista: lista}, function (data) {
        $(lista).each(function (k, v) {
            $('#d_' + v.foto_id).fadeOut();
        });
        $('#ModalRemove').modal('hide');
    });
});

//editar produto
$('.btn-edit').on('click', function () {
    var produto_id = $(this).attr('id');
    var produto_nome = $(this).attr('name');
    var produto_categoria = $(this).attr('categoria');
    var produto_descricao = $('#descricao_' + produto_id).val();
    var produto_codigo = $(this).attr('codigo');
    var produto_valor = $(this).attr('valor');
    var produto_ativo = $(this).attr('ativo');
    var produto_show = $(this).attr('show');
    var produto_meta_desc = $(this).attr('meta_desc');
    var produto_meta_key = $(this).attr('meta_key');
    $('#produto_id').val(produto_id);
    $('#produto_nome').val(produto_nome);
    $('#produto_categoria').val(produto_categoria);
    $('#produto_descricao').val(produto_descricao);
    $('#produto_codigo').val(produto_codigo);
    $('#produto_valor').val(produto_valor);
    $('#produto_ativo').val(produto_ativo);
    $('#produto_show').val(produto_show);
    $('#produto_meta_desc').val(produto_meta_desc);
    $('#produto_meta_keywords').val(produto_meta_key);

    var categoria_nome = $('#produto_categoria option:selected').text();
    $('#sp_produto_nome').html(categoria_nome + ' <i class="fa fa-angle-right"></i> ' + produto_nome);
    $('#hsp_produto_nome').removeClass('hide').show();
    $('#a-tab-edit').tab('show');
    $("#btn-add-update span").text('Atualizar');
    $("#btn-add-update i").removeClass('fa-plus-circle').addClass('fa-refresh');
    $('#produto_nome').focus();
    $('a[href="#novo"]').hide();
    $('a[href="#lista"]').removeClass('hide').fadeIn();
    summerNote();
    $('.summernote').code(produto_descricao);
});
//remove registro do banco
$('.btn-remove').on('click', function () {
    var produto_id = $(this).attr('id');
    var produto_nome = $(this).attr('name');
    var url = baseUri + "painel/produto/remover/";
    $('#ModalRemove').modal('show');
    $('#btn-confirm-remove').on('click', function () {
        $.post(url, {produto_id: produto_id}, function (data) {
            $('#ModalRemove').modal('hide');
            _alert('Produto removido com sucesso! ');
            //exclui a tr do registo    
            $('#li_' + produto_id).fadeOut().remove();
            //se não houverem mais registros / esconde a table
            if ($('#table-list > tbody > tr ').length <= 0) {
                $('#table-list').html('<p class="well well-sm"><i class="fa fa-info-circle"></i> Você ainda não cadastrou nenhum produto!</p>')
            }
            //reset form
            $('#produto_id').val('');
            $('#produto_nome').val('');
            $("#btn-add-update span").text('Cadastrar');
            $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-plus-circle');
        });
    });
});
//cancela editar // reset form
$('#btn-add-update-cancel').on('click', function () {
    $('#a-tab-lista').tab('show');
    $("#btn-add-update span").text('Cadastrar');
    $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-plus-circle');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    //$('.summernote').code('');
    resetMe();
    summerNote();
});

//cancela novo // reset form
$('#btn-add-update-cancel').on('click', function () {
    $('#a-tab-lista').tab('show');
    $("#btn-add-update span").text('Cadastrar');
    $("#btn-add-update i").removeClass('fa-refresh').addClass('fa-plus-circle');
    $('a[href="#lista"]').hide();
    $('a[href="#novo"]').removeClass('hide').fadeIn();
    resetMe();
    summerNote();
});

//verifica ao enviar - se o form está vazio
$('#form-produto').on('submit', function () {
    if ($.trim($('#produto_nome').val()) === '') {
        _alert_error('<p><i class="fa fa-warning"></i> Um nome deve ser informado!</p>');
        $('#produto_nome').focus();
        $('#produto_nome').addClass('text-danger');
        $('#produto_nome').parent().addClass('has-error');
        $('#produto_nome').parent().append('<span class="fa fa-trash form-control-feedback" id="remove-me"></span>');
        return false;
    }
});

function resetMe() {
    $('#form-produto input[type=text]').val('');
    $('#produto_descricao').val('');
    $('#produto_id').val('');
    $('#produto_categoria').val('');
    $('#produto_nome').removeClass('text-danger');
    $('#produto_nome').parent().removeClass('has-error');
    $('#remove-me').remove();
}

function novo() {
    resetMe();
    $('a[href=\"#novo\"]').tab('show');
    $('a[href=\"#lista\"]').removeClass('hide').fadeIn();
    $('a[href=\"#novo\"]').hide();
    summerNote();
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
        height: 350,
        lang: 'pt-BR'
    });
    $('#side-bar').css('min-height', $('#panel-content').height() + 2);
}