<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function update(Request $request)
    {
        $data = Validator::make($request->all(), [
            'locale' => ['required', 'in:ar,en'],
        ])->validate();

        Session::put('locale', $data['locale']);
        App::setLocale($data['locale']);

        return redirect()->back();
    }
}
