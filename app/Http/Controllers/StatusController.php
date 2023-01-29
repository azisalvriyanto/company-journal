<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\Statuses;
use App\Models\Status;

use DataTables;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = Status::query()
            ->select(['statuses.*']);

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
                                <a class="dropdown-item" href="' . route('payments.statuses.edit', $query->id) . '">
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
                    return route('payments.statuses.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
                'data-color-code' => function($query) {
                    return $query->color_code;
                },
            ])
            ->rawColumns(['is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('statuses.index');
    }

    public function create()
    {
        return view('statuses.create');
    }

    public function store(Request $request)
    {
        $query = new Statuses;
        return response()->json($query->store($request));
    }

    public function edit($id)
    {
        $data['query'] = Status::query()->findOrFail($id);

        return view('statuses.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new Statuses;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new Statuses;
        return response()->json($query->destroy($request, $id));
    }
}