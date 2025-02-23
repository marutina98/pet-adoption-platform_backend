<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePetAgencyOrPetAdopter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $isPetAgency = auth()->user()->is_pet_agency;
        $isPetAdopter = auth()->user()->is_pet_adopter;

        if (auth()->user() && ($isPetAgency || $isPetAdopter)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Forbidden'
        ], 403);

    }
}
