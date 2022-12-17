<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ItemCategory;

use DataTables;

class ItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = ItemCategory::query()
            ->select(['item_categories.*']);

            return DataTables::eloquent($query)
            ->addIndexColumn()
            ->toJson();
        }

        return view('item-categories.index');
    }

    public function destroy(Request $request, $id)
    {
        $query = ItemCategory::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();
                $query->delete();
                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Item category deleted in successfully.',
                    'data'      => NULL
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'   => 500,
                    'message'   => $e->getMessage(),
                    'data'      => NULL
                ]);
            }
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => 'Item category not found.',
                'data'      => NULL
            ]);
        }
    }
}