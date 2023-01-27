<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\PaymentMethods;
use App\Models\PaymentMethod;

use DataTables;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = PaymentMethod::query()
            ->select(['payment_methods.*'])
            ->whereIn('payment_methods.owner_id', [
                $owner->id,
                $owner->parent_company_id
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
            ])
            ->rawColumns(['is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('payment-methods.index');
    }

    public function create()
    {
        return view('payment-methods.create');
    }

    public function store(Request $request)
    {
        $query = new PaymentMethods;
        return $query->store($request);
    }
}