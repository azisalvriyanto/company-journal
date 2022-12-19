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
                $query = Category::query()->create([
                    'owner_id'  => $request->owner,
                    'name'      => $request->name,
                    'is_enable' => $request->is_enable ?? 0,
                ]);

                $response = [
                    'status'    => 200,
                    'message'   => 'Category created in successfully.',
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
                'message'   => 'Category failed to create.',
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
        ]);

        if ($validator->passes()) {
            try {
                $query = Category::query()->find($id);

                $query->owner_id     = $request->owner;
                $query->name         = $request->name;
                $query->is_enable    = $request->is_enable ?? 0;
                $query->save();

                $response = [
                    'status'    => 200,
                    'message'   => 'Category updated in successfully.',
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
                'message'   => 'Category failed to update.',
                'data'      => [],
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return response()->json($response);
    }


    public function destroy($request, $id)
    {
        $query = Category::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();
                $query->delete();
                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Category deleted in successfully.',
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
