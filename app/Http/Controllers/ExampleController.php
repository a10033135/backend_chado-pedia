<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Display a simple message.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Hello from Chado-pedia backend!',
            'status' => 'success'
        ]);
    }
}
