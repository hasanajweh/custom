<?php

namespace App\Http\Controllers;

use App\Services\ActiveContext;
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
        Session::save(); // Save session immediately
        App::setLocale($data['locale']);

        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'locale' => $data['locale']]);
        }

        // Preserve tenant context when switching language
        // Get the current URL to preserve tenant slugs
        $backUrl = $request->header('Referer') ?? url()->previous();
        
        // If we have an active school context, ensure the redirect URL uses correct slugs
        $activeSchool = ActiveContext::getSchool();
        
        if ($activeSchool && $activeSchool->network) {
            // Extract the path from the referer
            $parsedUrl = parse_url($backUrl);
            $path = $parsedUrl['path'] ?? '/';
            
            // Check if the path contains tenant slugs
            $pathParts = explode('/', trim($path, '/'));
            
            // If path has network/school structure, preserve it
            if (count($pathParts) >= 2) {
                // Rebuild URL with correct tenant slugs
                $networkSlug = $activeSchool->network->slug;
                $schoolSlug = $activeSchool->slug;
                
                // Replace network and school slugs in path if they exist
                if (isset($pathParts[0]) && isset($pathParts[1])) {
                    $pathParts[0] = $networkSlug;
                    $pathParts[1] = $schoolSlug;
                    $newPath = '/' . implode('/', $pathParts);
                    
                    // Preserve query string if exists
                    $query = $parsedUrl['query'] ?? '';
                    $redirectUrl = $newPath . ($query ? '?' . $query : '');
                    
                    return redirect($redirectUrl);
                }
            }
        }
        
        // Fallback to standard back redirect
        return redirect()->back();
    }
}
