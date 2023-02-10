<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\Billings;

use App\Models\Billing;

use DataTables;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $statuses = collect(Billing::STATUSES())->keyBy('name');
            $query = Billing::query()
            ->with([
                'monthlyJournal',
                'status',
            ])
            ->select(['billings.*'])
            ->whereRelation('monthlyJournal', 'owner_id', $owner->id);

            return DataTables::eloquent($query)
            ->editColumn('transaction_time', function ($query) {
                return '<a class="text-primary" href="' . route('billings.show', $query->id) . '">' . date('Y-m-d', strtotime($query->transaction_time)) . '<div class="small">' . date('l, F j, Y', strtotime($query->transaction_time)) . '</div>' . '</a>';
            })
            ->editColumn('sub_total', function ($query) {
                return number_format($query->sub_total, 0, '.', ',');
            })
            ->editColumn('total_shipping', function ($query) {
                return number_format($query->total_shipping, 0, '.', ',');
            })
            ->editColumn('total_discount', function ($query) {
                return number_format($query->total_discount, 0, '.', ',');
            })
            ->editColumn('total_tax', function ($query) {
                return number_format($query->total_tax, 0, '.', ',');
            })
            ->editColumn('total_price', function ($query) {
                return number_format($query->total_price, 0, '.', ',');
            })
            ->editColumn('status.name', function ($query) {
                return '<span class="badge ' . $query->status->background_color . ' ' . $query->status->font_color . '">' . $query->status->name . '</span>';
            })
            ->addColumn('actions', function ($query) use ($statuses) {
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
                                ' . (
                                    $query->status->name == 'Draft' ? '
                                    <a class="dropdown-item" href="' . route('billings.edit', $query->id) . '">
                                        <i class="bi bi-pencil dropdown-item-icon"></i> Edit
                                    </a>
                                    ' : '
                                ' ) . '
                                ' . (
                                    $query->status->name == 'Draft' ? '' : '
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="' . $statuses['Draft']['id'] . '" data-name="Draft">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Draft
                                    </a>
                                ' ) . '
                                ' . (
                                    $query->status->name == 'Lock' ? '' : '
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="' . $statuses['Lock']['id'] . '" data-name="Lock">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Lock
                                    </a>
                                ' ) . '
                                ' . (
                                    $query->status->name == 'Cancel' ? '' : '
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="' . $statuses['Cancel']['id'] . '" data-name="Cancel">
                                        <i class="bi bi-file-earmark-x dropdown-item-icon"></i> Cancel
                                    </a>
                                ' ) . '
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
                    return route('billings.show', $query->id);
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

        $data['statuses'] = Billing::STATUSES();

        return view('billings.index', $data);
    }

    public function create()
    {
        return view('billings.create');
    }

    public function store(Request $request)
    {
        $query = new Billings;
        return response()->json($query->store($request));
    }

    public function show($id)
    {
        $data['query'] = Billing::query()->findOrFail($id);
        $data['statuses'] = collect(Billing::STATUSES())->keyBy('name');

        return view('billings.show', $data);
    }

    public function edit($id)
    {
        $data['query'] = Billing::query()->findOrFail($id);

        return view('billings.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new Billings;
        return response()->json($query->update($request, $id));
    }

    public function updateStatus(Request $request, $id)
    {
        $query = new Billings;
        return response()->json($query->updateStatus($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new Billings;
        return response()->json($query->destroy($request, $id));
    }
}