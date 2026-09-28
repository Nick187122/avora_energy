@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Orders</h2>
            <p>Customer orders from the checkout.</p>
        </div>
    </div>

    <div class="filter-bar">
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:.8rem;align-items:flex-end;width:100%;">
            <div class="form-group" style="flex:2;">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Order no, name, email, phone...">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    @foreach(['pending', 'processing', 'completed', 'cancelled'] as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}" style="font-weight:600;">{{ $order->order_number }}</a></td>
                        <td>
                            {{ $order->customer_name }}
                            <div class="text-muted" style="font-size:.78rem;">{{ $order->phone }}</div>
                        </td>
                        <td><strong>{{ money((float) $order->total) }}</strong></td>
                        <td class="text-muted">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</td>
                        <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline">Details</a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" data-confirm="Delete this order permanently?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--a-muted);">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $orders->links() }}</div>
    </div>
@endsection