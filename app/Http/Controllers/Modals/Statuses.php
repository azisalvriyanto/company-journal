<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Status;

use Illuminate\Http\Response;

use DB;
use Validator;

class Statuses extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string',
            'background_color'  => 'required|string',
            'font_color'        => 'required|string',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                      = new Status;
                $query->name                = $request->name;
                $query->background_color    = $request->background_color;
                $query->font_color          = $request->font_color;
                $query->is_enable           = $request->is_enable ?? 0;
                $query->save();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Status created in successfully.',
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
                'message'   => 'Status failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string',
            'background_color'  => 'required|string',
            'font_color'        => 'required|string',
        ]);

        if ($validator->passes()) {
            $query = Status::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->name                = $request->name;
                    $query->background_color    = $request->background_color;
                    $query->font_color          = $request->font_color;
                    $query->is_enable           = $request->is_enable ?? 0;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Status updated in successfully.',
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
                    'message'   => 'Status not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Status failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = Status::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Status deleted in successfully.',
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
                'message'   => 'Status not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}