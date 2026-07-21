<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(string $locale, Request $request)
    {
        if (in_array($locale, ['en', 'ar'])) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
