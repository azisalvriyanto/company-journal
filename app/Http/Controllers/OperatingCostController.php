<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\OperatingCosts;

use App\Models\OperatingCost;
use App\Models\UnitOfMeasurement;

use DataTables;

class OperatingCostController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = OperatingCost::query()
            ->with([
                'unitOfMeasurement',
            ])
            ->select(['operating_costs.*'])
            ->whereIn('operating_costs.owner_id', [
                $owner->id,
                $owner->parent_company_id
            ]);

            return DataTables::eloquent($query)
            ->editColumn('default_cost', function ($query) {
                return number_format($query->default_cost, 10, '.', ',');
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
                                <a class="dropdown-item" href="' . route('operating-costs.edit', $query->id) . '">
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
                    return route('operating-costs.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
                'data-unit-of-measurement' => function($query) {
                    return $query->unitOfMeasurement->name;
                },
            ])
            ->rawColumns(['is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('operating-costs.index');
    }

    public function create()
    {
        $data['unitOfMeasurements'] = UnitOfMeasurement::query()->orderBy('name')->get()->all();

        return view('operating-costs.create', $data);
    }

    public function store(Request $request)
    {
        $query = new OperatingCosts;
        return response()->json($query->store($request));
    }

    public function edit($id)
    {
        $data['query']              = OperatingCost::query()->findOrFail($id);
        $data['unitOfMeasurements'] = UnitOfMeasurement::query()->orderBy('name')->get()->all();

        return view('operating-costs.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new OperatingCosts;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new OperatingCosts;
        return response()->json($query->destroy($request, $id));
    }
}