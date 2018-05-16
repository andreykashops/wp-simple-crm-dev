/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 04.05.2018
 */

jQuery(document).ready(function ($) {
    
    var list = $('#the-list').find('tr');
    
    $.each( list, function() {
        
        var post_id = $(this).attr('id').split('-')[1];
        
        $(this).on('change', 'select.edit-status', function () {

            var status = $(this).find(':selected').val();

            $.post(
                    '/wp-admin/admin-ajax.php',
                    {
                        action: 'scrm_update_data',
                        data: {
                            type: 'post',
                            id: post_id,
                            key: 'status',
                            value: status
                        }
                    },
                    function (response) {

                        if (response == 0) 
                            alert( 'Error : Status not changed.' );
                    },
                    'html'
            );
        });
    });
});
