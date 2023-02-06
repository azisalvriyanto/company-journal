<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\StorageOperationType;

use Illuminate\Http\Response;

use DB;
use Validator;

class StorageOperationTypes extends Controller
{
    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
        ]);

        if ($validator->passes()) {
            $query = StorageOperationType::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->name        = $request->name;
                    $query->is_enable   = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Storage operation type updated in successfully.',
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
                    'message'   => 'Storage operation type not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Storage operation type failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }
}