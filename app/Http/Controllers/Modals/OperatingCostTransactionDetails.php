<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\OperatingCostTransaction;
use App\Models\OperatingCostTransactionDetail;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class OperatingCostTransactionDetails extends Controller
{
    public function store($request, $operatingCostTransactionDetailId)
    {
        $query = OperatingCostTransaction::query()->find($operatingCostTransactionDetailId);
        if ($query) {
            $monthlyJournal = $query->monthlyJournal;
            if ($monthlyJournal->status->name == 'Draft') {
                if ($query->status->name == 'Draft') {
                    try {
                        DB::beginTransaction();

                        $operatingCostTransactionDetailIds = $query->operatingCostTransactionDetails->pluck('id', 'id')->toArray();
                        if ($request->operating_cost_transaction_details) {
                            foreach($request->operating_cost_transaction_details as $operatingCostTransactionDetail) {
                                $queryOperatingCostTransactionDetail                                = new OperatingCostTransactionDetail;
                                $queryOperatingCostTransactionDetail->operating_cost_transaction_id = $operatingCostTransactionDetailId;
                                $queryOperatingCostTransactionDetail->operating_cost_id             = array_key_exists('operating_cost', $operatingCostTransactionDetail)   ? $operatingCostTransactionDetail['operating_cost'] : NULL;
                                $queryOperatingCostTransactionDetail->quantity                      = number_format((double) filter_var(array_key_exists('quantity', $operatingCostTransactionDetail)    ? $operatingCostTransactionDetail['quantity']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryOperatingCostTransactionDetail->price                         = number_format((double) filter_var(array_key_exists('price', $operatingCostTransactionDetail)       ? $operatingCostTransactionDetail['price']      : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryOperatingCostTransactionDetail->total_price                   = number_format($queryOperatingCostTransactionDetail->quantity*$queryOperatingCostTransactionDetail->price, 0, '.', '');
                                $queryOperatingCostTransactionDetail->save();

                                if (in_array($queryOperatingCostTransactionDetail->id, $operatingCostTransactionDetailIds)) {
                                    unset($operatingCostTransactionDetailIds[$queryOperatingCostTransactionDetail->id]);
                                }
                            }
                        }

                        if ($operatingCostTransactionDetailIds) {
                            OperatingCostTransactionDetail::query()
                            ->whereOperatingCostTransactionId($operatingCostTransactionDetailId)
                            ->whereIn('id', $operatingCostTransactionDetailIds)
                            ->delete();
                        }


                        $operatingCostTransactionDetails = OperatingCostTransactionDetail::query()
                        ->whereOperatingCostTransactionId($operatingCostTransactionDetailId)
                        ->get();
                        $query->total_price = $operatingCostTransactionDetails->sum('total_price');
                        $query->save();


                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Operating cost transaction detail updated in successfully.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    } catch (\Exception $e) {
                        DB::rollback();
                        $response = [
                            'status'    => 500,
                            'message'   => $e->getMessage(),
                            'data'      => NULL,
                            'errors'    => [],
                        ];
                    }
                } else {
                    $response = [
                        'status'    => 404,
                        'message'   => 'Operating cost transaction not writable.',
                        'data'      => $query,
                        'errors'    => [],
                    ];
                }
            } else {
                $response = [
                    'status'    => 404,
                    'message'   => 'Monthly journal not writable.',
                    'data'      => $monthlyJournal,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 404,
                'message'   => 'Operating cost transaction not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}