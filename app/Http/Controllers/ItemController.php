<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\Items;

use App\Models\Category;
use App\Models\Item;
use App\Models\UnitOfMeasurement;

use DataTables;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = Item::query()
            ->with([
                'category',
                'unitOfMeasurement',
            ])
            ->select(['items.*'])
            ->whereIn('items.owner_id', [
                $owner->id,
                $owner->parent_company_id
            ]);

            return DataTables::eloquent($query)
            ->editColumn('is_enable', function ($query) {
                return $query->is_enable ? '<span class="badge bg-soft-success text-success">Enable</span>' : '<span class="badge bg-soft-danger text-danger">Disable</span>';
            })
            ->editColumn('detail_group', function ($query) {
                return $query->detail_group ?? '<i class="text-muted">NULL</i>';
            })
            ->addColumn('actions', function ($query) {
                return '
                    <div class="btn-group" role="group">
                        <span class="btn btn-white btn-sm">
                            More
                        </span>

                        <div class="btn-group">
                            <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="datatableMore-' . $query->id . '" data-bs-toggle="dropdown" aria-expanded="false"></button>

                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-' . $query->id . '">
                                <span class="dropdown-header">Options</span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('items.items.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
                                </a>
                                <a class="dropdown-item datatable-btn-destroy" href="javascript:;">
                                    <i class="bi-trash dropdown-item-icon"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->setRowAttr([
                'data-id' => function($query) {
                    return $query->id;
                },
                'data-url' => function($query) {
                    return route('items.items.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
                'data-category' => function($query) {
                    return $query->category->name;
                },
                'data-unit-of-measurement' => function($query) {
                    return $query->unitOfMeasurement->name;
                },
            ])
            ->rawColumns(['is_enable', 'detail_group','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('items.index');
    }

    public function create()
    {
        $data['detailGroups'] = collect(Item::DETAIL_GROUPS)->sortBy('name');
        $data['categories'] = Category::query()->orderBy('name')->get()->all();
        $data['unitOfMeasurements'] = UnitOfMeasurement::query()->orderBy('name')->get()->all();

        return view('items.create', $data);
    }

    public function store(Request $request)
    {
        $query = new Items;
        return response()->json($query->store($request));
    }

    public function edit($id)
    {
        $data['query']              = Item::query()->findOrFail($id);
        $data['detailGroups']       = collect(Item::DETAIL_GROUPS)->sortBy('name');
        $data['categories']         = Category::query()->orderBy('name')->get()->all();
        $data['unitOfMeasurements'] = UnitOfMeasurement::query()->orderBy('name')->get()->all();

        return view('items.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new Items;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new Items;
        return response()->json($query->destroy($request, $id));
    }
}