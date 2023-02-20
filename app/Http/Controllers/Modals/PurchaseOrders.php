<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PaymentTerm;
use App\Models\Status;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class PurchaseOrders extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'time'              => 'required|string',
            'order_deadline'    => 'required|string',
            'payment_term'      => 'required|exists:payment_terms,id',
            'vendor'            => 'required|exists:users,id',
            'vendor_address'    => 'required|exists:contacts,id',
            'internal_code'     => 'nullable|string',
            'note'              => 'nullable|string',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $time = date('Y-m-d 00:00:00', strtotime($request->time));

                $monthlyJournalRequest = new Request;
                $monthlyJournalRequest->replace([
                    'owner'  => auth()->user()->parent_company_id,
                    'name'   => date('Y-m', strtotime($time))
                ]);
                $monthlyJournal = new MonthlyJournals;
                $monthlyJournal = $monthlyJournal->store($monthlyJournalRequest);
                if ($monthlyJournal['status'] == 200) {
                    $paymentTerm = PaymentTerm::query()
                    ->find($request->payment_term);
                    if ($paymentTerm) {
                        $vendor = User::query()
                        ->with([
                            'billingAddress',
                            'billingAddresses',
                        ])
                        ->whereIsEnable(TRUE)
                        ->whereIn('parent_company_id', [
                            $monthlyJournal['data']->owner->id,
                            $monthlyJournal['data']->owner->parentCompany->id,
                        ])
                        ->whereHas('ownerTypes', function($query) {
                            $query->where('name', 'Supplier');
                        })
                        ->whereId($request->vendor)
                        ->first();
                        if ($vendor) {
                            $vendorAddress = $vendor->billingAddresses->where('id', $request->vendor_address)->first();
                            if ($vendorAddress) {
                                $query                          = new PurchaseOrder;
                                $query->monthly_journal_id      = $monthlyJournal['data']->id;
                                $query->transaction_time        = $time;
                                $query->order_deadline          = date('Y-m-d 00:00:00', strtotime($request->order_deadline));
                                $query->payment_term_id         = $paymentTerm->id;
                                $query->internal_code           = $request->internal_code ?? NULL;
                                $query->vendor_id               = $vendor->id;
                                $query->vendor_address_id       = $vendorAddress->id;
                                $query->note                    = $request->note ?? NULL;
                                $query->status_id               = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                                $query->save();

                                $query->code                    = $query->id;
                                $query->save();

                                DB::commit();
                                $response = [
                                    'status'    => 200,
                                    'message'   => 'Purchase order created in successfully.',
                                    'data'      => $query,
                                    'errors'    => [],
                                ];
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 404,
                                    'message'   => 'Vendor address not found.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 404,
                                'message'   => 'Vendor not found.',
                                'data'      => NULL,
                                'errors'    => [],
                            ];
                        }
                    } else {
                        DB::rollback();
                        $response = [
                            'status'    => 404,
                            'message'   => 'Payment term not found.',
                            'data'      => NULL,
                            'errors'    => [],
                        ];
                    }
                } else {
                    DB::rollback();
                    $response = $monthlyJournal;
                }
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
                'status'    => 500,
                'message'   => 'Purchase order failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'time'              => 'required|string',
            'order_deadline'    => 'required|string',
            'payment_term'      => 'required|exists:payment_terms,id',
            'vendor'            => 'required|exists:users,id',
            'vendor_address'    => 'required|exists:contacts,id',
            'internal_code'     => 'nullable|string',
            'note'              => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $query = PurchaseOrder::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $time = date('Y-m-d 00:00:00', strtotime($request->time));

                    $monthlyJournalRequest = new Request;
                    $monthlyJournalRequest->replace([
                        'owner'  => auth()->user()->parent_company_id,
                        'name'   => date('Y-m', strtotime($time))
                    ]);
                    $monthlyJournal = new MonthlyJournals;
                    $monthlyJournal = $monthlyJournal->store($monthlyJournalRequest);
                    if ($monthlyJournal['status'] == 200) {
                        $paymentTerm = PaymentTerm::query()
                        ->find($request->payment_term);
                        if ($paymentTerm) {
                            $vendor = User::query()
                            ->with([
                                'billingAddress',
                                'billingAddresses',
                            ])
                            ->whereIsEnable(TRUE)
                            ->whereIn('parent_company_id', [
                                $monthlyJournal['data']->owner->id,
                                $monthlyJournal['data']->owner->parentCompany->id,
                            ])
                            ->whereHas('ownerTypes', function($query) {
                                $query->where('name', 'Supplier');
                            })
                            ->whereId($request->vendor)
                            ->first();
                            if ($vendor) {
                                $vendorAddress = $vendor->billingAddresses->where('id', $request->vendor_address)->first();
                                if ($vendorAddress) {
                                    $query->monthly_journal_id      = $monthlyJournal['data']->id;
                                    $query->transaction_time        = $time;
                                    $query->order_deadline          = date('Y-m-d 00:00:00', strtotime($request->order_deadline));
                                    $query->payment_term_id         = $paymentTerm->id;
                                    $query->vendor_id               = $vendor->id;
                                    $query->vendor_address_id       = $vendorAddress->id;
                                    $query->internal_code           = $request->internal_code ?? NULL;
                                    $query->note                    = $request->note ?? NULL;
                                    $query->status_id               = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                                    $query->save();

                                    $query->code                    = $query->id;
                                    $query->save();

                                    DB::commit();
                                    $response = [
                                        'status'    => 200,
                                        'message'   => 'Purchase order updated in successfully.',
                                        'data'      => $query,
                                        'errors'    => [],
                                    ];
                                } else {
                                    DB::rollback();
                                    $response = [
                                        'status'    => 404,
                                        'message'   => 'Vendor address not found.',
                                        'data'      => NULL,
                                        'errors'    => [],
                                    ];
                                }
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 404,
                                    'message'   => 'Vendor not found.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 404,
                                'message'   => 'Payment term not found.',
                                'data'      => NULL,
                                'errors'    => [],
                            ];
                        }
                    } else {
                        DB::rollback();
                        $response = $monthlyJournal;
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    $response = [
                        'status'    => 500,
                        'message'   => $e->getMessage(),
                        'data'      => $query,
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
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Purchase order failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function updateStatus($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|in:' . collect(PurchaseOrder::STATUSES())->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            $query = PurchaseOrder::query()->find($id);
            if ($query) {
                if ($query->monthlyJournal->status->name == 'Draft') {
                    $statuses = PurchaseOrder::STATUSES();
                    if (!is_bool(array_search($request->status, array_column($statuses, 'id')))) {
                        try {
                            $query->status_id   = Status::query()->whereId($request->status)->whereIsEnable(TRUE)->first()->id;
                            $query->save();

                            DB::commit();
                            $response = [
                                'status'    => 200,
                                'message'   => 'Purchase order status updated in successfully.',
                                'data'      => $query,
                                'errors'    => [],
                            ];
                        } catch (\Exception $e) {
                            DB::rollback();
                            $response = [
                                'status'    => 500,
                                'message'   => $e->getMessage(),
                                'data'      => $query,
                                'errors'    => [],
                            ];
                        }
                    } else {
                        DB::rollback();
                        $response = [
                            'status'    => 500,
                            'message'   => 'Purchase order status not found.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    }
                } else {
                    $response = [
                        'status'    => 500,
                        'message'   => 'Monthly journal not writable.',
                        'data'      => NULL,
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
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Purchase order failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = PurchaseOrder::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $monthlyJournalRequest = new Request;
                $monthlyJournalRequest->replace([
                    'owner'  => auth()->user()->parent_company_id,
                    'name'   => date('Y-m', strtotime($query->transaction_time))
                ]);
                $monthlyJournal = new MonthlyJournals;
                $monthlyJournal = $monthlyJournal->show($monthlyJournalRequest);
                if ($monthlyJournal['status'] == 200) {
                    if ($monthlyJournal['data']->status->name == 'Draft') {
                        if ($query->status->name == 'Draft') {
                            $purchaseOrders = PurchaseOrder::query()
                            ->with([
                                'monthlyJournal',
                                'status',
                            ])
                            ->select(['operating_cost_transactions.*'])
                            ->whereRelation('monthlyJournal', 'owner_id', $monthlyJournal['data']->owner->id)
                            ->whereDate('created_at', '>', $query->created_at)
                            ->get();
                            if ($purchaseOrders->count() == 0) {
                                $query->delete();

                                DB::commit();
                                $response = [
                                    'status'    => 200,
                                    'message'   => 'Purchase order deleted in successfully.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 500,
                                    'message'   => 'Purchase order can\'t be deleted.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 500,
                                'message'   => 'Purchase order not writable.',
                                'data'      => NULL,
                                'errors'    => [],
                            ];
                        }
                    } else {
                        DB::rollback();
                        $response = [
                            'status'    => 500,
                            'message'   => 'Monthly journal not writable.',
                            'data'      => NULL,
                            'errors'    => [],
                        ];
                    }
                } else {
                    DB::rollback();
                    $response = $monthlyJournal;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $response = [
                    'status'    => 500,
                    'message'   => $e->getMessage(),
                    'data'      => $query,
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