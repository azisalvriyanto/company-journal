<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\OperationType;

use Illuminate\Http\Response;

use DB;
use Validator;

class OperationTypes extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'group'             => 'required|in:' . collect(OperationType::GROUPS)->pluck('id')->implode(','),   
            'name'              => 'required|string',
            'transaction_code'  => 'required|string',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                      = new OperationType;
                $query->group               = $request->group;
                $query->name                = $request->name;
                $query->transaction_code    = $request->transaction_code;
                $query->is_enable           = $request->is_enable ?? 0;
                $query->save();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Operation type created in successfully.',
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
                'message'   => 'Operation type failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group'             => 'required|in:' . collect(OperationType::GROUPS)->pluck('name')->implode(','),   
            'name'              => 'required|string',
            'transaction_code'  => 'required|string',
        ]);

        if ($validator->passes()) {
            $query = OperationType::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->group               = $request->group;
                    $query->name                = $request->name;
                    $query->transaction_code    = $request->transaction_code;
                    $query->is_enable           = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Operation type updated in successfully.',
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
                    'message'   => 'Operation type not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Operation type failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = OperationType::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Operation type deleted in successfully.',
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
                'message'   => 'Operation type not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}