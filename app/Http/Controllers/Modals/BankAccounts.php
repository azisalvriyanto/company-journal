<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;

use Illuminate\Http\Response;

use DB;
use Validator;

class BankAccounts extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'owner' => 'required|exists:users,id',
            'bank'  => 'required|exists:banks,id',
            'name'  => 'required|string',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                          = new BankAccount;
                $query->owner_id                = $request->owner;
                $query->bank_id                 = $request->bank;
                $query->name                    = $request->name;
                $query->account_number          = $request->account_number;
                $query->is_enable               = $request->is_enable ?? 0;
                $query->save();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Bank account created in successfully.',
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
                'message'   => 'Bank account failed to create.',
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
            'bank'  => 'required|exists:banks,id',
            'name'  => 'required|string',
        ]);

        if ($validator->passes()) {
            $query = BankAccount::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->owner_id                = $request->owner;
                    $query->bank_id                 = $request->bank;
                    $query->name                    = $request->name;
                    $query->is_enable               = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Bank account updated in successfully.',
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
                'message'   => 'Bank account failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = BankAccount::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Bank account deleted in successfully.',
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
                'message'   => 'Bank account not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}