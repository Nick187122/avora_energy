<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}