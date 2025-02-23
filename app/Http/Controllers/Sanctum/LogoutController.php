<?php

namespace App\Http\Controllers\Sanctum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    
    public function __invoke() {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout: Success!',
        ], 200);
    }

}
