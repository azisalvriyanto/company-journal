<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;

use DataTables;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = Item::query()
            ->select(['items.*']);

            return DataTables::eloquent($query)
            ->addIndexColumn()
            ->toJson();
        }

        return view('items.index');
    }

    public function destroy(Request $request, $id)
    {
        $query = Item::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();
                $query->delete();
                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Item deleted in successfully.',
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
                'message'   => 'Item not found.',
                'data'      => NULL
            ]);
        }
    }
}