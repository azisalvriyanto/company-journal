<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\MonthlyJournal;
use App\Models\Status;

use Illuminate\Http\Response;

use DB;
use Validator;

class MonthlyJournals extends Controller
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

                $query = MonthlyJournal::query()
                ->whereOwnerId($request->owner)
                ->whereName($request->name)
                ->first();
                if ($query) {
                    if ($query->status->name == 'Draft') {
                        $response = [
                            'status'    => 200,
                            'message'   => 'Monthly journal doesn\'t exist.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    } else {
                        $response = [
                            'status'    => 500,
                            'message'   => 'Monthly journal has been locked.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    }
                } else {
                    $query              = new MonthlyJournal;
                    $query->owner_id    = $request->owner;
                    $query->name        = date('Y-m', strtotime($request->name));
                    $query->status_id   = Status::query()->whereName('Draft')->whereIsEnable(TRUE)->first()->id;
                    $query->save();

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Monthly journal created in successfully.',
                        'data'      => $query,
                        'errors'    => [],
                    ];
                }

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
                'message'   => 'Monthly journal failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function show($id)
    {
        $query = MonthlyJournal::query()->find($id);
        if ($query) {
            $response = [
                'status'    => 200,
                'message'   => NULL,
                'data'      => $query,
                'errors'    => [],
            ];
        } else {
            $response = [
                'status'    => 404,
                'message'   => 'Monthly journal not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }

    public function update($id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|exists:statuses,id',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query = MonthlyJournal::query()
                ->find($id);
                if ($query) {
                    if ($query->status->name == 'Draft') {
                        $query->status_id   = $request->status;
                        $query->save();
    
                        DB::commit();
                        $response = [
                            'status'    => 200,
                            'message'   => 'Monthly journal updated in successfully.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    } else if ($query->status->name == 'Lock') {
                        $response = [
                            'status'    => 500,
                            'message'   => 'Monthly journal has been locked.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    } else {
                        $response = [
                            'status'    => 404,
                            'message'   => 'Monthly journal doesn\'t have status.',
                            'data'      => $query,
                            'errors'    => [],
                        ];
                    }
                } else {
                    $response = [
                        'status'    => 404,
                        'message'   => 'Monthly journal not found.',
                        'data'      => NULL,
                        'errors'    => [],
                    ];
                }

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
                'message'   => 'Monthly journal failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = MonthlyJournal::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Monthly journal deleted in successfully.',
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
                'message'   => 'Monthly journal not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}