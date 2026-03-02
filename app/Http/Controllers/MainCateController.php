<?php

namespace App\Http\Controllers;

use App\Models\MainCate;
use Illuminate\Http\Request;

class MainCateController extends Controller
{
    /**
     * GET /api/main-cate
     * Public endpoint: Return all enabled MainCate records with their enabled sub-categories.
     */
    public function indexPublic()
    {
        $categories = MainCate::with([
            'subCategories' => function ($query) {
                $query->where('enable', true)->orderBy('sort')->orderBy('id');
            }
        ])
            ->where('enable', true)
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * GET /api/admin/main-cate
     * Admin endpoint: get all records.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => MainCate::with('subCategories')->orderBy('id', 'desc')->get(),
        ]);
    }

    /**
     * POST /api/admin/main-cate
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_image' => 'boolean',
            'enable' => 'boolean',
        ]);

        $validated['create_time'] = now();
        $validated['update_time'] = now();

        $mainCate = MainCate::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Main category created successfully',
            'data' => $mainCate,
        ], 201);
    }

    /**
     * GET /api/admin/main-cate/{id}
     */
    public function show($id)
    {
        $mainCate = MainCate::with('subCategories')->find($id);

        if (!$mainCate) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $mainCate]);
    }

    /**
     * PUT /api/admin/main-cate/{id}
     */
    public function update(Request $request, $id)
    {
        $mainCate = MainCate::find($id);

        if (!$mainCate) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'has_image' => 'boolean',
            'enable' => 'boolean',
        ]);

        $validated['update_time'] = now();

        $mainCate->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Main category updated successfully',
            'data' => $mainCate,
        ]);
    }

    /**
     * DELETE /api/admin/main-cate/{id}
     */
    public function destroy($id)
    {
        $mainCate = MainCate::find($id);

        if (!$mainCate) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $mainCate->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Main category deleted successfully',
        ]);
    }
}
