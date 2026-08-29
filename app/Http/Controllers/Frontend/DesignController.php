<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('created_at')
            ->paginate(24);
        return view('designs.index', compact('designs'));
    }
}
