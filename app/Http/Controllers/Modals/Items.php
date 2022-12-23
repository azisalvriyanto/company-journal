<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Item;

use Illuminate\Http\Response;

use DB;
use Validator;

class Items extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'owner'                 => 'required|exists:users,id',
            'category'              => 'required|exists:categories,id',
            'name'                  => 'required|string',
            'code'                  => 'nullable|string',
            'unit_of_measurement'   => 'required|exists:unit_of_measurements,id',
            'detail_group'          => 'nullable|string',
        ]);

        if ($validator->passes()) {
            try {
                $query = Item::query()->create([
                    'owner_id'                  => $request->owner,
                    'category_id'               => $request->category,
                    'name'                      => $request->name,
                    'code'                      => $request->code ?? NULL,
                    'unit_of_measurement_id'    => $request->unit_of_measurement,
                    'image_url'                 => $request->image_url ?? NULL,
                    'detail_group'              => $request->detail_group,
                    'is_enable'                 => $request->is_enable ?? 0,
                ]);

                $response = [
                    'status'    => 200,
                    'message'   => 'Item created in successfully.',
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
                'message'   => 'Item failed to create.',
                'data'      => [],
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return response()->json($response);
    }

    public function destroy($request, $id)
    {
        $query = Item::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();
                $query->delete();
                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Item deleted in successfully.',
                    'data'      => NULL
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'   => 500,
                    'message'   => $e->getMessage(),
                    'data'      => NULL
                ]);
            }
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => 'Item not found.',
                'data'      => NULL
            ]);
        }
    }
}