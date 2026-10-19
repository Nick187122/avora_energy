<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'honeypot' => ['nullable', 'string'],
        ]);

        if ($request->filled('honeypot')) {
            return redirect()->back()->with('success', 'Subscribed successfully.');
        }

        NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            ['is_active' => true]
        );

        return redirect()->back()->with('success', 'Subscription successful! Welcome to our newsletter.');
    }
}
