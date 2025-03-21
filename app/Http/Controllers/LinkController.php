<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function shorten(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        // Verifica se o link já foi encurtado antes
        $existingLink = Link::where('original_url', $request->url)->first();
        if ($existingLink) {
            return response()->json([
                'short_url' => url($existingLink->short_code)
            ]);
        }

        // Gera um código único
        $shortCode = Str::random(6);
        while (Link::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(6);
        }

        // Salva no banco
        $link = Link::create([
            'original_url' => $request->url,
            'short_code' => $shortCode
        ]);

        return response()->json([
            'short_url' => url($shortCode)
        ]);
    }

    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();
        return redirect()->to($link->original_url);
    }
}
