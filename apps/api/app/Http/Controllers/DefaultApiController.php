<?php

namespace App\Http\Controllers;

class DefaultApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => config('app.name').' API',
            'version' => config('app.version').'+'.config('app.build'),
        ]);
    }
}
