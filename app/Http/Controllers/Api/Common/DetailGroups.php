<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailGroups extends Controller
{
    public function index(Request $request)
    {
        $query  = collect([
            [
                'id'    => "Product Name",
                'name'  => "Product Name",
            ],
            [
                'id'    => "Production Date",
                'name'  => "Production Date",
            ],
            [
                'id'    => "Expired Date",
                'name'  => "Expired Date",
            ],
        ]);
        if ($request->keyword) {
            $query  = $query->filter(function($item) use($request) {
                return str_contains(strtolower($item['name']), strtolower($request->keyword));
            });
        }
        $query  = $query->toArray();

        return response()->json([
            'status'    => 200,
            'message'   => '',
            'data'      => $query
        ]);
    }
}