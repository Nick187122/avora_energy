<li class="product-card">
    @php $cardImage = product_image($product->image) ?: 'img/placeholder.png'; @endphp
    <a href="{{ route('product.show', $product->slug) }}" class="thumb" aria-label="{{ $product->name }}">
        <img src="{{ asset($cardImage) }}" alt="{{ $product->name }}" loading="lazy">
        @if($product->has_sale)
            <span class="sale-badge">-{{ $product->discount_percent }}%</span>
        @endif
        <span class="stock-badge {{ $product->is_in_stock ? '' : 'out' }}">{{ $product->is_in_stock ? 'In Stock' : 'Out' }}</span>
    </a>
    <div class="info">
        <div class="info-meta">
            @if($product->category)
                <a href="{{ route('category.show', $product->category->slug) }}" class="cat-chip">{{ $product->category->name }}</a>
            @endif
            @if($product->brand)
                <span class="brand-name">{{ $product->brand->name }}</span>
            @endif
        </div>
        <h3><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
        <div class="price-row">
            @if($product->has_price)
                @if($product->has_sale)
                    <span class="price"><del>{{ money((float) $product->regular_price) }}</del> <ins>{{ money($product->display_price) }}</ins></span>
                @else
                    <span class="price">{{ money($product->display_price) }}</span>
                @endif
            @else
                <span class="price contact-price">{{ $product->price_display_text }}</span>
            @endif
        </div>
        <div class="price-row">
            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-blue-outline btn-sm">View</a>
            @if($product->is_in_stock)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-orange btn-sm">Add to Cart</button>
                </form>
            @endif
        </div>
    </div>
</li>