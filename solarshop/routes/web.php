<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\ValuePropositionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/category/{category}', [ShopController::class, 'index'])->name('category.show');
Route::get('/brand/{brand}', [ShopController::class, 'index'])->name('brand.show');
Route::get('/product/{slug}', [PublicProductController::class, 'show'])->name('product.show');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::redirect('/about-us', '/page/about-us')->name('about');
Route::redirect('/services', '/page/services')->name('services');

Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('brands', BrandController::class)->except(['show']);
    Route::resource('hero-slides', HeroSlideController::class)->except(['show']);
    Route::post('hero-slides/{heroSlide}/toggle', [HeroSlideController::class, 'toggle'])->name('hero-slides.toggle');
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::resource('partners', PartnerController::class)->except(['show']);
    Route::resource('value-propositions', ValuePropositionController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('messages/{message}/unread', [MessageController::class, 'markUnread'])->name('messages.unread');
    Route::resource('subscribers', SubscriberController::class)->only(['index', 'destroy']);
    Route::get('subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::resource('admins', AdminUserController::class)->except(['show']);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});