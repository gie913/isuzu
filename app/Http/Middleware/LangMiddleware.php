<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     * Checks query string ?lang=, falls back to session or config locale.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'id'];

        if ($request->has('lang')) {
            $lang = (string) $request->query('lang');
            if (in_array($lang, $supportedLocales, true)) {
                if ($request->hasSession()) {
                    $request->session()->put('locale', $lang);
                }
                App::setLocale($lang);
            }
        } elseif ($request->hasSession() && $request->session()->has('locale')) {
            $lang = (string) $request->session()->get('locale');
            if (in_array($lang, $supportedLocales, true)) {
                App::setLocale($lang);
            }
        }

        return $next($request);
    }
}
