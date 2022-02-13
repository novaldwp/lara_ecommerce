@section('scripts')
<script>
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
        'alwaysShowNavOnTouchDevices': false
    })
</script>
<script>
$(document).ready(function() {
    let textModal = $('input[name="textModal"]');
    let inputOrderId = $('input[name="order_id"]');
    let filter;
    $('.modal-dialog').css({
        'top' : '20%'
    });

    $(document).on('click', 'a.nav-link', function() {
        filter = $(this).data('filter');
        $('.link-item').find('a.nav-link.active').removeClass('active');
        $(this).addClass('active');

        table.ajax.reload();
    })

    var table = $('#data-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax:  {
            url: "{{ route('admin.orders.index') }}",
            type: "GET",
            data: function(d) {
                d.filter = filter;
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: "1%"
            },
            {
                data: 'code',
                name: 'code',
                width: "4%"
            },
            {
                data: 'order_date',
                name: 'order_date',
                width: "9%"
            },
            {
                data: 'total_price',
                name: 'total_price',
                width: "5%"
            },
            {
                data: 'status',
                name: 'status',
                width: "5%"
            },
            {
                data: 'action',
                name: 'action',
                width: "8%",
                align: "center",
                orderable: false,
                searchable: false
            },
        ]
    });

    $(document).on('click', '#receiveButton', function(e) {
        e.preventDefault();

        let order_id = $(this).data('order');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Status order ini akan berubah menjadi diterima",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willReceive) => {
            if (willReceive) {
                $.ajax({
                    url: 'orders/receive/'+order_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res)
                    {
                        if (res.status = "success")
                        {
                            updateSpanText(res.count)
                        }
                        table.ajax.reload();
                        swal(res.message, {
                            icon: res.status,
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '#cancelButton', function(e) {
        e.preventDefault();
        let order_id    = $('input[name="order_id"]').val();
        let cancel_note = $('input[name="textModal"]').val();

        swal({
            title: "Apakah Kamu yakin?",
            text: "Order ini akan ditolak",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willCancel) => {
            if (willCancel) {
                $.ajax({
                    url: 'orders/cancel',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { order_id:order_id, cancel_note:cancel_note },
                    success: function(res)
                    {
                    $('#actionModal').modal('hide');
                        if (res.status == "success")
                        {
                            updateSpanText(res.count);
                        }
                        table.ajax.reload();
                        setSimpleAlert(res);
                    }
                });
            }
        });
    });

    $(document).on('click', '#deliveryButton', function(e) {
        e.preventDefault();
        let order_id    = $('input[name="order_id"]').val();
        let no_resi     = $('input[name="textModal"]').val();

        $.ajax({
            url: 'orders/delivery',
            type: 'POST',
            dataType: 'JSON',
            data: { order_id:order_id, no_resi:no_resi },
            success: function(res)
            {
                $('#actionModal').modal('hide');
                if (res.status == "success")
                {
                    updateSpanText(res.count);
                }
                table.ajax.reload();
                setSimpleAlert(res);
            }
        });
    });

    $(document).on('click', '#deliveryModalButton', function(e) {
        e.preventDefault();
        let order_id = $(this).data('order');

        $('#actionModal').modal('show');
        $('#modalLabel').text('No. Resi Pengiriman');
        textModal.attr('placeholder', 'contoh: CGK201020102201');
        inputOrderId.val(order_id);
        textModal.val('').focus();
        $('button[name="actionModalSubmit"]').prop('id', 'deliveryButton');
    })

    $(document).on('click', '#cancelModalButton', function(e) {
        e.preventDefault();
        let order_id = $(this).data('order');

        $('#actionModal').modal('show');
        $('#modalLabel').text('Alasan Order Ditolak');
        textModal.attr('placeholder', 'contoh: Stok Produk Ternyata Habis');
        inputOrderId.val(order_id);
        textModal.val('').focus();
        $('button[name="actionModalSubmit"]').prop('id', 'cancelButton');
    })

    $(document).on('click', '#actionModalClose', function(e) {
        e.preventDefault();

        $('#actionModal').modal('hide');
    })

    $(document).on('keyup', '#textModal', function(e) {
        let me = $(this).val();

        if (me.length != 0)
        {
            $('button[name="actionModalSubmit"]').prop('disabled', false);
        }
        else
        {
            $('button[name="actionModalSubmit"]').prop('disabled', true);
        }
    });

    $(document).on('click', '#completeButton', function(e) {
        e.preventDefault();
        let order_id = $(this).data('order');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Order ini akan diselesaikan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willReject) => {
            if (willReject) {
                $.ajax({
                    url: 'orders/complete/'+order_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res)
                    {
                        $('#actionModal').modal('hide');
                        if (res.status == "success")
                        {
                            updateSpanText(res.count);
                        }
                        table.ajax.reload();
                        setSimpleAlert(res);
                    }
                });
            }
        });
    });

    // optional uses
    function updateSpanText(array)
    {
        $('span[name="all"]').text(array.all);
        $('span[name="pending"]').text(array.pending);
        $('span[name="received"]').text(array.received);
        $('span[name="delivery"]').text(array.delivered);
        $('span[name="complete"]').text(array.completed);
        $('span[name="canceled"]').text(array.canceled);
    }

    function setSimpleAlert(array)
    {
        return swal(array.message, {
            icon: array.status
        });
    }
});
</script>
@endsection
