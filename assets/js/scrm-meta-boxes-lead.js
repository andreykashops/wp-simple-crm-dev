/* 
 * Created by Roman Hofman
 * Date: 10.04.2018
 */

jQuery(document).ready(function ($) {

    $('#scrm-lead-contact-select').change(function () {

        var post_id = $(this).find(":selected").val();

        $.post(
                '/wp-admin/admin-ajax.php',
                {
                    action: 'scrm_refresh_contact_info',
                    data: post_id
                },
                function (response, status) {

                    $('#scrm-lead-contact-info').html(response);
                },
                'html'
        );
    });
});
