<footer class="site-footer">
    <div class="footer-top container">
        <div class="footer-grid">
            <div>
                <h4>Follow Us</h4>
                <p style="font-size:.88rem;color:#c3d4ea;">{{ site_name() }} is now available on all Socials.</p>
                <div class="social-row">
                    @php $socials = ['facebook', 'instagram', 'youtube', 'linkedin', 'tiktok']; @endphp
                    @foreach($socials as $s)
                        @if(setting($s))
                            <a href="{{ setting($s) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($s) }}">
                                {{ ucfirst(substr($s, 0, 2)) }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <h4>Information</h4>
                <ul>
                    <li><a href="{{ route('shop.index') }}">New Arrivals</a></li>
                    <li><a href="{{ route('shop.index') }}?sort=price_desc">Special Products</a></li>
                    <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('page.show', 'delivery-and-return') }}">Delivery &amp; Return</a></li>
                    <li><a href="{{ route('page.show', 'terms-conditions') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('page.show', 'warranty-and-support') }}">Warranty &amp; Support</a></li>
                </ul>
            </div>

            <div>
                <h4>My Account</h4>
                <ul>
                    <li><a href="{{ route('page.show', 'product-support') }}">Product Support</a></li>
                    <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
                    <li><a href="{{ route('cart.index') }}">Shopping Cart</a></li>
                    <li><a href="{{ route('page.show', 'order-tracking') }}">Order Tracking</a></li>
                </ul>
            </div>

            <div>
                <h4>Customer Care</h4>
                <ul>
                    <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                    <li><a href="{{ route('page.show', 'return-policy') }}">Return Policy</a></li>
                    <li><a href="{{ route('page.show', 'terms-conditions') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('page.show', 'faqs') }}">FAQs</a></li>
                </ul>
            </div>

            <div>
                <h4>Newsletter</h4>
                <p style="font-size:.88rem;color:#c3d4ea;">Join 1000+ subscribers and get a new discount coupon on every Saturday.</p>
                <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" novalidate>
                    @csrf
                    <input type="email" name="email" placeholder="Your Email ..." required>
                    <button type="submit">SIGNUP</button>
                    <input type="text" name="honeypot" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
                </form>
                <p class="footer-note">By subscribing you accept our Privacy Policy &amp; Terms and Conditions.</p>
            </div>
        </div>
    </div>

    <div class="footer-help">
        <div class="container">
            <div class="help-grid">
                <div><span class="title">Customer Feedback</span><span class="sub">Your voice helps us serve you better</span></div>
                <div><span class="title">VAT Exemptions</span><span class="sub">Understand your taxes, save more money</span></div>
                <div><span class="title">Avora Energy Express</span><span class="sub">Get your solar order delivered super fast</span></div>
                <div><span class="title">How to Place Order</span><span class="sub">Ordering solar energy has never been easier</span></div>
                <div><span class="title">Warranty &amp; Support</span><span class="sub">We back every product we sell fully</span></div>
                <div><span class="title">Our Wholesale</span><span class="sub">Big orders, better prices for you</span></div>
                <div><span class="title">Payment &amp; Delivery</span><span class="sub">Pay your way, delivered right to you</span></div>
                <div><span class="title">Returns &amp; Refunds</span><span class="sub">Not happy? We make it right fast</span></div>
                <div><span class="title">Avora Energy Corporate</span><span class="sub">Clean energy solutions built for your business</span></div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <span>Copyright &copy; {{ date('Y') }} {{ site_name() }}. All Rights Reserved.</span>
            <span>Powered by <a href="https://www.softwareske.com/">SoftwaresKe Ltd</a></span>
        </div>
    </div>
</footer>