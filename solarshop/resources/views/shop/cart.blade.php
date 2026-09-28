@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a> &raquo; <span>Cart</span></nav>
            <h1>Your Shopping Cart</h1>
        </div>
    </div>

    <div class="container">
        @if($items)
            <div class="cart-layout">
                <div>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="cart-item">
                                    <td>
                                        <div class="product-cell">
                                            @php $cartImage = product_image($item['product']->image) ?: 'img/placeholder.png'; @endphp
                                            <img src="{{ asset($cartImage) }}" alt="{{ $item['product']->name }}">
                                            <div>
                                                <h4><a href="{{ route('product.show', $item['product']->slug) }}">{{ $item['product']->name }}</a></h4>
                                                @if($item['product']->brand)
                                                    <span style="font-size:.78rem;color:var(--meta);">{{ $item['product']->brand->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item['product']->has_sale)
                                            <del style="color:var(--meta);font-size:.8rem;">{{ money((float) $item['product']->regular_price) }}</del>
                                            <br><span style="color:var(--btn-orange);font-weight:700;">{{ money($item['product']->display_price) }}</span>
                                        @else
                                            <span style="font-weight:600;">{{ money($item['product']->display_price) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update') }}" method="POST" class="qty-form" data-auto="1">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <div class="cart-qty">
                                                <button type="button" data-dir="-">&minus;</button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1">
                                                <button type="button" data-dir="+">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td><strong>{{ money($item['line_total']) }}</strong></td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <button type="submit" class="remove-link" style="background:none;border:none;cursor:pointer;">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('shop.index') }}" class="btn btn-blue-outline" style="margin-top:1.2rem;">&larr; Continue Shopping</a>
                </div>

                <div class="cart-summary">
                    <h3>Cart Totals</h3>
                    @if(setting('delivery_note'))
                        <div class="delivery-note">{{ setting('delivery_note') }}</div>
                    @endif
                    <div class="summary-row"><span>Subtotal</span><span>{{ money($subtotal) }}</span></div>
                    <div class="summary-row"><span>Delivery</span><span>Calculated at checkout</span></div>
                    <div class="summary-row total"><span>Total</span><span>{{ money($subtotal) }}</span></div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block btn-lg" style="margin-top:1rem;">Proceed to Checkout</a>
                    <form action="{{ route('cart.clear') }}" method="POST" style="margin-top:.7rem;">
                        @csrf
                        <button type="submit" class="btn btn-blue-outline btn-block btn-sm">Clear Cart</button>
                    </form>
                </div>
            </div>
        @else
            <div class="search-empty">
                <h2>Your cart is currently empty.</h2>
                <p>Explore our range of solar panels, batteries, and inverters.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg" style="margin-top:1rem;">Return to shop</a>
            </div>
        @endif
    </div>
@endsection