<?php

namespace App\Http\Controllers;

use App\Models\SubCate;
use Illuminate\Http\Request;

class SubCateController extends Controller
{
    /**
     * GET /api/sub-cate
     * Public endpoint: Return all enabled SubCate records.
     */
    public function indexPublic(Request $request)
    {
        $query = SubCate::where('enable', true)->orderBy('sort')->orderBy('id');

        if ($request->has('main_cate_id')) {
            $query->where('main_cate_id', $request->input('main_cate_id'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * GET /api/admin/sub-cate
     * Admin endpoint: get all records.
     */
    public function index(Request $request)
    {
        $query = SubCate::with('mainCategory')->orderBy('id', 'desc');

        if ($request->has('main_cate_id')) {
            $query->where('main_cate_id', $request->input('main_cate_id'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * POST /api/admin/sub-cate
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'main_cate_id' => 'nullable|exists:MainCate,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort' => 'integer',
            'has_image' => 'boolean',
            'enable' => 'boolean',
        ]);

        $validated['create_time'] = now();
        $validated['update_time'] = now();
        $validated['sort'] = $validated['sort'] ?? 0;

        $subCate = SubCate::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category created successfully',
            'data' => $subCate,
        ], 201);
    }

    /**
     * GET /api/admin/sub-cate/{id}
     */
    public function show($id)
    {
        $subCate = SubCate::with('mainCategory')->find($id);

        if (!$subCate) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $subCate]);
    }

    /**
     * PUT /api/admin/sub-cate/{id}
     */
    public function update(Request $request, $id)
    {
        $subCate = SubCate::find($id);

        if (!$subCate) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'main_cate_id' => 'nullable|exists:MainCate,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'sort' => 'integer',
            'has_image' => 'boolean',
            'enable' => 'boolean',
        ]);

        $validated['update_time'] = now();

        $subCate->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category updated successfully',
            'data' => $subCate,
        ]);
    }

    /**
     * DELETE /api/admin/sub-cate/{id}
     */
    public function destroy($id)
    {
        $subCate = SubCate::find($id);

        if (!$subCate) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $subCate->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category deleted successfully',
        ]);
    }
}
