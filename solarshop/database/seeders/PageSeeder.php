<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>Who We Are</h2><p>We are Avora Energy Solutions, a Nairobi-based solar energy company with over ten years of hands-on experience delivering residential and commercial solar projects across Kenya and East Africa. We are not just an online shop. We have a physical presence across the Nairobi Metropolitan region, a team of certified solar engineers, and the technical capability to take your project from first enquiry through financial analysis, procurement, professional installation, and lifetime operations and maintenance.</p><p>Whether you are a homeowner, a business, a developer, or an institution, Avora Energy Solutions is your trusted solar partner.</p><h2>What We Stock</h2><p>Our product range covers everything you need for a solar-powered property: Solar panels from Tier-1 global brands including Jinko, JA Solar, Trina, and Longi; hybrid and grid-tied inverters from Growatt, Deye, Victron Energy, and Huawei; lithium and lead-acid battery storage; solar water heaters; solar outdoor and street lighting; solar water pumping for boreholes and irrigation; portable power stations; and all balance-of-system accessories. We stock over 1,500 products from more than 40 brands, the widest choice in Kenya with genuine warranties and local after-sales support.</p><h2>Why Shop With Us</h2><ul><li><strong>Online + Physical:</strong> Nairobi showroom with project teams across the Nairobi Metropolitan region.</li><li><strong>10+ Years Experience:</strong> Thousands of successful residential and commercial solar projects delivered across Kenya.</li><li><strong>Genuine Warranty:</strong> Original products from authorized sources with manufacturer warranty honoured in Kenya and local spare parts.</li><li><strong>Full Project Service:</strong> Design, supply, install, commission, and O&amp;M from first call to 25-year system life.</li></ul>',
                'meta_title' => 'About Avora Energy Solutions | Trusted Solar Company in Kenya',
                'meta_description' => 'Avora Energy Solutions is Kenya\'s largest solar energy marketplace with 10+ years of experience, Tier-1 brands, genuine warranties and full project service.',
                'is_active' => true,
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'content' => '<h2>Welcome to Avora Energy Solutions</h2><p>Established in 2010, with over 10,000 installations, partnerships with top brands and award-winning service, we provide reliable and affordable solar energy solutions for all.</p><h2>Our Products &amp; Services</h2><ul><li><strong>High-Efficiency Solar Panels:</strong> Durable and reliable solar panels.</li><li><strong>Advanced Energy Storage Solutions:</strong> Long-lasting solar battery options.</li><li><strong>Powerful Solar Inverters:</strong> Convert solar energy effectively.</li><li><strong>Complete Solar Energy Kits:</strong> All-in-one solar energy solutions.</li><li><strong>Solar Water Heating &amp; Pumping:</strong> Systems for hotels, schools, farms and homes.</li><li><strong>Design, Installation &amp; O&amp;M:</strong> From project scoping and ROI analysis to professional installation and long-term operations &amp; maintenance.</li></ul><h2>How to Acquire Our Solar Products</h2><ol><li>Contact us to discuss your solar energy needs and preferences.</li><li>Navigate to our website and browse our wide range of solar products.</li><li>Browse through categories like solar panels, batteries, inverters, and kits.</li><li>Choose the items you wish to purchase and add them to your shopping cart.</li><li>Proceed to checkout, providing the necessary details for shipping and payment options.</li></ol>',
                'meta_title' => 'Solar Services in Kenya | Avora Energy Solutions',
                'meta_description' => 'Avora Energy Solutions provides solar panels, batteries, inverters, water heating, pumping and complete installation services across Kenya.',
                'is_active' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h2>Get in Touch with Avora Energy Solutions</h2><p>Visit our Nairobi showroom along Northern Bypass, or reach us through the channels below. Our team is available Monday to Saturday.</p><ul><li><strong>Phone:</strong> +254758433424</li><li><strong>Email:</strong> faithdoyin3@gmail.com</li><li><strong>Address:</strong> Northern Bypass, Nairobi, Kenya</li><li><strong>Hours:</strong> Mon&ndash;Sat, 8:00 AM &ndash; 6:00 PM</li></ul><p>You can also use the contact form to send us a message &mdash; we respond within one business day.</p>',
                'meta_title' => 'Contact Avora Energy Solutions | Nairobi Solar Showroom',
                'meta_description' => 'Contact Avora Energy Solutions for solar products, pricing, installation and support. Nairobi showroom along Northern Bypass.',
                'is_active' => true,
            ],
            [
                'title' => 'Delivery & Return',
                'slug' => 'delivery-and-return',
                'content' => '<h2>Delivery</h2><p>We deliver nationwide across Kenya. Orders within Nairobi and the metropolitan area are usually delivered within 1&ndash;2 working days. Upcountry and East African deliveries are dispatched via trusted couriers and confirmed by our team upon checkout.</p><h2>Returns &amp; Refunds</h2><p>We back every product we sell fully. If you receive an incorrect or damaged item, contact us within 7 days of delivery and we will make it right. Genuine manufacturer warranties apply to all brands we stock and are honoured locally in Kenya with spare parts available.</p>',
                'meta_title' => 'Delivery & Return Policy | Avora Energy Solutions',
                'meta_description' => 'Nationwide solar delivery from Avora Energy Solutions and a simple returns policy. We back every product we sell fully.',
                'is_active' => true,
            ],
            [
                'title' => 'Warranty & Support',
                'slug' => 'warranty-and-support',
                'content' => '<h2>Warranty</h2><p>Every product we stock comes from authorized distributors or manufacturers with genuine warranty documentation and traceable serial numbers. Manufacturer warranties are honoured in Kenya with local spare parts.</p><h2>Technical Support</h2><p>Our team of certified solar engineers provides product support, installation guidance and after-sales service. For any product-related issue, reach out via phone, WhatsApp or the contact form.</p>',
                'meta_title' => 'Warranty & Support | Avora Energy Solutions',
                'meta_description' => 'Genuine warranties on all solar products from Avora Energy Solutions, with local after-sales support and spare parts in Kenya.',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
