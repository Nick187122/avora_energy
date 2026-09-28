<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::when(! request()->boolean('all'), fn ($q) => $q->where('is_read', false))
            ->latest()
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message): View
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function markUnread(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => false]);

        return back()->with('success', 'Message marked as unread.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }
}