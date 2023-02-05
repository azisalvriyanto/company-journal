<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Companies extends Controller
{
    public function index(Request $request)
    {
        $query  = User::query()
        ->whereGroup('Company')
        ->whereIsEnable(TRUE);
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