@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a> &raquo; <span>Checkout</span></nav>
            <h1>Checkout</h1>
        </div>
    </div>

    <div class="container checkout-layout">
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <h2 style="color:var(--blue);font-size:1.2rem;margin-bottom:1rem;">Billing Details</h2>
            <div class="form-grid">
                <div class="form-group {{ $errors->has('customer_name') ? 'field-error' : '' }} full">
                    <label for="customer_name">Full Name <span class="req">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                    @error('customer_name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group {{ $errors->has('email') ? 'field-error' : '' }}">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group {{ $errors->has('phone') ? 'field-error' : '' }}">
                    <label for="phone">Phone Number <span class="req">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required placeholder="07XX XXX XXX">
                    @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group {{ $errors->has('address') ? 'field-error' : '' }} full">
                    <label for="address">Delivery Address <span class="req">*</span></label>
                    <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
                    @error('address') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label for="city">Town / City</label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
                </div>

                <div class="form-group full">
                    <label>Payment Method</label>
                    <div class="pay-options">
                        <label class="pay-option {{ old('payment_method', 'mpesa') === 'mpesa' ? 'selected' : '' }}">
                            <input type="radio" name="payment_method" value="mpesa" {{ old('payment_method', 'mpesa') === 'mpesa' ? 'checked' : '' }}>
                            <div>
                                <h5>M-Pesa</h5>
                                <p>Pay with M-Pesa on delivery or via STK push (Kenya).</p>
                            </div>
                        </label>
                        <label class="pay-option {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}">
                            <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                            <div>
                                <h5>Bank Transfer</h5>
                                <p>Direct deposit to our company bank account.</p>
                            </div>
                        </label>
                        <label class="pay-option {{ old('payment_method') === 'card' ? 'selected' : '' }}">
                            <input type="radio" name="payment_method" value="card" {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                            <div>
                                <h5>Card Payment</h5>
                                <p>Visa / Mastercard payments processed securely.</p>
                            </div>
                        </label>
                        <label class="pay-option {{ old('payment_method') === 'cod' ? 'selected' : '' }}">
                            <input type="radio" name="payment_method" value="cod" {{ old('payment_method') === 'cod' ? 'checked' : '' }}>
                            <div>
                                <h5>Cash on Delivery</h5>
                                <p>Pay in cash when your order is delivered.</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full">
                    <label for="notes">Order Notes (optional)</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top:.6rem;">Place Order</button>
        </form>

        <aside class="cart-summary">
            <h3>Your Order</h3>
            @foreach($items as $item)
                <div class="summary-row">
                    <span style="font-size:.85rem;">{{ $item['product']->name }} <span style="color:var(--meta);">× {{ $item['quantity'] }}</span></span>
                    <span>{{ money($item['line_total']) }}</span>
                </div>
            @endforeach
            <div class="summary-row total">
                <span>Order Total</span>
                <span>{{ money($subtotal) }}</span>
            </div>
            <div class="delivery-note">
                @if(setting('delivery_note'))
                    {{ setting('delivery_note') }}
                @else
                    Delivery calculated after order confirmation; free within Nairobi.
                @endif
            </div>
            <p style="font-size:.78rem;color:var(--meta);margin-top:.5rem;">Your information is kept private and secure. We only use it to process your order.</p>
        </aside>
    </div>
@endsection