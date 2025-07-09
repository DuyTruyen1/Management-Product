<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
  public function handle(Request $request, Closure $next, ...$roles)
  {
    if (!$request->user() || !$request->user()->hasAnyRole($roles)) {
      return response()->json([
        'success' => false,
        'message' => 'Unauthorized. Insufficient permissions.'
      ], 403);
    }

    return $next($request);
  }
}
