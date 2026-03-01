<?php

namespace App\Http\Controllers;

use App\Models\MainCate;
use Illuminate\Http\Request;

class MainCateController extends Controller
{
    /**
     * GET /api/main-cate
     * Return all enabled MainCate records.
     */
    public function index()
    {
        $categories = MainCate::where('enable', true)
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }
}
