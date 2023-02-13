<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\PaymentTerm;
use App\Models\Status;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class Billings extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'time'              => 'required|string',
            'due_time'          => 'required|string',
            'payment_term'      => 'required|exists:payment_terms,id',
            'supplier'          => 'required|exists:users,id',
            'supplier_address'  => 'required|exists:contacts,id',
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
                    ->whereId($request->payment_term)
                    ->first();
                    if ($paymentTerm) {
                        if ($paymentTerm->value && $paymentTerm->deadline_type) {
                            $dueTime = date('Y-m-d 00:00:00', strtotime($time . ' + ' . $paymentTerm->value . ' ' . $paymentTerm->deadline_type));
                        } else {
                            $dueTime = date('Y-m-d 00:00:00', strtotime($request->due_time));
                            if ($time <= $dueTime) {
                                $dueTime = $time;
                            }
                        }

                        $supplier = User::query()
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
                        ->whereId($request->supplier)
                        ->first();
                        if ($supplier) {
                            $supplierAddress = $supplier->billingAddresses->where('id', $request->supplier_address)->first();
                            if ($supplierAddress) {
                                $query                          = new Billing;
                                $query->monthly_journal_id      = $monthlyJournal['data']->id;
                                $query->transaction_time        = $time;
                                $query->transaction_due_time    = $dueTime;
                                $query->payment_term_id         = $paymentTerm->id;
                                $query->internal_code           = $request->internal_code ?? NULL;
                                $query->supplier_id             = $supplier->id;
                                $query->supplier_address_id     = $supplierAddress->id;
                                $query->internal_code           = $request->internal_code ?? NULL;
                                $query->note                    = $request->note ?? NULL;
                                $query->status_id               = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                                $query->save();

                                $query->code                    = $query->id;
                                $query->save();

                                DB::commit();
                                $response = [
                                    'status'    => 200,
                                    'message'   => 'Billing created in successfully.',
                                    'data'      => $query,
                                    'errors'    => [],
                                ];
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 404,
                                    'message'   => 'Supplier address not found.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 404,
                                'message'   => 'Supplier not found.',
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
                'message'   => 'Billing failed to create.',
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
            'due_time'          => 'required|string',
            'payment_term'      => 'required|exists:payment_terms,id',
            'supplier'          => 'required|exists:users,id',
            'supplier_address'  => 'required|exists:contacts,id',
            'internal_code'     => 'nullable|string',
            'note'              => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $query = Billing::query()->find($id);
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
                        ->whereId($request->payment_term)
                        ->first();
                        if ($paymentTerm) {
                            if ($paymentTerm->value && $paymentTerm->deadline_type) {
                                $dueTime = date('Y-m-d 00:00:00', strtotime($time . ' + ' . $paymentTerm->value . ' ' . $paymentTerm->deadline_type));
                            } else {
                                $dueTime = date('Y-m-d 00:00:00', strtotime($request->due_time));
                                if ($time <= $dueTime) {
                                    $dueTime = $time;
                                }
                            }

                            $supplier = User::query()
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
                            ->whereId($request->supplier)
                            ->first();
                            if ($supplier) {
                                $supplierAddress = $supplier->billingAddresses->where('id', $request->supplier_address)->first();
                                if ($supplierAddress) {
                                    $query->monthly_journal_id      = $monthlyJournal['data']->id;
                                    $query->transaction_time        = $time;
                                    $query->transaction_due_time    = $dueTime;
                                    $query->payment_term_id         = $paymentTerm->id;
                                    $query->supplier_id             = $supplier->id;
                                    $query->supplier_address_id     = $supplierAddress->id;
                                    $query->internal_code           = $request->internal_code ?? NULL;
                                    $query->note                    = $request->note ?? NULL;
                                    $query->status_id               = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                                    $query->save();

                                    $query->code                    = $query->id;
                                    $query->save();

                                    DB::commit();
                                    $response = [
                                        'status'    => 200,
                                        'message'   => 'Billing updated in successfully.',
                                        'data'      => $query,
                                        'errors'    => [],
                                    ];
                                } else {
                                    DB::rollback();
                                    $response = [
                                        'status'    => 404,
                                        'message'   => 'Supplier address not found.',
                                        'data'      => NULL,
                                        'errors'    => [],
                                    ];
                                }
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 404,
                                    'message'   => 'Supplier not found.',
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
                    'message'   => 'Billing not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Billing failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function updateStatus($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|in:' . collect(Billing::STATUSES())->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            $query = Billing::query()->find($id);
            if ($query) {
                if ($query->monthlyJournal->status->name == 'Draft') {
                    $statuses = Billing::STATUSES();
                    if (!is_bool(array_search($request->status, array_column($statuses, 'id')))) {
                        try {
                            $query->status_id           = Status::query()->whereId($request->status)->whereIsEnable(TRUE)->first()->id;
                            $query->save();

                            DB::commit();
                            $response = [
                                'status'    => 200,
                                'message'   => 'Billing status updated in successfully.',
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
                            'message'   => 'Billing status not found.',
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
                    'message'   => 'Billing not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Billing failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = Billing::query()->find($id);
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
                            $operatingCostTransactions = Billing::query()
                            ->with([
                                'monthlyJournal',
                                'status',
                            ])
                            ->select(['operating_cost_transactions.*'])
                            ->whereRelation('monthlyJournal', 'owner_id', $monthlyJournal['data']->owner->id)
                            ->whereDate('created_at', '>', $query->created_at)
                            ->get();
                            if ($operatingCostTransactions->count() == 0) {
                                $query->delete();

                                DB::commit();
                                $response = [
                                    'status'    => 200,
                                    'message'   => 'Billing deleted in successfully.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            } else {
                                DB::rollback();
                                $response = [
                                    'status'    => 500,
                                    'message'   => 'Billing can\'t be deleted.',
                                    'data'      => NULL,
                                    'errors'    => [],
                                ];
                            }
                        } else {
                            DB::rollback();
                            $response = [
                                'status'    => 500,
                                'message'   => 'Billing not writable.',
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
                'message'   => 'Billing not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}