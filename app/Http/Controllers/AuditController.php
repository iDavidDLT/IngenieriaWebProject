<?php

namespace App\Http\Controllers;

use App\Models\ProductAudit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        return view('products.activity', [
            'activities' => ProductAudit::with('user')
                ->where('user_id', $request->user()->id)
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }
}
