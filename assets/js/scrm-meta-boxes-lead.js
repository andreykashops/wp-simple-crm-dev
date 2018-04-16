/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 10.04.2018
 */

jQuery(document).ready(function ($) {

    $('#scrm-lead-contact-id').change(function () {

        var post_id = $(this).find(":selected").val();

        $.post(
                '/wp-admin/admin-ajax.php',
                {
                    action: 'scrm_refresh_contact_info',
                    post_id: post_id
                },
                function (response) {

                    $('#scrm-lead-contact .scrm-contact-block-1').replaceWith(response);
                    $('#scrm-lead-contact-id').val(post_id);
                },
                'html'
        );

        $.post(
                '/wp-admin/admin-ajax.php',
                {
                    action: 'scrm_refresh_contact_image',
                    post_id: post_id
                },
                function (response) {

                    $('#scrm-lead-contact-image .inside').html(response);
                },
                'html'
        );
    });

    var media_frame;

    $('#scrm-lead-contact-image').on('click', '#upload_lead_contact_image', function (event) {
        
        event.preventDefault();
        
        if (media_frame) {
            
            media_frame.open();
        } else {

            media_frame = wp.media.frames.media_frame = wp.media({
                title: $(this).attr('title'),
                button: {
                    text: $(this).text()
                },
                multiple: false
            });

            media_frame.on('select', function () {

                var img = media_frame.state().get('selection').first().toJSON();

                $("#scrm-contact-thumbnail-id").val(img.id);
                $("#scrm-lead-contact-image img").attr('src', img.url).attr('width', img.width).attr('height', img.height).show();
                $("#scrm-lead-contact-image a").attr('id', 'remove_lead_contact_image').text('Remove contact image');
            });

            media_frame.open();
        }
    }).on('click', '#remove_lead_contact_image', function (event) {
        
        event.preventDefault();
        
        $('#scrm-contact-thumbnail-id').val('');
        $('#scrm-lead-contact-image img').attr('src', '').attr('width', '0').attr('height', '0').hide();
        $("#scrm-lead-contact-image a").attr('id', 'upload_lead_contact_image').text('Set contact image');
    });
});
