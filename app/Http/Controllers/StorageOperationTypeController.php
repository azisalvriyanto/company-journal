<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\StorageOperationTypes;
use App\Models\StorageOperationType;

use DataTables;

class StorageOperationTypeController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = StorageOperationType::query()
            ->with([
                'storage',
                'operationType',
            ])
            ->select(['storage_operation_types.*']);

            return DataTables::eloquent($query)
            ->editColumn('operation_type.name', function ($query) {
                return ($query->operationType->group == 'In' ? '<span class="me-1 badge bg-soft-success text-success" style="width: 40px;">In</span>' : ($query->operationType->group == 'Out' ? '<span class="me-1 badge bg-soft-danger text-danger" style="width: 40px;">Out</span>' : '<span class="me-1 badge bg-soft-secondary text-muted" style="width: 40px;">Undifened</span>')) . ' ' . $query->operationType->name;
            })
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
                                <a class="dropdown-item" href="' . route('storage-operation-types.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
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
                    return route('storage-operation-types.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
            ])
            ->rawColumns(['operation_type.name', 'is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('storage-operation-types.index');
    }

    public function edit($id)
    {
        $data['query'] = StorageOperationType::query()->findOrFail($id);

        return view('storage-operation-types.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new StorageOperationTypes;
        return response()->json($query->update($request, $id));
    }
}