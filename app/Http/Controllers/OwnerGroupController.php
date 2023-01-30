<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\OwnerGroups;
use App\Models\OwnerGroup;

use DataTables;

class OwnerGroupController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = OwnerGroup::query()
            ->select(['owner_groups.*']);

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
                                <a class="dropdown-item" href="' . route('owner-groups.edit', $query->id) . '">
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
                    return route('owner-groups.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
            ])
            ->rawColumns(['name', 'is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('owner-groups.index');
    }

    public function create()
    {
        return view('owner-groups.create');
    }

    public function store(Request $request)
    {
        $query = new OwnerGroups;
        return response()->json($query->store($request));
    }

    public function edit($id)
    {
        $data['query'] = OwnerGroup::query()->findOrFail($id);

        return view('owner-groups.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new OwnerGroups;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new OwnerGroups;
        return response()->json($query->destroy($request, $id));
    }
}