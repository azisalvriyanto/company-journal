<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\PurchaseOrders;

use App\Models\PurchaseOrder;
use App\Models\PaymentTerm;

use DataTables;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $statuses = $data['statuses'] = collect(PurchaseOrder::STATUSES())->keyBy('name');

        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = PurchaseOrder::query()
            ->with([
                'monthlyJournal',
                'status',
                'supplier'
            ])
            ->select(['purchase_orders.*'])
            ->whereRelation('monthlyJournal', 'owner_id', $owner->id);

            return DataTables::eloquent($query)
            ->editColumn('transaction_time', function ($query) {
                return '<a class="text-primary" href="' . route('purchase-orders.show', $query->id) . '">' . date('Y-m-d', strtotime($query->transaction_time)) . '<div class="small">' . date('l, F j, Y', strtotime($query->transaction_time)) . '</div>' . '</a>';
            })
            ->editColumn('transaction_due_time', function ($query) {
                return $query->paymentTerm->name . ' / ' . date('Y-m-d', strtotime($query->transaction_due_time)) . '<div class="small">' . date('l, F j, Y', strtotime($query->transaction_due_time)) . '</div>';
            })
            ->editColumn('subtotal', function ($query) {
                return number_format($query->subtotal, 0, '.', ',');
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
            ->editColumn('total_purchase', function ($query) {
                return number_format($query->total_purchase, 0, '.', ',');
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
                                    <a class="dropdown-item" href="' . route('purchase-orders.edit', $query->id) . '">
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
                    return route('purchase-orders.show', $query->id);
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
            ->rawColumns(['transaction_time', 'transaction_due_time', 'code', 'total_price', 'total_shipping', 'status.name', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('purchase-orders.index', $data);
    }
}