@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="success-box">
            <div class="success-icon">&#10003;</div>
            <h2 style="color:var(--blue);">Thank you for your order!</h2>
            <p>Your order has been received and is now being processed.</p>
            <p style="margin-top:1rem;">Order Number: <span class="order-number">{{ $order->order_number }}</span></p>
            <p style="font-size:.88rem;color:var(--muted);margin-top:.5rem;">
                A confirmation with the summary below has been noted. We'll contact you on <strong>{{ $order->phone }}</strong> to arrange payment &amp; delivery.
            </p>

            <div class="order-summary">
                @foreach($order->items as $item)
                    <div class="summary-row">
                        <span style="font-size:.9rem;">{{ $item->product_name }} × {{ $item->quantity }}</span>
                        <span>{{ money((float) $item->price) }}</span>
                    </div>
                @endforeach
                <div class="summary-row total">
                    <span>Total</span>
                    <span>{{ money((float) $order->total) }}</span>
                </div>
                <div class="summary-row">
                    <span>Payment Method</span>
                    <span>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                </div>
            </div>

            <a href="{{ route('shop.index') }}" class="btn btn-blue-outline" style="margin-top:1.5rem;">Continue Shopping</a>
        </div>
    </div>
@endsection