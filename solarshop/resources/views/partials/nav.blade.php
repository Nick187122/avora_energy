@php
    $navOrder = ['solar-panels', 'solar-inverters', 'solar-batteries', 'solar-water-heaters', 'solar-outdoor-lights', 'solar-water-pumps'];

    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->where('is_active', true)
        ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('name')->take(12)])
        ->orderBy('sort_order')->orderBy('name')
        ->get()
        ->sortBy(fn ($c) => array_search($c->slug, $navOrder, true) !== false ? array_search($c->slug, $navOrder, true) : 99);
@endphp

<div class="main-nav">
    <div class="container">
        <ul>
            <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}">Home</a>
            </li>

            <li class="{{ request()->routeIs('shop*') || request()->routeIs('category*') || request()->routeIs('product*') || request()->routeIs('brand*') ? 'active' : '' }}">
                <a href="{{ route('shop.index') }}">Shop All</a>
            </li>

            @foreach($navCategories->take(6) as $category)
                <li class="has-sub {{ URL::current() === route('category.show', $category->slug) ? 'active-cat' : '' }}">
                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }} <span class="caret">▾</span></a>
                    @if($category->children->count())
                        <ul class="submenu {{ $category->children->count() > 6 ? 'submenu-grid' : '' }}">
                            @foreach($category->children as $child)
                                <li>
                                    <a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('category.show', $category->slug) }}" style="color:var(--btn-orange);font-weight:700;">View all {{ $category->name }}</a></li>
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="strap-line">
    Buy at {{ site_name() }} at the Best Price, support &amp; Genuine warranty. Clean Energy is Now.
</div>