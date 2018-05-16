/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 07.05.2018
 */

jQuery(document).ready(function ($) {

    var per_page_init = $('#edit-scrm-per-page').val();

    function refreshTable() {

        var columns = {
            enable: {},
            disable: {},
            settings: {}
        };

        $('.columns-enable').find('li').each(function () {
            columns.enable[$(this).attr('id')] = $.trim($(this).text());
        });

        $('.columns-disable').find('li').each(function () {
            columns.disable[$(this).attr('id')] = $.trim($(this).text());
        });
        
        columns.settings['per_page'] = $('#edit-scrm-per-page').val();
        
        columns.settings['small_image'] = $('#edit-scrm-small-image:checked').val();

        $.post(
                '/wp-admin/admin-ajax.php',
                {
                    action: 'scrm_update_data',
                    data: {
                        type: 'user',
                        id: $('#current-user').val(),
                        key: 'scrm-multi-table',
                        value: columns
                    }
                },
                function () {
                    if ( per_page_init == $('#edit-scrm-per-page').val() )
                        location.reload(true);
                    else
                        location.replace('?page=scrm');
                }
        );
    }
    
    function editName() {
        
        $(this).find('span').toggleClass('dashicons-edit').toggleClass('dashicons-dismiss');
        var el = $(this).parent('li');
        el.find('.column-name').toggle();
        el.find('input').toggle().keyup(function() {
            el.find('.column-name').text($(this).val());
        });
    }

    $('#sortable1, #sortable2').sortable({
        items: 'li',
        cursor: 'move',
        connectWith: '.columns-sortable',
        opacity: 0.5,
        placeholder: 'ui-state-highlight',
        update: function () {
            //refreshTable();
        }
    }).disableSelection();

    $('.toggle-control').click(function () {
        $('.table-control').slideToggle();
    });

    $('#control-submit').click(function (e) {
        refreshTable();
    });
    
    $.each($('.control-columns').find('li'), function() {
        
        $(this).on('click', '.button-link', editName);
    });
});
