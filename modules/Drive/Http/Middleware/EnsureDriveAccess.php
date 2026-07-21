<?php

namespace Modules\Drive\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDriveAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
