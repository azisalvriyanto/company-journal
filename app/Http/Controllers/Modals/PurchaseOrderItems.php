<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class PurchaseOrderItems extends Controller
{
    public function store($request, $purchaseOrderId)
    {
        $query = PurchaseOrder::query()->find($purchaseOrderId);
        if ($query) {
            $monthlyJournal = $query->monthlyJournal;
            if ($monthlyJournal->status->name == 'Draft') {
                if ($query->status->name == 'Draft') {
                    try {
                        DB::beginTransaction();

                        $purchaseOrderItemIds = $query->purchaseOrderItems->pluck('id', 'id')->toArray();
                        if ($request->purchase_order_items) {
                            foreach($request->purchase_order_items as $purchaseOrderItemId => $purchaseOrderItem) {
                                if (in_array($purchaseOrderItemId, $purchaseOrderItemIds)) {
                                    $queryPurchaseOrderItem                 = PurchaseOrderItem::query()->find($purchaseOrderItemId);
                                } else {
                                    $queryPurchaseOrderItem                 = new PurchaseOrderItem;
                                }

                                $queryPurchaseOrderItem->purchase_order_id  = $purchaseOrderId;
                                $queryPurchaseOrderItem->item_id            = array_key_exists('item', $purchaseOrderItem) ? $purchaseOrderItem['item'] : NULL;
                                $queryPurchaseOrderItem->quantity           = number_format((double) filter_var(array_key_exists('quantity', $purchaseOrderItem)    ? $purchaseOrderItem['quantity']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryPurchaseOrderItem->price              = number_format((double) filter_var(array_key_exists('price', $purchaseOrderItem)       ? $purchaseOrderItem['price']      : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryPurchaseOrderItem->total_price        = number_format($queryPurchaseOrderItem->quantity*$queryPurchaseOrderItem->price, 10, '.', '');
                                $queryPurchaseOrderItem->note               = array_key_exists('note', $purchaseOrderItem) ? $purchaseOrderItem['note'] : NULL;
                                $queryPurchaseOrderItem->save();

                                if (in_array($queryPurchaseOrderItem->id, $purchaseOrderItemIds)) {
                                    unset($purchaseOrderItemIds[$queryPurchaseOrderItem->id]);
                                }
                            }
                        }

                        if ($purchaseOrderItemIds) {
                            PurchaseOrderItem::query()
                            ->wherePurchaseOrderId($purchaseOrderId)
                            ->whereIn('id', $purchaseOrderItemIds)
                            ->delete();
                        }

                        $purchaseOrderItems = PurchaseOrderItem::query()
                        ->wherePurchaseOrderId($purchaseOrderId)
                        ->get();
                        $query->total_purchase = $purchaseOrderItems->sum('total_price');
                        $query->save();

                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Purchase order item updated in successfully.',
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
                        'message'   => 'Purchase order not writable.',
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
                'message'   => 'Purchase order not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}