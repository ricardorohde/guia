$(function() {
    $('.button-checkbox').each(function() {
        // Settings
        var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'fa fa-check-square-o'
                    },
                    off: {
                        icon: 'fa fa-square-o'
                    }
                };
        // Event Handlers
        $button.on('click', function() {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function() {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');
            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");
            // Set the button's icon
            $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);
            // Update the button's color
            if (isChecked) {
                $button
                        //.removeClass('btn-default')
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-' + color);
                //.addClass('btn-' + color + ' active');
            }
            else {
                $button
                        //.removeClass('btn-' + color + ' active')
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-' + color);
                //.addClass('btn-default');
            }

            if ($('.button-checkbox').find('input:checkbox:checked').length < 1) {
                $('#btn-remove-selecao').html('<i class="fa fa-trash"></i>');
            } else {
                $('#btn-remove-selecao').html('<i class="fa fa-trash"></i> Remover Selecionadas');
            }
            //console.log($('.button-checkbox').find('input:checkbox:checked').length)
        }
        // Initialization
        function init() {
            updateDisplay();
            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});