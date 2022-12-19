<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = Category::query()
            ->select(['categories.*'])
            ->whereIn('categories.owner_id', [
                $owner->id,
                $owner->parentCompany->id
            ]);

            return DataTables::eloquent($query)
            ->editColumn('is_enable', function ($query) {
                return $query->is_enable ? '<span class="badge bg-soft-success text-success">Enable</span>' : '<span class="badge bg-soft-danger text-danger">Disable</span>';
            })
            ->addColumn('actions', function ($query) {
                return '
                    <div class="btn-group" role="group">
                        <span class="btn btn-white btn-sm">
                            More
                        </span>

                        <div class="btn-group">
                            <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="datatableMore-' . $query->id . '" data-bs-toggle="dropdown" aria-expanded="false"></button>

                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-' . $query->id . '" >
                                <span class="dropdown-header">Options</span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('items.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
                                </a>
                                <a class="dropdown-item datatable-btn-destroy" href="javascript:;" data-url="' . route('items.show', $query->id) . '">
                                    <i class="bi-trash dropdown-item-icon"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['is_enable', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('categories.index');
    }

    public function create()
    {
        return view('categories.create');
    }

    public function destroy(Request $request, $id)
    {
        $query = Category::query()->find($id);
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