$(document).ready(function() {
    let url = window.location.origin;

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#addCartButton', function(e) {
        e.preventDefault();

        let me = $(this).data('product');
        $.ajax({
            url: url+'/carts/add-product-to-cart/'+me,
            type: 'GET',
            dataType: 'JSON',
            success: function(res)
            {
                swal({
                    title: res.title,
                    text: res.message,
                    icon: res.type,
                    button: false,
                    closeModal: true,
                    timer: 2000
                });

                $('span#countShoppingCart').text(`(${res.count})`);
            },
            error: function(res)
            {
                console.log(res.responseJSON.message);
            }
        })
        // alert(me);
    });

    $(document).on('click', '#addWishlistButton', function(e) {
        e.preventDefault();

        let me = $(this).data('product');

        alert(me);
    });
});
