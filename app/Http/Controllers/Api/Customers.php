<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Customers extends Controller
{
    public function index(Request $request)
    {
        if ($request->owner) {
            $query  = User::query()
            ->with([
                'billingAddress',
                'billingAddresses',
            ])
            ->whereIsEnable(TRUE)
            ->whereParentCompanyId($request->owner)
            ->whereHas('ownerTypes', function($query) {
                $query->where('name', 'Customer');
            });
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