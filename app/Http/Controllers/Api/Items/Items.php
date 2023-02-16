<?php

namespace App\Http\Controllers\Api\Items;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class Items extends Controller
{
    public function index(Request $request)
    {
        $query  = Item::query()
        ->with('category', 'unitOfMeasurement')
        ->whereOwnerId($request->owner);
        if ($request->keyword) {
            $query  = $query->where('name', 'like', '%' . $request->keyword . '%');
        }
        $query  = $query->get();

        return response()->json([
            'status'    => 200,
            'message'   => '',
            'data'      => $query
        ]);
    }
}