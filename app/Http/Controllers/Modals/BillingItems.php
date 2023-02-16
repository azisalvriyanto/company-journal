<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class BillingItems extends Controller
{
    public function store($request, $billingId)
    {
        $query = Billing::query()->find($billingId);
        if ($query) {
            $monthlyJournal = $query->monthlyJournal;
            if ($monthlyJournal->status->name == 'Draft') {
                if ($query->status->name == 'Draft') {
                    try {
                        DB::beginTransaction();

                        $billingIds = $query->billingItems->pluck('id', 'id')->toArray();
                        if ($request->billing_items) {
                            foreach($request->billing_items as $billingItem) {
                                $queryBillingItem               = new BillingItem;
                                $queryBillingItem->billing_id   = $billingId;
                                $queryBillingItem->item_id      = array_key_exists('item', $billingItem) ? $billingItem['item'] : NULL;
                                $queryBillingItem->quantity     = number_format((double) filter_var(array_key_exists('quantity', $billingItem)    ? $billingItem['quantity']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryBillingItem->price        = number_format((double) filter_var(array_key_exists('price', $billingItem)       ? $billingItem['price']      : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryBillingItem->total_price  = number_format($queryBillingItem->quantity*$queryBillingItem->price, 0, '.', '');
                                $queryBillingItem->note         = array_key_exists('note', $billingItem) ? $billingItem['note'] : NULL;
                                $queryBillingItem->save();

                                if (in_array($queryBillingItem->id, $billingIds)) {
                                    unset($billingIds[$queryBillingItem->id]);
                                }
                            }
                        }

                        if ($billingIds) {
                            BillingItem::query()
                            ->whereBillingId($billingId)
                            ->whereIn('id', $billingIds)
                            ->delete();
                        }

                        $billingItems = BillingItem::query()
                        ->whereBillingId($billingId)
                        ->get();
                        $query->subtotal = $billingItems->sum('total_price');
                        $query->total_shipping          = number_format((double) filter_var(array_key_exists('total_shipping', $request->billing)   ? $request->billing['total_shipping']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                        $query->total_shipping_discount = 0;
                        $query->total_discount          = number_format((double) filter_var(array_key_exists('total_discount', $request->billing)   ? $request->billing['total_discount']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                        $query->total_tax_value         = number_format((double) filter_var(array_key_exists('total_tax_value', $request->billing)  ? $request->billing['total_tax_value']  : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                        $query->total_tax_type          = array_key_exists('total_tax_type', $request->billing) ? ($request->billing['total_tax_type'] == 'Percent' ? 'Percent' : 'Flat')   : 'Percent';

                        $subTotal                       = $query->subtotal + $query->total_shipping - $query->total_discount;
                        $query->total_tax               = $query->total_tax_type == 'Percent' ? $subTotal*($query->total_tax_value/100) : $query->total_tax_value;
                        $query->total_bill              = $subTotal + $query->total_tax;
                        $query->total_amount_paid       = 0;
                        $query->total_due_balance       = $query->total_bill - $query->total_amount_paid;

                        $query->save();

                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Billing item updated in successfully.',
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