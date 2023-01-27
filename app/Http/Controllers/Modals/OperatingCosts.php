<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\OperatingCost;

use Illuminate\Http\Response;

use DB;
use Validator;

class OperatingCosts extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'owner'                 => 'required|exists:users,id',
            'name'                  => 'required|string',
            'default_cost'          => 'nullable|string',
            'unit_of_measurement'   => 'required|exists:unit_of_measurements,id',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                          = new OperatingCost;
                $query->owner_id                = $request->owner;
                $query->name                    = $request->name;
                $query->default_cost            = number_format((double) filter_var($request->default_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0, 10, '.', '');
                $query->unit_of_measurement_id  = $request->unit_of_measurement;
                $query->is_enable               = $request->is_enable ?? 0;
                $query->save();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Operating cost created in successfully.',
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
                'message'   => 'Operating cost failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return response()->json($response);
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
            $query = OperatingCost::query()->find($id);
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
                        'message'   => 'Operating cost updated in successfully.',
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
                'message'   => 'Operating cost failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return response()->json($response);
    }

    public function destroy($request, $id)
    {
        $query = OperatingCost::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Operating cost deleted in successfully.',
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
                'message'   => 'Operating cost not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return response()->json($response);
    }
}