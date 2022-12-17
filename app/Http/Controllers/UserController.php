<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
        ->select(['users.*']);

        if (request()->ajax()) {
            return DataTables::eloquent($users)
            ->editColumn('email', function ($query) {
                return $query->email ? $query->email : '<i class="text-muted">Empty</i>';
            })
            ->editColumn('is_enable', function ($query) {
                return $query->is_enable ? '<span class="badge bg-soft-success text-success">Enable</span>' : '<span class="badge bg-soft-danger text-danger">Disable</span>';
            })
            ->rawColumns(['email', 'is_enable'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('users.index');
    }
}
