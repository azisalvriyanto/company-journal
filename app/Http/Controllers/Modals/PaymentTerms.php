<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;

use Illuminate\Http\Response;

use DB;
use Validator;

class PaymentTerms extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'owner'         => 'required|exists:users,id',
            'name'          => 'required|string',
            'value'         => 'nullable|integer',
            'deadline_type' => 'nullable|in:' . collect(PaymentTerm::DEADLINE_TYPES)->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                  = new PaymentTerm;
                $query->owner_id        = $request->owner;
                $query->name            = $request->name;
                $query->value           = is_numeric($request->value) ? $request->value : NULL;
                $query->deadline_type   = $request->deadline_type ?? NULL;
                $query->is_enable       = $request->is_enable ?? 0;
                $query->save();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Payment term created in successfully.',
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
                'status'    => 500,
                'message'   => 'Payment term failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'owner'         => 'required|exists:users,id',
            'name'          => 'required|string',
            'value'         => 'nullable|integer',
            'deadline_type' => 'nullable|in:' . collect(PaymentTerm::DEADLINE_TYPES)->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            $query = PaymentTerm::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->owner_id        = $request->owner;
                    $query->name            = $request->name;
                    $query->value           = is_numeric($request->value) ? $request->value : NULL;
                    $query->deadline_type   = $request->deadline_type ?? NULL;
                    $query->is_enable       = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Payment term updated in successfully.',
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
                    'message'   => 'Payment term not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Payment term failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = PaymentTerm::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Payment term deleted in successfully.',
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
                'message'   => 'Payment term not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}