<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\OperatingCostTransaction;
use App\Models\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Validator;

class OperatingCostTransactions extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'time'          => 'required|string',
            'internal_code' => 'nullable|string',
            'note'          => 'nullable|string',
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
                    $query                      = new OperatingCostTransaction;
                    $query->monthly_journal_id  = $monthlyJournal['data']->id;
                    $query->transaction_time    = $time;
                    $query->internal_code       = $request->internal_code ?? NULL;
                    $query->note                = $request->note ?? NULL;
                    $query->status_id           = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                    $query->save();

                    $query->code                = $query->id;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Operating cost transaction created in successfully.',
                        'data'      => $query,
                        'errors'    => [],
                    ];
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
                'message'   => 'Operating cost transaction failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'time'          => 'required|string',
            'internal_code' => 'nullable|string',
            'note'          => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $query = OperatingCostTransaction::query()->find($id);
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
                        $query->monthly_journal_id  = $monthlyJournal['data']->id;
                        $query->transaction_time    = $time;
                        $query->code                = $query->id;
                        $query->internal_code       = $request->internal_code ?? NULL;
                        $query->note                = $request->note ?? NULL;
                        $query->status_id           = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                        $query->save();

                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Operating cost transaction updated in successfully.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
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
                    'message'   => 'Operating cost transaction not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Operating cost transaction failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = OperatingCostTransaction::query()->find($id);
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
                            $query->delete();

                            DB::commit();
                            $response = [
                                'status'    => 200,
                                'message'   => 'Operating cost transaction deleted in successfully.',
                                'data'      => NULL,
                                'errors'    => [],
                            ];
                        } else {
                            $response = [
                                'status'    => 500,
                                'message'   => 'Operating cost transactiol not writable.',
                                'data'      => NULL,
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
                'message'   => 'Operating cost transaction not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}