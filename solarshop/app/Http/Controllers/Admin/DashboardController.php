<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::count(),
            'active_products' => Product::active()->count(),
            'categories' => Category::count(),
            'brands' => Brand::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'subscribers' => NewsletterSubscriber::count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];

        $recentOrders = Order::with('items')->latest()->take(8)->get();
        $lowStock = Product::active()->where('stock', '<=', 5)->orderBy('stock')->take(8)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStock', 'recentMessages'));
    }
}