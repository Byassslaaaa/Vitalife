<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $lang = $request->input('lang');
        $availableLocales = config('app.available_locales');
        if (in_array($lang, $availableLocales)) {
            Session::put('locale', $lang);
            App::setLocale($lang);
            return response()->json(['message' => 'Language changed successfully']);
        }
        return response()->json(['message' => 'Invalid language'], 400);
    }
}
