@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-ic bg-blue">📦</div>
            <div>
                <div class="stat-num">{{ $stats['products'] }}</div>
                <div class="stat-lbl">Products ({{ $stats['active_products'] }} active)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-orange">🧾</div>
            <div>
                <div class="stat-num">{{ $stats['orders'] }}</div>
                <div class="stat-lbl">Orders ({{ $stats['pending_orders'] }} pending)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-green">KSh</div>
            <div>
                <div class="stat-num">{{ number_format($stats['revenue'], 0) }}</div>
                <div class="stat-lbl">Revenue (excl. cancelled)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-red">✉️</div>
            <div>
                <div class="stat-num">{{ $stats['unread_messages'] }}</div>
                <div class="stat-lbl">Unread messages</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-blue">🗂️</div>
            <div>
                <div class="stat-num">{{ $stats['categories'] }}</div>
                <div class="stat-lbl">Categories</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-orange">🏷️</div>
            <div>
                <div class="stat-num">{{ $stats['brands'] }}</div>
                <div class="stat-lbl">Brands</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-green">✉️</div>
            <div>
                <div class="stat-num">{{ $stats['subscribers'] }}</div>
                <div class="stat-lbl">Newsletter subscribers</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ic bg-red">⚠️</div>
            <div>
                <div class="stat-num">{{ $lowStock->count() }}</div>
                <div class="stat-lbl">Low stock (≤ 5) items</div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div>
            <div class="panel">
                <h3>Recent Orders</h3>
                @if($recentOrders->count())
                    <div class="table-wrap" style="margin-bottom:0;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ money((float) $order->total) }}</td>
                                        <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                                        <td>{{ $order->created_at->format('M d, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No orders yet.</p>
                @endif
            </div>

            <div class="panel">
                <h3>Low Stock Products</h3>
                @if($lowStock->count())
                    <div class="table-wrap" style="margin-bottom:0;">
                        <table class="table">
                            <thead>
                                <tr><th>Product</th><th>Stock</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($lowStock as $product)
                                    <tr>
                                        <td><a href="{{ route('admin.products.edit', $product) }}">{{ \Illuminate\Support\Str::limit($product->name, 46) }}</a></td>
                                        <td class="text-danger"><strong>{{ $product->stock }}</strong></td>
                                        <td>
                                            @if($product->stock === 0)
                                                <span class="badge badge-danger">Out of stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">All products are well stocked. &#10003;</p>
                @endif
            </div>
        </div>

        <div>
            <div class="panel">
                <h3>Quick Actions</h3>
                <div style="display:flex;flex-direction:column;gap:.55rem;">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">＋ Add New Product</a>
                    <a href="{{ route('admin.products.index') }}?status=sale" class="btn btn-outline">View Products on Sale</a>
                    <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-outline">Pending Orders</a>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">Inbox Messages</a>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-outline">Add Category</a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">Site Settings</a>
                </div>
            </div>

            <div class="panel">
                <h3>Recent Messages</h3>
                @if($recentMessages->count())
                    @foreach($recentMessages as $message)
                        <a href="{{ route('admin.messages.show', $message) }}" style="display:block;padding:.55rem 0;border-bottom:1px solid #f1f3f7;font-size:.88rem;">
                            <strong>{{ $message->name }}</strong>
                            @if(! $message->is_read) <span class="badge badge-orange">new</span> @endif
                            <div class="text-muted" style="font-size:.8rem;">{{ \Illuminate\Support\Str::limit($message->message, 60) }}</div>
                        </a>
                    @endforeach
                @else
                    <p class="text-muted">No messages yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection