<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\InvoiceItems;

use App\Models\InvoiceItem;

use DataTables;

class InvoiceItemController extends Controller
{
    public function index(Request $request, $invoiceId)
    {
        if (request()->ajax()) {
            $query = InvoiceItem::query()
            ->with([
                'invoice',
                'item.unitOfMeasurement',
            ])
            ->select(['invoice_items.*'])
            ->whereInvoiceId($invoiceId);

            return DataTables::eloquent($query)
            ->editColumn('item.name', function ($query) {
                if ($query->invoice->status->name == 'Draft') {
                    return '
                        <div class="tom-select-custom">
                            <select name="invoice_items[' . $query->id . '][item]" class="form-select form-item" autocomplete="off">
                                <option
                                    selected=""
                                    value="' . $query->item->id . '"
                                    data-id="' . $query->item->id . '"
                                    data-name="' . $query->item->name . '"
                                    data-unit_of_measurement_code="' . $query->item->unitOfMeasurement->code . '"
                                ></option>
                            </select>
                        </div>
                    ';
                } else {
                    return $query->item->name;
                }
            })
            ->editColumn('quantity', function ($query) {
                if ($query->invoice->status->name == 'Draft') {
                    return '
                        <div class="input-group input-group-merge">
                            <input name="invoice_items[' . $query->id . '][quantity]" type="text" class="input-count form-control text-end" placeholder="" value="' . $query->quantity . '" autocomplete="off" style="min-width: 10rem;">
                            <div name="invoice_items[' . $query->id . '][unit_of_measurement]" class="input-group-append input-group-text">' . $query->item->unitOfMeasurement->code . '</div>
                        </div>
                    ';
                } else {
                    return number_format($query->quantity, 2, '.', ',');
                }
            })
            ->editColumn('price', function ($query) {
                if ($query->invoice->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <input id="price" name="invoice_items[' . $query->id . '][price]" type="text" class="input-count form-control text-end" placeholder="" value="' . number_format($query->price, 0, '.', '') . '" autocomplete="off">
                        </div>
                    ';
                } else {
                    return number_format($query->price, 0, '.', ',');
                }
            })
            ->editColumn('total_price', function ($query) {
                if ($query->invoice->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <label name="invoice_items[' . $query->id . '][total_price]" style="padding: 0rem 1rem;">' . number_format($query->total_price, 0, '.', ',') . '</label>
                        </div>
                    ';
                } else {
                    return number_format($query->total_price, 0, '.', ',');
                }
            })
            ->editColumn('note', function ($query) {
                if ($query->invoice->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <textarea class="form-control" name="invoice_items[' . $query->id . '][note]" rows="1">' . $query->note . '</textarea>
                        </div>
                    ';
                } else {
                    return $query->note;
                }
            })
            ->addColumn('actions', function ($query) {
                if ($query->invoice->status->name == 'Draft') {
                    return '
                        <button type="button" class="btn-items-remove btn btn-xs btn-danger">
                            <i class="bi bi-trash3 me-1"></i>
                            Remove
                        </button>
                    ';
                } else {
                    return '
                        <button type="button" class="btn btn-xs btn-secondary">
                            <i class="bi bi-file-earmark-lock me-1"></i>
                            Locked
                        </button>
                    ';
                }
            })
            ->setRowAttr([
                'data-id' => function($query) {
                    return $query->id;
                },
                'data-item-id' => function($query) {
                    return $query->item_id;
                },
                'data-price' => function($query) {
                    return number_format($query->price, 10, '.', ',');
                },
                'data-total-price' => function($query) {
                    return number_format($query->total_price, 10, '.', ',');
                },
            ])
            ->rawColumns(['item.name', 'quantity', 'price', 'total_price', 'note', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }
    }

    public function store(Request $request, $invoiceId)
    {
        $query = new InvoiceItems;
        return response()->json($query->store($request, $invoiceId));
    }
}