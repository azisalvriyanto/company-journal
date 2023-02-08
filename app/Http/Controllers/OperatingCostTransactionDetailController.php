<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\OperatingCostTransactionDetails;

use App\Models\OperatingCostTransactionDetail;

use DataTables;

class OperatingCostTransactionDetailController extends Controller
{
    public function index(Request $request, $operatingCostTransactionDetailId)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = OperatingCostTransactionDetail::query()
            ->with([
                'operatingCostTransaction',
                'operatingCost',
            ])
            ->select(['operating_cost_transaction_details.*'])
            ->whereOperatingCostTransactionId($operatingCostTransactionDetailId);

            return DataTables::eloquent($query)
            ->editColumn('operating_cost.name', function ($query) {
                if ($query->operatingCostTransaction->status->name == 'Draft') {
                    return '
                        <div class="tom-select-custom">
                            <select name="operating_cost_transaction_details[' . $query->id . '][operating_cost]" class="form-select" autocomplete="off">
                                <option
                                    selected=""
                                    value="' . $query->operatingCost->id . '"
                                    data-id="' . $query->operatingCost->id . '"
                                    data-name="' . $query->operatingCost->name . '"
                                ></option>
                            </select>
                        </div>
                    ';
                } else {
                    return $query->operatingCost->name;
                }
            })
            ->editColumn('quantity', function ($query) {
                if ($query->operatingCostTransaction->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <input id="price" name="operating_cost_transaction_details[' . $query->id . '][quantity]" type="text" class="input-count form-control text-end" placeholder="" value="' . number_format($query->quantity, 2, '.', '') . '" autocomplete="off">
                        </div>
                    ';
                } else {
                    return number_format($query->quantity, 2, '.', ',');
                }
            })
            ->editColumn('price', function ($query) {
                if ($query->operatingCostTransaction->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <input id="price" name="operating_cost_transaction_details[' . $query->id . '][price]" type="text" class="input-count form-control text-end" placeholder="" value="' . number_format($query->price, 2, '.', '') . '" autocomplete="off">
                        </div>
                    ';
                } else {
                    return number_format($query->price, 2, '.', ',');
                }
            })
            ->editColumn('total_price', function ($query) {
                if ($query->operatingCostTransaction->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <label name="operating_cost_transaction_details[' . $query->id . '][total_price]">' . number_format($query->total_price, 0, '.', ',') . '</label>
                        </div>
                    ';
                } else {
                    return number_format($query->total_price, 0, '.', '');
                }
            })
            ->editColumn('note', function ($query) {
                if ($query->operatingCostTransaction->status->name == 'Draft') {
                    return '
                        <div class="form-group">
                            <textarea class="form-control" name="operating_cost_transaction_details[' . $query->id . '][note]" rows="1">' . $query->note . '</textarea>
                        </div>
                    ';
                } else {
                    return $query->note;
                }
            })
            ->addColumn('actions', function ($query) {
                if ($query->operatingCostTransaction->status->name == 'Draft') {
                    return '
                        <button type="button" class="btn-details-remove btn btn-xs btn-danger">
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
                'data-operating-cost-id' => function($query) {
                    return $query->operating_cost_id;
                },
                'data-price' => function($query) {
                    return number_format($query->price, 10, '.', ',');
                },
                'data-total-price' => function($query) {
                    return number_format($query->total_price, 10, '.', ',');
                },
            ])
            ->rawColumns(['operating_cost.name', 'quantity', 'price', 'total_price', 'note', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }
    }

    public function store(Request $request, $operatingCostTransactionDetailId)
    {
        $query = new OperatingCostTransactionDetails;
        return response()->json($query->store($request, $operatingCostTransactionDetailId));
    }
}