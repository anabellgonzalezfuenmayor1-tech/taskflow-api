<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function health(){
        return response()->json([
            "status" => "ok",
            "app" => config('app.name'),
            "version" => "0.1.0",
        ]);
    }
}
