<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class SalesOrderItems extends Controller
{
    public function store($request, $salesOrderId)
    {
        $query = SalesOrder::query()->find($salesOrderId);
        if ($query) {
            $monthlyJournal = $query->monthlyJournal;
            if ($monthlyJournal->status->name == 'Draft') {
                if ($query->status->name == 'Draft') {
                    try {
                        DB::beginTransaction();

                        $salesOrderItemIds = $query->salesOrderItems->pluck('id', 'id')->toArray();
                        if ($request->sales_order_items) {
                            foreach($request->sales_order_items as $salesOrderItemId => $salesOrderItem) {
                                if (in_array($salesOrderItemId, $salesOrderItemIds)) {
                                    $querySalesOrderItem                = SalesOrderItem::query()->find($salesOrderItemId);
                                } else {
                                    $querySalesOrderItem                = new SalesOrderItem;
                                }

                                $querySalesOrderItem->sales_order_id    = $salesOrderId;
                                $querySalesOrderItem->item_id           = array_key_exists('item', $salesOrderItem) ? $salesOrderItem['item'] : NULL;
                                $querySalesOrderItem->quantity          = number_format((double) filter_var(array_key_exists('quantity', $salesOrderItem)    ? $salesOrderItem['quantity']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $querySalesOrderItem->price             = number_format((double) filter_var(array_key_exists('price', $salesOrderItem)       ? $salesOrderItem['price']      : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $querySalesOrderItem->total_price       = number_format($querySalesOrderItem->quantity*$querySalesOrderItem->price, 10, '.', '');
                                $querySalesOrderItem->note              = array_key_exists('note', $salesOrderItem) ? $salesOrderItem['note'] : NULL;
                                $querySalesOrderItem->save();

                                if (in_array($querySalesOrderItem->id, $salesOrderItemIds)) {
                                    unset($salesOrderItemIds[$querySalesOrderItem->id]);
                                }
                            }
                        }

                        if ($salesOrderItemIds) {
                            SalesOrderItem::query()
                            ->whereSalesOrderId($salesOrderId)
                            ->whereIn('id', $salesOrderItemIds)
                            ->delete();
                        }

                        $salesOrderItems = SalesOrderItem::query()
                        ->whereSalesOrderId($salesOrderId)
                        ->get();
                        $query->total_sales = $salesOrderItems->sum('total_price');
                        $query->save();

                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Sales order item updated in successfully.',
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
                        'message'   => 'Sales order not writable.',
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
                'message'   => 'Sales order not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}