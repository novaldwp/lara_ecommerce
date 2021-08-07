
                    <div class="text-center">
                        <p class="header-completed"><strong>Selesaikan Pembayaran Dalam</strong></p>
                        <strong><p class="header-completed timer"></p></strong>
                        <p class="header-completed">Batas Akhir Pembayaran</p>
                        <p class="header-completed"><strong>{{ getDateTimeIndo($order->payment_due) }}</strong></p>
                        <input type="hidden" name="payment_due" id="payment_due" value="{{ date('M d, Y H:i:s', strtotime($order->payment_due)) }}">
                    </div>
                    <div class="payment-information">
                        <div class="card">
                            <div class="card-header information-header" id="headingOne">
                                <h5 class="mb-0">
                                <strong>{{ $header }}</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="payment-code">
                                    @if ($order->payments->payment_type != "echannel")
                                        <p class="header">Nomor Pembayaran</p>
                                        <p><strong>{{ $order->payments->va_number ?? "Error Payment Number" }}</strong></p>
                                    @else
                                        <p class="header">Biller Code</p>
                                        <p><strong>{{ $order->payments->biller_code ?? "Error Payment Number" }}</strong></p>
                                        <p class="header">Bill Key</p>
                                        <p><strong>{{ $order->payments->bill_key ?? "Error Payment Number" }}</strong></p>
                                    @endif
                                </div>
                                <div class="payment-amount">
                                    <p class="header">Total Pembayaran</p>
                                    <p><strong>Rp. {{ number_format($order->payments->amount, 0) }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment-button">
                        <a href="{{ route('ecommerce.product.index') }}" class="btn left">Shopping Again</a>
                        <a href="{{ route('ecommerce.profile.orders') }}" class="btn right">Check Payment Status</a>
                    </div>
