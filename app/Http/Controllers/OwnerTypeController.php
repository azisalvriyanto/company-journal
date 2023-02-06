<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\OwnerTypes;
use App\Models\OwnerType;

use DataTables;

class OwnerTypeController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = OwnerType::query()
            ->select(['owner_types.*']);

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

                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-' . $query->id . '">
                                <span class="dropdown-header">Options</span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('owner-types.edit', $query->id) . '">
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
                    return route('owner-types.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
            ])
            ->rawColumns(['name', 'is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('owner-types.index');
    }

    public function create()
    {
        return view('owner-types.create');
    }

    public function store(Request $request)
    {
        $query = new OwnerTypes;
        return response()->json($query->store($request));
    }

    public function edit($id)
    {
        $data['query'] = OwnerType::query()->findOrFail($id);

        return view('owner-types.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new OwnerTypes;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new OwnerTypes;
        return response()->json($query->destroy($request, $id));
    }
}