<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Category;

use Illuminate\Http\Response;

use DB;
use Validator;

class Categories extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'owner' => 'required|exists:users,id',
            'name'  => 'required|string',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query = Category::query()->create([
                    'owner_id'  => $request->owner,
                    'name'      => $request->name,
                    'is_enable' => $request->is_enable ?? 0,
                ]);

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Category created in successfully.',
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
                'message'   => 'Category failed to create.',
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
        ]);

        if ($validator->passes()) {
            $query = Category::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->owner_id     = $request->owner;
                    $query->name         = $request->name;
                    $query->is_enable    = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Category updated in successfully.',
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
                    'message'   => 'Category not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Category failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = Category::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Category deleted in successfully.',
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
                'message'   => 'Category not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}
