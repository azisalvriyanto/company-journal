<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\SalesOrderItems;

use App\Models\SalesOrderItem;

use DataTables;

class SalesOrderItemController extends Controller
{
    public function index(Request $request, $salesOrderId)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = SalesOrderItem::query()
            ->with([
                'salesOrder',
                'item',
            ])
            ->select(['sales_order_items.*'])
            ->whereSalesOrderId($salesOrderId);

            return DataTables::eloquent($query)
            ->editColumn('item.name', function ($query) {
                if ($query->salesOrder->status->name == 'Draft') {
                    return '
                        <div class="tom-select-custom">
                            <select name="sales_order_items[' . $query->id . '][item]" class="form-select" autocomplete="off">
                                <option
                                    selected=""
                                    value="' . $query->item->id . '"
                                    data-id="' . $query->item->id . '"
                                    data-name="' . $query->item->name . '"
                                ></option>
                            </select>
                        </div>
                    ';
                } else {
                    return $query->item->name;
                }
            })
            ->editColumn('quantity', function ($query) {
                if ($query->salesOrder->status->name == 'Draft') {
                    return '
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend input-group-text bg-white">
                                <a class="js-minus btn btn-white btn-xs btn-icon rounded-circle ms-0 me-1" href="javascript:;">
                                    <svg width="8" height="2" viewBox="0 0 8 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 1C0 0.723858 0.223858 0.5 0.5 0.5H7.5C7.77614 0.5 8 0.723858 8 1C8 1.27614 7.77614 1.5 7.5 1.5H0.5C0.223858 1.5 0 1.27614 0 1Z" fill="currentColor" />
                                    </svg>
                                </a>
                                <a class="js-plus btn btn-white btn-xs btn-icon rounded-circle ms-1 me-0" href="javascript:;">
                                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 0C4.27614 0 4.5 0.223858 4.5 0.5V3.5H7.5C7.77614 3.5 8 3.72386 8 4C8 4.27614 7.77614 4.5 7.5 4.5H4.5V7.5C4.5 7.77614 4.27614 8 4 8C3.72386 8 3.5 7.77614 3.5 7.5V4.5H0.5C0.223858 4.5 0 4.27614 0 4C0 3.72386 0.223858 3.5 0.5 3.5H3.5V0.5C3.5 0.223858 3.72386 0 4 0Z" fill="currentColor" />
                                    </svg>
                                </a>
                            </div>
                            <input id="price" name="sales_order_items[' . $query->id . '][quantity]" type="text" class="input-count form-control text-end" placeholder="" value="' . number_format($query->quantity, 2, '.', '') . '" autocomplete="off" style="min-width: 15rem;">
                            <div name="sales_order_items[' . $query->id . '][unit_of_measurement]" class="input-group-append input-group-text border-0">' . $query->item->unitOfMeasurement->code . '</div>
                        </div>
                    ';
                } else {
                    return number_format($query->quantity, 2, '.', ',');
                }
            })
            ->editColumn('price', function ($query) {
                if ($query->salesOrder->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <input id="price" name="sales_order_items[' . $query->id . '][price]" type="text" class="input-count form-control text-end" placeholder="" value="' . number_format($query->price, 2, '.', '') . '" autocomplete="off">
                        </div>
                    ';
                } else {
                    return number_format($query->price, 2, '.', ',');
                }
            })
            ->editColumn('total_price', function ($query) {
                if ($query->salesOrder->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <label name="sales_order_items[' . $query->id . '][total_price]">' . number_format($query->total_price, 0, '.', ',') . '</label>
                        </div>
                    ';
                } else {
                    return number_format($query->total_price, 0, '.', ',');
                }
            })
            ->editColumn('note', function ($query) {
                if ($query->salesOrder->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <textarea class="form-control" name="sales_order_items[' . $query->id . '][note]" rows="1">' . $query->note . '</textarea>
                        </div>
                    ';
                } else {
                    return $query->note;
                }
            })
            ->addColumn('actions', function ($query) {
                if ($query->salesOrder->status->name == 'Draft') {
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

    public function store(Request $request, $salesOrderId)
    {
        $query = new SalesOrderItems;
        return response()->json($query->store($request, $salesOrderId));
    }
}