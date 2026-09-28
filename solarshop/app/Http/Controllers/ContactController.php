<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'honeypot' => ['nullable', 'string'],
        ]);

        if ($request->filled('honeypot')) {
            return redirect()->route('contact.index')->with('error', 'Your message could not be sent.');
        }

        unset($data['honeypot']);

        ContactMessage::create($data);

        return redirect()->route('contact.index')->with('success', 'Thank you! Your message has been sent. We will get back to you shortly.');
    }
}