<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckParameter
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');

        if (!$id) {
            return response()->json(['error' => 'Eksik ID parametresi.'], 400);
        }

        if (!is_numeric($id)) {
            return response()->json(['error' => 'Geçersiz ID parametresi.'], 400);
        }

        return $next($request);
    }
}
