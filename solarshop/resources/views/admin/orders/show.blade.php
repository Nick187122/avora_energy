@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
    <div class="admin-head">
        <div>
            <h2>Order {{ $order->order_number }}</h2>
            <p>Placed {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">← All Orders</a>
    </div>

    <div class="grid-2">
        <div>
            <div class="panel">
                <h3>Order Items</h3>
                <div class="table-wrap" style="margin-bottom:0;">
                    <table class="table">
                        <thead>
                            <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:.6rem;">
                                            @if($item->image)
                                                <img src="{{ asset($item->image) }}" class="thumb" alt="">
                                            @endif
                                            <div>
                                                {{ $item->product_name }}
                                                @if($item->product)
                                                    <div><a href="{{ route('admin.products.edit', $item->product_id) }}" class="text-muted" style="font-size:.75rem;">edit product</a></div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ money((float) $item->price) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>{{ money((float) $item->subtotal) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:.9rem 1rem 0;display:flex;justify-content:flex-end;gap:2rem;font-weight:700;color:var(--a-heading);">
                    <span>Total: {{ money((float) $order->total) }}</span>
                </div>
            </div>
        </div>

        <div>
            <div class="panel">
                <h3>Update Status</h3>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            @foreach(\App\Models\Order::STATUSES as $st)
                                <option value="{{ $st }}" @selected($order->status === $st)>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                </form>
            </div>

            <div class="panel">
                <h3>Customer Details</h3>
                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $order->email }}">{{ $order->email }}</a></p>
                <p><strong>Phone:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
                <p><strong>Address:</strong> {{ $order->address }}</p>
                @if($order->city)
                    <p><strong>City:</strong> {{ $order->city }}</p>
                @endif
                <p><strong>Payment:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
            </div>

            @if($order->notes)
                <div class="panel">
                    <h3>Order Notes</h3>
                    <p>{{ $order->notes }}</p>
                </div>
            @endif

            <div class="panel">
                <h3>Actions</h3>
                <div style="display:flex;flex-direction:column;gap:.55rem;">
                    <a href="mailto:{{ $order->email }}" class="btn btn-outline">Email Customer</a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}" target="_blank" rel="noopener" class="btn btn-outline">WhatsApp Customer</a>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" data-confirm="Delete this order? This cannot be undone.">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection