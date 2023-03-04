<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\PaymentTerm;
use App\Models\Status;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class SalesOrders extends Controller
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
                            $query->where('name', 'Customer');
                        })
                        ->whereId($request->vendor)
                        ->first();
                        if ($vendor) {
                            $vendorAddress = $vendor->billingAddresses->where('id', $request->vendor_address)->first();
                            if ($vendorAddress) {
                                $query                      = new SalesOrder;
                                $query->monthly_journal_id  = $monthlyJournal['data']->id;
                                $query->transaction_time    = $time;
                                $query->order_deadline      = date('Y-m-d 00:00:00', strtotime($request->order_deadline));
                                $query->payment_term_id     = $paymentTerm->id;
                                $query->internal_code       = $request->internal_code ?? NULL;
                                $query->vendor_id           = $vendor->id;
                                $query->vendor_address_id   = $vendorAddress->id;
                                $query->note                = $request->note ?? NULL;
                                $query->status_id           = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                                $query->save();

                                $query->code                = $query->id;
                                $query->save();

                                DB::commit();
                                $response = [
                                    'status'    => 200,
                                    'message'   => 'Sales order created in successfully.',
                                    'data'      => $query,
                                    'errors'    => [],
                                ];
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 404,
                                    'message'   => 'Customer address not found.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 404,
                                'message'   => 'Customer not found.',
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
                'message'   => 'Sales order failed to create.',
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
            $query = SalesOrder::query()->find($id);
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
                                $query->where('name', 'Customer');
                            })
                            ->whereId($request->vendor)
                            ->first();
                            if ($vendor) {
                                $vendorAddress = $vendor->billingAddresses->where('id', $request->vendor_address)->first();
                                if ($vendorAddress) {
                                    $query->monthly_journal_id  = $monthlyJournal['data']->id;
                                    $query->transaction_time    = $time;
                                    $query->order_deadline      = date('Y-m-d 00:00:00', strtotime($request->order_deadline));
                                    $query->payment_term_id     = $paymentTerm->id;
                                    $query->vendor_id           = $vendor->id;
                                    $query->vendor_address_id   = $vendorAddress->id;
                                    $query->internal_code       = $request->internal_code ?? NULL;
                                    $query->note                = $request->note ?? NULL;
                                    $query->status_id           = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                                    $query->save();

                                    $query->code                = $query->id;
                                    $query->save();

                                    DB::commit();
                                    $response = [
                                        'status'    => 200,
                                        'message'   => 'Sales order updated in successfully.',
                                        'data'      => $query,
                                        'errors'    => [],
                                    ];
                                } else {
                                    DB::rollback();
                                    $response = [
                                        'status'    => 404,
                                        'message'   => 'Customer address not found.',
                                        'data'      => NULL,
                                        'errors'    => [],
                                    ];
                                }
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 404,
                                    'message'   => 'Customer not found.',
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
                    'message'   => 'Sales order not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Sales order failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function updateStatus($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|in:' . collect(SalesOrder::STATUSES())->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            $query = SalesOrder::query()->find($id);
            if ($query) {
                if ($query->monthlyJournal->status->name == 'Draft') {
                    $statuses = SalesOrder::STATUSES();
                    if (!is_bool(array_search($request->status, array_column($statuses, 'id')))) {
                        try {
                            $query->status_id   = Status::query()->whereId($request->status)->whereIsEnable(TRUE)->first()->id;
                            $query->save();

                            DB::commit();
                            $response = [
                                'status'    => 200,
                                'message'   => 'Sales order status updated in successfully.',
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
                            'message'   => 'Sales order status not found.',
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
                    'message'   => 'Sales order not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Sales order failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = SalesOrder::query()->find($id);
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
                            $salesOrders = SalesOrder::query()
                            ->with([
                                'monthlyJournal',
                                'status',
                            ])
                            ->select(['sales_orders.*'])
                            ->whereRelation('monthlyJournal', 'owner_id', $monthlyJournal['data']->owner->id)
                            ->whereDate('created_at', '>', $query->created_at)
                            ->get();
                            if ($salesOrders->count() == 0) {
                                $query->delete();

                                DB::commit();
                                $response = [
                                    'status'    => 200,
                                    'message'   => 'Sales order deleted in successfully.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 500,
                                    'message'   => 'Sales order can\'t be deleted.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 500,
                                'message'   => 'Sales order not writable.',
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
                'message'   => 'Sales order not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}