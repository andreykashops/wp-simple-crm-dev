/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 18.04.2018
 */

jQuery(document).ready(function ($) {

    $.fn.setCurrent = function () {
        $(this).on('click', 'tr', function (e) {
            $(this).parent().resetCurrent();
            $(this).addClass('current');
        });
    };

    $.fn.resetCurrent = function () {
        $(this).find('tr').removeClass('current');
    };

    $.fn.toggleField = function () {
        $(this).last('tr').on('click', '.field-title', function (e) {
            $(this).parent().find('.field-data').toggle();
        });
    };

    $.fn.refreshOder = function () {
        $(this).each(function (i, val) {
            $(val).find('.order').html('<span>' + (i + 1) + '</span>');
        });
    };

    $.fn.changeLabel = function () {
        $(this).on('keyup', 'input', function () {
            var text = $(this).val();
            if (!text) {
                text = '...';
            }
            $(this).parents('td.field').find('.field-title p .label-info').html(text);
        });
    };

    $.fn.changeType = function () {
        $(this).on('change', 'select', function () {

            var field = $(this).parents('.custom-field');
            var type = $(this).val();
            var text = $(this).find('option:selected').text();

            if (type) {

                $.post(
                        '/wp-admin/admin-ajax.php',
                        {
                            action: 'scrm_refresh_custom_field_values',
                            prefix: prefix,
                            id: id,
                            i: field.find('.order span').text() - 1,
                            type: type
                        },
                        function (response) {

                            field.find('.field-values').empty().html(response);
                            field.find('.type-info').empty().html(text);
                        },
                        'html'
                        );
            }
        });
    }

    $('#sortable').sortable({
        items: 'tr',
        cursor: 'move',
        axis: 'y',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        helper: 'clone',
        opacity: 0.5,
        placeholder: '',
        start: function () {

        },
        stop: function () {

            $('.ui-sortable tr').refreshOder();
        }
    });
    $('#sortable').disableSelection();

    $('.scrm-fields-table').on('click', 'a.add_row', function (event) {

        event.preventDefault();
        
        $.post(
                '/wp-admin/admin-ajax.php',
                {
                    action: 'scrm_get_custom_field',
                    prefix: prefix,
                    id: id,
                    i: $('.ui-sortable').find('tr').length
                },
                function (response) {

                    $('.ui-sortable').append(response);

                    $('.ui-sortable tr').refreshOder();
                    $('.ui-sortable .field-label').changeLabel();
                    $('.ui-sortable .field-type').changeType();
                },
                'html'
                );
    }).on('click', 'a.remove_row', function (event) {

        event.preventDefault();
        
        var size = $('.ui-sortable').find('tr').length;

        if (size > 0) {
            $('.ui-sortable').find('.current').not('.built-in').remove();
            $('.ui-sortable tr').refreshOder();
        }
    });

    $('.ui-sortable').setCurrent();
    $('.ui-sortable').toggleField();

    $('.ui-sortable tr').refreshOder();
    $('.ui-sortable .field-label').changeLabel();
    $('.ui-sortable .field-type').changeType();
});   