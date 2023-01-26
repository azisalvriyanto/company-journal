<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $company = auth()->user()->parentCompany;
            $query = User::query()
            ->select(['users.*'])
            ->whereGroup('User')
            ->whereIn('parent_company_id', [
                $company->id,
                $company->parent_company_id
            ]);

            return DataTables::eloquent($query)
            ->editColumn('email', function ($query) {
                return $query->email ? $query->email : '<i class="text-muted">Empty</i>';
            })
            ->editColumn('is_enable', function ($query) {
                return $query->is_enable ? '<span class="badge bg-soft-success text-success">Enable</span>' : '<span class="badge bg-soft-danger text-danger">Disable</span>';
            })
            ->addColumn('actions', function ($query) {
                return '
                    <div class="btn-group" role="group">
                        <span class="btn btn-white btn-sm">
                            More
                        </span>

                        <div class="btn-group">
                            <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="datatableMore-' . $query->id . '" data-bs-toggle="dropdown" aria-expanded="false"></button>

                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-' . $query->id . '">
                                <span class="dropdown-header">Options</span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('users.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
                                </a>
                                <a class="dropdown-item datatable-btn-destroy" href="javascript:;" data-url="' . route('users.show', $query->id) . '">
                                    <i class="bi-trash dropdown-item-icon"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->setRowAttr([
                'data-id' => function($query) {
                    return $query->id;
                },
                'data-group' => function($query) {
                    return $query->group;
                },
                'data-name' => function($query) {
                    return $query->name;
                },
                'data-email' => function($query) {
                    return $query->email;
                },
            ])
            ->rawColumns(['email', 'is_enable', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('users.index');
    }

    public function destroy(Request $request, $id)
    {
        $query = User::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();
                $query->delete();
                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'User deleted in successfully.',
                    'data'      => NULL
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status'   => 500,
                    'message'   => $e->getMessage(),
                    'data'      => NULL
                ]);
            }
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => 'User not found.',
                'data'      => NULL
            ]);
        }
    }
}
