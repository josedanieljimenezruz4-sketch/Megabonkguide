<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameInfo;
use App\Models\Faq;

class WikiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $infos = GameInfo::where('title', 'LIKE', "%{$search}%")
                            ->orWhere('content', 'LIKE', "%{$search}%")
                            ->get();

            $faqs = Faq::where('title', 'LIKE', "%{$search}%")
                            ->orWhere('content', 'LIKE', "%{$search}%")
                            ->get();
        } else {
            $infos = GameInfo::all();
            $faqs = Faq::all();
        }

        return view('info_general', compact('infos', 'faqs', 'search'));
    }
}
