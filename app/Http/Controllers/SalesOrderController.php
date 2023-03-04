<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\SalesOrders;

use App\Models\SalesOrder;
use App\Models\PaymentTerm;

use DataTables;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $statuses = $data['statuses'] = collect(SalesOrder::STATUSES())->keyBy('name');

        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = SalesOrder::query()
            ->with([
                'monthlyJournal',
                'status',
                'vendor'
            ])
            ->select(['sales_orders.*'])
            ->whereRelation('monthlyJournal', 'owner_id', $owner->id);

            return DataTables::eloquent($query)
            ->editColumn('transaction_time', function ($query) {
                return '<a class="text-primary" href="' . route('sales-orders.show', $query->id) . '">' . date('Y-m-d', strtotime($query->transaction_time)) . '<div class="small">' . date('l, F j, Y', strtotime($query->transaction_time)) . '</div>' . '</a>';
            })
            ->editColumn('order_deadline', function ($query) {
                return date('Y-m-d', strtotime($query->order_deadline)) . '<div class="small">' . date('l, F j, Y', strtotime($query->order_deadline)) . '</div>';
            })
            ->editColumn('total_sales', function ($query) {
                return number_format($query->total_sales, 0, '.', ',');
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
                                    <a class="dropdown-item" href="' . route('sales-orders.edit', $query->id) . '">
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
                                    $query->status->name == 'Quotation' ? '' : '
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="' . $statuses['Quotation']['id'] . '" data-name="Quotation">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Quotation
                                    </a>
                                ' ) . '
                                ' . (
                                    $query->status->name == 'Close' ? '' : '
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="' . $statuses['Close']['id'] . '" data-name="Close">
                                        <i class="bi bi-file-earmark-x dropdown-item-icon"></i> Cancel
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
                    return route('sales-orders.show', $query->id);
                },
                'data-transaction-time' => function($query) {
                    return date('l, F j, Y H:i:s', strtotime($query->transaction_time));
                },
                'data-code' => function($query) {
                    return $query->code;
                },
                'data-total-bill' => function($query) {
                    return number_format($query->total_price, 10, '.', ',');
                },
            ])
            ->rawColumns(['transaction_time', 'order_deadline', 'code', 'total_price', 'total_shipping', 'status.name', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('sales-orders.index', $data);
    }

    public function create()
    {
        $owner = auth()->user()->parentCompany;
        $data['paymentTerms'] = PaymentTerm::query()
        ->whereIn('payment_terms.owner_id', [
            $owner->id,
            $owner->parent_company_id
        ])->orderBy('value')->get()->all();

        return view('sales-orders.create', $data);
    }

    public function store(Request $request)
    {
        $query = new SalesOrders;
        return response()->json($query->store($request));
    }

    public function show($id)
    {
        $data['query'] = SalesOrder::query()->findOrFail($id);
        $data['statuses'] = collect(SalesOrder::STATUSES())->keyBy('name');

        return view('sales-orders.show', $data);
    }

    public function edit($id)
    {
        $data['query'] = SalesOrder::query()->findOrFail($id);
        $owner = auth()->user()->parentCompany;
        $data['paymentTerms'] = PaymentTerm::query()
        ->whereIn('payment_terms.owner_id', [
            $owner->id,
            $owner->parent_company_id
        ])->orderBy('value')->get()->all();

        return view('sales-orders.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new SalesOrders;
        return response()->json($query->update($request, $id));
    }

    public function updateStatus(Request $request, $id)
    {
        $query = new SalesOrders;
        return response()->json($query->updateStatus($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new SalesOrders;
        return response()->json($query->destroy($request, $id));
    }
}