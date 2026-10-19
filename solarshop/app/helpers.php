<?php

use App\Models\Setting;
use App\Services\CartService;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('money')) {
    function money(float $amount): string
    {
        return 'KSh '.number_format($amount, 2);
    }
}

if (! function_exists('site_logo')) {
    function site_logo(): string
    {
        return setting('logo', asset('img/logo.png'));
    }
}

if (! function_exists('site_name')) {
    function site_name(): string
    {
        return setting('site_name', 'Avora Energy Solutions');
    }
}

if (! function_exists('site_email')) {
    function site_email(): string
    {
        return setting('email', 'faithdoyin3@gmail.com');
    }
}

if (! function_exists('site_phone')) {
    function site_phone(): string
    {
        return setting('phone_1', '+254758433424');
    }
}

if (! function_exists('wa_number')) {
    function wa_number(): string
    {
        return setting('whatsapp_number', site_phone());
    }
}

if (! function_exists('wa_link')) {
    function wa_link(string $message = ''): string
    {
        $number = preg_replace('/[^0-9]/', '', wa_number());

        return 'https://wa.me/'.$number.($message !== '' ? '?text='.rawurlencode($message) : '');
    }
}

if (! function_exists('tel_link')) {
    function tel_link(?string $number = null): string
    {
        return 'tel:'.preg_replace('/[^0-9+]/', '', $number ?: site_phone());
    }
}

if (! function_exists('cart_count')) {
    function cart_count(): int
    {
        return app(CartService::class)->count();
    }
}

if (! function_exists('cart_subtotal')) {
    function cart_subtotal(): float
    {
        return app(CartService::class)->subtotal();
    }
}

if (! function_exists('product_image')) {
    function product_image(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        $hash = pathinfo($image, PATHINFO_FILENAME);
        $thumb = 'img/thumbs/'.$hash.'.webp';

        if (is_file(public_path($thumb))) {
            return $thumb;
        }

        $fallback = 'img/thumbs/'.$hash.'.jpg';

        if (is_file(public_path($fallback))) {
            return $fallback;
        }

        return $image;
    }
}

if (! function_exists('clean_html')) {
    function clean_html(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        // Remove the most dangerous elements / attributes to block stored XSS.
        $html = preg_replace('~</?(?:script|style|iframe|object|embed|form|meta|link|base|svg|math|video|audio|source)\b[^>]*>~i', '', $html);
        $html = preg_replace('~on\w+\s*=\s*["\'][^"\']*["\']~i', '', $html);
        $html = preg_replace('~on\w+\s*=\s*\w+~i', '', $html);
        $html = preg_replace('~javascript\s*:\s*~i', '', $html);
        $html = preg_replace('~<img\s[^>]*\bonerror~i', '<img', $html);

        return $html;
    }
}
