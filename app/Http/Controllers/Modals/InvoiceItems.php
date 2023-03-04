<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class InvoiceItems extends Controller
{
    public function store($request, $invoiceId)
    {
        $query = Invoice::query()->find($invoiceId);
        if ($query) {
            $monthlyJournal = $query->monthlyJournal;
            if ($monthlyJournal->status->name == 'Draft') {
                if ($query->status->name == 'Draft') {
                    try {
                        DB::beginTransaction();

                        $invoiceItemIds = $query->invoiceItems->pluck('id', 'id')->toArray();
                        if ($request->invoice_items) {
                            foreach($request->invoice_items as $invoiceItemId => $invoiceItem) {
                                if (in_array($invoiceItemId, $invoiceItemIds)) {
                                    $queryInvoiceItem           = InvoiceItem::query()->find($invoiceItemId);
                                } else {
                                    $queryInvoiceItem           = new InvoiceItem;
                                }
                                $queryInvoiceItem->invoice_id   = $invoiceId;
                                $queryInvoiceItem->item_id      = array_key_exists('item', $invoiceItem) ? $invoiceItem['item'] : NULL;
                                $queryInvoiceItem->quantity     = number_format((double) filter_var(array_key_exists('quantity', $invoiceItem)    ? $invoiceItem['quantity']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryInvoiceItem->price        = number_format((double) filter_var(array_key_exists('price', $invoiceItem)       ? $invoiceItem['price']      : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                                $queryInvoiceItem->total_price  = number_format($queryInvoiceItem->quantity*$queryInvoiceItem->price, 0, '.', '');
                                $queryInvoiceItem->note         = array_key_exists('note', $invoiceItem) ? $invoiceItem['note'] : NULL;
                                $queryInvoiceItem->save();

                                if (in_array($queryInvoiceItem->id, $invoiceItemIds)) {
                                    unset($invoiceItemIds[$queryInvoiceItem->id]);
                                }
                            }
                        }

                        if ($invoiceItemIds) {
                            InvoiceItem::query()
                            ->whereInvoiceId($invoiceId)
                            ->whereIn('id', $invoiceItemIds)
                            ->delete();
                        }

                        $invoiceItems = InvoiceItem::query()
                        ->whereInvoiceId($invoiceId)
                        ->get();
                        $query->subtotal = $invoiceItems->sum('total_price');
                        $query->total_shipping          = number_format((double) filter_var(array_key_exists('total_shipping', $request->invoice)   ? $request->invoice['total_shipping']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                        $query->total_shipping_discount = 0;
                        $query->total_discount          = number_format((double) filter_var(array_key_exists('total_discount', $request->invoice)   ? $request->invoice['total_discount']   : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                        $query->total_tax_value         = number_format((double) filter_var(array_key_exists('total_tax_value', $request->invoice)  ? $request->invoice['total_tax_value']  : 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                        $query->total_tax_type          = array_key_exists('total_tax_type', $request->invoice) ? ($request->invoice['total_tax_type'] == 'Percent' ? 'Percent' : 'Flat')   : 'Percent';

                        $subTotal                       = $query->subtotal + $query->total_shipping - $query->total_discount;
                        $query->total_tax               = $query->total_tax_type == 'Percent' ? $subTotal*($query->total_tax_value/100) : $query->total_tax_value;
                        $query->total_invoice           = $subTotal + $query->total_tax;
                        $query->total_amount_paid       = 0;
                        $query->total_due_balance       = $query->total_invoice - $query->total_amount_paid;

                        $query->save();

                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Invoice item updated in successfully.',
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