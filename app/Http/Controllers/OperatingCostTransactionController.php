<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\OperatingCostTransactions;

use App\Models\OperatingCostTransaction;

use DataTables;

class OperatingCostTransactionController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = OperatingCostTransaction::query()
            ->with([
                'monthlyJournal',
                'status',
            ])
            ->select(['operating_cost_transactions.*'])
            ->whereRelation('monthlyJournal', 'owner_id', $owner->id);

            return DataTables::eloquent($query)
            ->editColumn('transaction_time', function ($query) {
                return '<a class="text-primary" href="' . route('operating-cost-transactions.show', $query->id) . '">' . date('Y-m-d', strtotime($query->transaction_time)) . '<div class="small">' . date('l, F j, Y', strtotime($query->transaction_time)) . '</div>' . '</a>';
            })
            ->editColumn('total_price', function ($query) {
                return number_format($query->total_price, 0, '.', ',');
            })
            ->editColumn('status.name', function ($query) {
                return '<span class="badge ' . $query->status->background_color . ' ' . $query->status->font_color . '">' . $query->status->name . '</span>';
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
                                <a class="dropdown-item" href="' . route('operating-cost-transactions.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
                                </a>
                                <a class="dropdown-item datatable-btn-lock" href="javascript:;">
                                    <i class="bi-trash dropdown-item-icon"></i> Lock
                                </a>
                                <a class="dropdown-item datatable-btn-cancel" href="javascript:;">
                                    <i class="bi-trash dropdown-item-icon"></i> Cancel
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
                    return route('operating-cost-transactions.show', $query->id);
                },
                'data-transaction-time' => function($query) {
                    return date('l, F j, Y H:i:s', strtotime($query->transaction_time));
                },
                'data-code' => function($query) {
                    return $query->code;
                },
                'data-total-price' => function($query) {
                    return number_format($query->total_price, 10, '.', ',');
                },
            ])
            ->rawColumns(['transaction_time', 'code', 'total_price', 'status.name', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        $data['statuses'] = OperatingCostTransaction::STATUSES();

        return view('operating-cost-transactions.index', $data);
    }

    public function create()
    {
        return view('operating-cost-transactions.create');
    }

    public function store(Request $request)
    {
        $query = new OperatingCostTransactions;
        return response()->json($query->store($request));
    }

    public function show($id)
    {
        $data['query'] = OperatingCostTransaction::query()->findOrFail($id);

        return view('operating-cost-transactions.show', $data);
    }

    public function edit($id)
    {
        $data['query'] = OperatingCostTransaction::query()->findOrFail($id);

        return view('operating-cost-transactions.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new OperatingCostTransactions;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new OperatingCostTransactions;
        return response()->json($query->destroy($request, $id));
    }
}