<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');
        
        // Verifica se o idioma é suportado
        if (in_array($locale, ['pt_BR', 'en', 'it'])) {
            Session::put('locale', $locale);
        }
        
        // Redireciona de volta para a página anterior
        return redirect()->back();
    }
}
