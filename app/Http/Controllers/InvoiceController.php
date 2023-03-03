<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\Invoices;

use App\Models\Invoice;
use App\Models\PaymentTerm;

use DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $statuses = collect(Invoice::STATUSES())->keyBy('name');
            $query = Invoice::query()
            ->with([
                'monthlyJournal',
                'status',
                'customer'
            ])
            ->select(['invoices.*'])
            ->whereRelation('monthlyJournal', 'owner_id', $owner->id);

            return DataTables::eloquent($query)
            ->editColumn('transaction_time', function ($query) {
                return '<a class="text-primary" href="' . route('invoices.show', $query->id) . '">' . date('Y-m-d', strtotime($query->transaction_time)) . '<div class="small">' . date('l, F j, Y', strtotime($query->transaction_time)) . '</div>' . '</a>';
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
            ->editColumn('total_invoice', function ($query) {
                return number_format($query->total_invoice, 0, '.', ',');
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
                                    <a class="dropdown-item" href="' . route('invoices.edit', $query->id) . '">
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
                    return route('invoices.show', $query->id);
                },
                'data-transaction-time' => function($query) {
                    return date('l, F j, Y H:i:s', strtotime($query->transaction_time));
                },
                'data-code' => function($query) {
                    return $query->code;
                },
                'data-total-invoice' => function($query) {
                    return number_format($query->total_invoice, 10, '.', ',');
                },
            ])
            ->rawColumns(['transaction_time', 'transaction_due_time', 'code', 'total_price', 'total_shipping', 'status.name', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        $data['statuses'] = Invoice::STATUSES();

        return view('invoices.index', $data);
    }

    public function create()
    {
        $owner = auth()->user()->parentCompany;
        $data['paymentTerms'] = PaymentTerm::query()
        ->whereIn('payment_terms.owner_id', [
            $owner->id,
            $owner->parent_company_id
        ])->orderBy('value')->get()->all();

        return view('invoices.create', $data);
    }

    public function store(Request $request)
    {
        $query = new Invoices;
        return response()->json($query->store($request));
    }

    public function show($id)
    {
        $data['query'] = Invoice::query()->findOrFail($id);
        $data['statuses'] = collect(Invoice::STATUSES())->keyBy('name');

        return view('invoices.show', $data);
    }

    public function edit($id)
    {
        $data['query'] = Invoice::query()->findOrFail($id);
        $owner = auth()->user()->parentCompany;
        $data['paymentTerms'] = PaymentTerm::query()
        ->whereIn('payment_terms.owner_id', [
            $owner->id,
            $owner->parent_company_id
        ])->orderBy('value')->get()->all();

        return view('invoices.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new Invoices;
        return response()->json($query->update($request, $id));
    }

    public function updateStatus(Request $request, $id)
    {
        $query = new Invoices;
        return response()->json($query->updateStatus($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new Invoices;
        return response()->json($query->destroy($request, $id));
    }
}