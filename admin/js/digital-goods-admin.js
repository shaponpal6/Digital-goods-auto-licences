(function ($) {
    'use strict';
    $(document).ready(function () {
        $('.dl_add_licence').on('click', function (e) {
            $('#dl_licence_container').show();
            $('#dl_licence_view_container').hide();
        });
        $('.dl_view_licence').on('click', function (e) {
            $('#dl_licence_container').hide();
            $('#dl_licence_view_container').show();
        });
        $('#dl_licence_add').on('click', function (e) {
            e.preventDefault();
            console.log(e);
            var product_id = $(this).attr('data-id');
            var licence = $('#dl_licence_key').val();
            var item = $('#dl_licence_item').val();
            if (licence === '') return alert('Add Licence ket');
            console.log('product_id', product_id);
            console.log('licence', licence);
            console.log('item', item);

            var data = {
                'action': 'dl_licence_admin_ajax',
                'product_id': product_id,
                'licence': licence,
                'item': item !== '' ? parseInt(item) : 1,
            };

            // var url = new URL(window.location.href);
            // url.searchParams.set('sp_poll_add', 'poll');
            // console.log(url);
            // console.log(url.href);
            console.log(dl_licence_obj.ajax_url);

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
                // $('#dl_licence_container').hide()
                // $('#dl_licence_view_container').show()
                // console.log('Voted Completed: ' + response);
                window.location.href = window.location.pathname + window.location.search + window.location.hash;
                // creates a history entry
            });
        });


        // Table Execute
        var tableCont = document.querySelector('#tabla')

        function scrollHandle(e) {
            console.log(e);
            var scrollTop = e.target.scrollTop;
            e.target.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';
        }

        if (tableCont) {
            console.log('tttabls active');
            tableCont.addEventListener('scroll', scrollHandle);
        }
    });

})(jQuery);
