<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OperatingCost;
use Illuminate\Http\Request;

class OperatingCosts extends Controller
{
    public function index(Request $request)
    {
        if ($request->owner) {
            $query  = OperatingCost::query()
            ->whereIsEnable(TRUE)
            ->whereOwnerId($request->owner);
            if ($request->keyword) {
                $query  = $query->where('name', 'like', '%' . $request->keyword . '%');
            }
            $query  = $query->get();

            return response()->json([
                'status'    => 200,
                'message'   => '',
                'data'      => $query,
                'errors'    => []
            ]);
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => 'Owner not found.',
                'data'      => NULL,
                'errors'    => []
            ]);
        }
    }
}