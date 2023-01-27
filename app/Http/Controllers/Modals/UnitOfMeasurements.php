<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\UnitOfMeasurement;

use Illuminate\Http\Response;

use DB;
use Validator;

class UnitOfMeasurements extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'owner' => 'required|exists:users,id',
            'name'  => 'required|string',
            'code'  => 'required|string',
        ]);

        if ($validator->passes()) {
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query = UnitOfMeasurement::query()->create([
                        'owner_id'  => $request->owner,
                        'name'      => $request->name,
                        'code'      => $request->code,
                        'is_enable' => $request->is_enable ?? 0,
                    ]);

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Unit of measurement created in successfully.',
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
                    'message'   => 'Operating cost not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Unit of measurement failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'owner' => 'required|exists:users,id',
            'name'  => 'required|string',
            'code'  => 'required|string',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query = UnitOfMeasurement::query()->find($id);

                $query->owner_id     = $request->owner;
                $query->name         = $request->name;
                $query->code         = $request->code;
                $query->is_enable    = $request->is_enable ?? 0;
                $query->save();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Unit of measurement updated in successfully.',
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
                'message'   => 'Unit of measurement failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = UnitOfMeasurement::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Unit of measurement deleted in successfully.',
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
                'message'   => 'Unit of measurement not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}
