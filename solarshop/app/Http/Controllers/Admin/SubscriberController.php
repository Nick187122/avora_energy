<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(): View
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->route('admin.subscribers.index')->with('success', 'Subscriber removed.');
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers-' . now()->format('Y-m-d') . '.csv"',
        ];

        return Response::stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Subscribed At']);
            NewsletterSubscriber::orderBy('created_at', 'desc')->each(function ($s) use ($handle) {
                fputcsv($handle, [$s->email, $s->created_at]);
            });
            fclose($handle);
        }, 200, $headers);
    }
}