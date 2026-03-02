<?php

namespace App\Http\Controllers;

use App\Models\ChadoContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChadoContentController extends Controller
{
    /**
     * GET /api/chado-content
     * Public endpoint: Return all enabled ChadoContent.
     */
    public function indexPublic(Request $request)
    {
        $query = ChadoContent::with(['mainCategories', 'subCategories'])
            ->where('enable', true)
            ->orderBy('id');

        if ($request->has('main_cate_id')) {
            $query->whereHas('mainCategories', function ($q) use ($request) {
                $q->where('ChadoContent_MainCate.main_cate_id', $request->input('main_cate_id'));
            });
        }

        if ($request->has('sub_cate_id')) {
            $query->whereHas('subCategories', function ($q) use ($request) {
                $q->where('ChadoContent_SubCate.sub_cate_id', $request->input('sub_cate_id'));
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * GET /api/admin/chado-content
     * Admin endpoint: get all records.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => ChadoContent::with(['mainCategories', 'subCategories'])->orderBy('id', 'desc')->get(),
        ]);
    }

    /**
     * POST /api/admin/chado-content
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_image' => 'boolean',
            'enable' => 'boolean',
            'main_cate_ids' => 'nullable|array',
            'main_cate_ids.*' => 'exists:MainCate,id',
            'sub_cate_ids' => 'nullable|array',
            'sub_cate_ids.*' => 'exists:SubCate,id',
        ]);

        $contentData = collect($validated)->except(['main_cate_ids', 'sub_cate_ids'])->toArray();
        $contentData['create_time'] = now();
        $contentData['update_time'] = now();

        DB::beginTransaction();
        try {
            $chadoContent = ChadoContent::create($contentData);

            if ($request->has('main_cate_ids')) {
                $chadoContent->mainCategories()->sync($request->main_cate_ids);
            }
            if ($request->has('sub_cate_ids')) {
                $chadoContent->subCategories()->sync($request->sub_cate_ids);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Chado content created successfully',
                'data' => $chadoContent->load(['mainCategories', 'subCategories']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to create content', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/admin/chado-content/{id}
     */
    public function show($id)
    {
        $chadoContent = ChadoContent::with(['mainCategories', 'subCategories'])->find($id);

        if (!$chadoContent) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $chadoContent]);
    }

    /**
     * PUT /api/admin/chado-content/{id}
     */
    public function update(Request $request, $id)
    {
        $chadoContent = ChadoContent::find($id);

        if (!$chadoContent) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'has_image' => 'boolean',
            'enable' => 'boolean',
            'main_cate_ids' => 'nullable|array',
            'main_cate_ids.*' => 'exists:MainCate,id',
            'sub_cate_ids' => 'nullable|array',
            'sub_cate_ids.*' => 'exists:SubCate,id',
        ]);

        $contentData = collect($validated)->except(['main_cate_ids', 'sub_cate_ids'])->toArray();
        $contentData['update_time'] = now();

        DB::beginTransaction();
        try {
            $chadoContent->update($contentData);

            if ($request->has('main_cate_ids')) {
                $chadoContent->mainCategories()->sync($request->main_cate_ids);
            }
            if ($request->has('sub_cate_ids')) {
                $chadoContent->subCategories()->sync($request->sub_cate_ids);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Chado content updated successfully',
                'data' => $chadoContent->load(['mainCategories', 'subCategories']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to update content', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/admin/chado-content/{id}
     */
    public function destroy($id)
    {
        $chadoContent = ChadoContent::find($id);

        if (!$chadoContent) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $chadoContent->delete(); // Cascades on the DB side, but good practice to sync empty if relying on Laravel level

        return response()->json([
            'status' => 'success',
            'message' => 'Chado content deleted successfully',
        ]);
    }
}
