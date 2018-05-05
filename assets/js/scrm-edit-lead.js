/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 04.05.2018
 */

jQuery(document).ready(function ($) {
    
    var el = $('td.status');
    var post_id = el.parent('tr').attr('id').split('-')[1];
    
    el.find('select').change(function () {
        
        var status = $(this).find(':selected').val();
        
        $.post(
                '/wp-admin/admin-ajax.php',
                {
                    action: 'scrm_update_post_meta',
                    data: {
                        post_id: post_id,
                        status: status
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
