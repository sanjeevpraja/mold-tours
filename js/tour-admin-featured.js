jQuery(document).ready(function ($) {
    $('.tour-featured-toggle').on('click', function (e) {
        e.preventDefault();
        var $icon = $(this).find('.dashicons');
        var postId = $(this).data('post-id');

        $.post(tourFeatured.ajax_url, {
            action: 'tour_toggle_featured',
            post_id: postId,
            nonce: tourFeatured.nonce
        }, function (response) {
            if (response.success) {
                $icon.removeClass('dashicons-star-filled dashicons-star-empty')
                     .addClass(response.data.icon);
            }
        });
    });
});
