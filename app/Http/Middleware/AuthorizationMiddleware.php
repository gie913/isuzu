<?php

namespace App\Http\Middleware;

use App\Facades\CMSAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     * Check role-based permission for module and action.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $module = null, string $action = 'can_read'): Response
    {
        if ($module && class_exists(CMSAccess::class)) {
            if (!CMSAccess::can($module, $action)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
                }
                abort(403, 'Unauthorized module access.');
            }
        }

        return $next($request);
    }
}
