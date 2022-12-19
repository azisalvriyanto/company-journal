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
            try {
                $query = UnitOfMeasurement::query()->create([
                    'owner_id'  => $request->owner,
                    'name'      => $request->name,
                    'code'      => $request->code,
                    'is_enable' => $request->is_enable ?? 0,
                ]);

                $response = [
                    'status'    => 200,
                    'message'   => 'Unit of measurement created in successfully.',
                    'data'      => $query,
                    'errors'    => [],
                ];
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'   => 500,
                    'message'   => $e->getMessage(),
                    'data'      => [],
                    'errors'    => [],
                ]);
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Unit of measurement failed to create.',
                'data'      => [],
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return response()->json($response);
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
                $query = UnitOfMeasurement::query()->find($id);

                $query->owner_id     = $request->owner;
                $query->name         = $request->name;
                $query->code         = $request->code;
                $query->is_enable    = $request->is_enable ?? 0;
                $query->save();

                $response = [
                    'status'    => 200,
                    'message'   => 'Unit of measurement updated in successfully.',
                    'data'      => $query,
                    'errors'    => [],
                ];
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'   => 500,
                    'message'   => $e->getMessage(),
                    'data'      => [],
                    'errors'    => [],
                ]);
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Unit of measurement failed to update.',
                'data'      => [],
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return response()->json($response);
    }

    public function destroy($request, $id)
    {
        $query = UnitOfMeasurement::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();
                $query->delete();
                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Unit of measurement deleted in successfully.',
                    'data'      => [],
                    'errors'    => [],
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'   => 500,
                    'message'   => $e->getMessage(),
                    'data'      => [],
                    'errors'    => [],
                ]);
            }
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => 'Item category not found.',
                'data'      => [],
                'errors'    => [],
            ]);
        }
    }
}
