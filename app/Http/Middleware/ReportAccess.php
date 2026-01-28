<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isGeneralManager()) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
