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
                $monthlyJournals = new MonthlyJournals;
                $monthlyJournals = $monthlyJournals->store($monthlyJournalRequest);
                if ($monthlyJournals['status'] == 200) {
                    $query                      = new OperatingCostTransaction;
                    $query->monthly_journal_id  = $monthlyJournals['data']->id;
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
                    $response = $monthlyJournals;
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
            'owner'                 => 'required|exists:users,id',
            'name'                  => 'required|string',
            'default_cost'          => 'nullable|string',
            'unit_of_measurement'   => 'required|exists:unit_of_measurements,id',
        ]);

        if ($validator->passes()) {
            $query = OperatingCostTransaction::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->owner_id                = $request->owner;
                    $query->name                    = $request->name;
                    $query->default_cost            = number_format((double) filter_var($request->default_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                    $query->unit_of_measurement_id  = $request->unit_of_measurement;
                    $query->is_enable               = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Operating cost transaction updated in successfully.',
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

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Operating cost transaction deleted in successfully.',
                    'data'      => NULL,
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