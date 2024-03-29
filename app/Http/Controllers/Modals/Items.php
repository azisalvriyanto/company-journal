<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemScanningCode;

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
            'detail_group'          => 'required|in:' . collect(Item::DETAIL_GROUPS)->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                          = new Item;
                $query->owner_id                = $request->owner;
                $query->category_id             = $request->category;
                $query->name                    = $request->name;
                $query->code                    = $request->code ?? NULL;
                $query->unit_of_measurement_id  = $request->unit_of_measurement;
                $query->image_url               = $request->image_url ?? NULL;
                $query->detail_group            = $request->detail_group;
                $query->is_enable               = $request->is_enable ?? 0;
                $query->save();

                if ($request->item_scanning_code) {
                    foreach ($request->item_scanning_code as $itemScanningCodeId => $itemScanningCode) {
                        $queryItemScanningCode = ItemScanningCode::query()
                        ->whereName($itemScanningCode['name'])
                        ->first();
                        if ($queryItemScanningCode) {
                            if ($queryItemScanningCode->item_id != $query->id) {
                                DB::rollback();
                                $response = [
                                    'status'    => 500,
                                    'message'   => 'Code ' . $queryItemScanningCode->name . ' doesn\'t not exist.',
                                    'data'      => $query,
                                    'errors'    => [],
                                ];

                                return $response;
                            }
                        } else {
                            $queryItemScanningCode = ItemScanningCode::query()
                            ->find($itemScanningCodeId);
                            if ($queryItemScanningCode == NULL) {
                                $queryItemScanningCode      = new ItemScanningCode;
                            }

                            $queryItemScanningCode->item_id = $query->id;
                            $queryItemScanningCode->name    = $itemScanningCode['name'];
                            $queryItemScanningCode->save();
                        }
                    }
                }

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Item created in successfully.',
                    'data'      => $query,
                    'errors'    => [],
                ];
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status'    => 500,
                    'message'   => $e->getMessage(),
                    'data'      => NULL,
                    'errors'    => [],
                ]);
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Item failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'owner'                 => 'required|exists:users,id',
            'category'              => 'required|exists:categories,id',
            'name'                  => 'required|string',
            'code'                  => 'nullable|string',
            'unit_of_measurement'   => 'required|exists:unit_of_measurements,id',
            'detail_group'          => 'required|in:' . collect(Item::DETAIL_GROUPS)->pluck('id')->implode(','),
        ]);

        if ($validator->passes()) {
            $query = Item::query()->find($id);
            if ($query) {
                try {
                    DB::beginTransaction();

                    $query->owner_id                = $request->owner;
                    $query->category_id             = $request->category;
                    $query->name                    = $request->name;
                    $query->code                    = $request->code ?? NULL;
                    $query->unit_of_measurement_id  = $request->unit_of_measurement;
                    $query->image_url               = $request->image_url ?? NULL;
                    $query->detail_group            = $request->detail_group;
                    $query->is_enable               = $request->is_enable ?? 0;
                    $query->save();

                    $itemScanningCodeIds = $query->itemScanningCodes->pluck('id', 'id')->toArray();
                    if ($request->item_scanning_code) {
                        foreach ($request->item_scanning_code as $itemScanningCodeId => $itemScanningCode) {
                            $queryItemScanningCode = ItemScanningCode::query()
                            ->whereName($itemScanningCode['name'])
                            ->first();
                            if ($queryItemScanningCode) {
                                if ($queryItemScanningCode->item_id != $query->id) {
                                    DB::rollback();
                                    $response = [
                                        'status'    => 500,
                                        'message'   => 'Code ' . $queryItemScanningCode->name . ' doesn\'t not exist.',
                                        'data'      => $query,
                                        'errors'    => [],
                                    ];

                                    return $response;
                                } else {
                                    if (in_array($queryItemScanningCode->id, $itemScanningCodeIds)) {
                                        unset($itemScanningCodeIds[$queryItemScanningCode->id]);
                                    }
                                }
                            } else {
                                $queryItemScanningCode = ItemScanningCode::query()
                                ->find($itemScanningCodeId);
                                if ($queryItemScanningCode == NULL) {
                                    $queryItemScanningCode      = new ItemScanningCode;
                                } else {
                                    if (in_array($queryItemScanningCode->id, $itemScanningCodeIds)) {
                                        unset($itemScanningCodeIds[$queryItemScanningCode->id]);
                                    }
                                }

                                $queryItemScanningCode->item_id = $query->id;
                                $queryItemScanningCode->name    = $itemScanningCode['name'];
                                $queryItemScanningCode->save();
                            }
                        }
                    }

                    if ($itemScanningCodeIds) {
                        ItemScanningCode::query()
                        ->whereItemId($query->id)
                        ->whereIn('id', $itemScanningCodeIds)
                        ->delete();
                    }

                    DB::commit();
                    $response = [
                        'status'    => 200,
                        'message'   => 'Item updated in successfully.',
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
                    'message'   => 'Item not found.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'Item failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = Item::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'Item deleted in successfully.',
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
                'message'   => 'Item not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}