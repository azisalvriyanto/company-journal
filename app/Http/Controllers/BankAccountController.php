<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\BankAccounts;
use App\Models\Bank;
use App\Models\BankAccount;

use DataTables;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = BankAccount::query()
            ->with([
                'bank',
            ])
            ->select(['bank_accounts.*'])
            ->whereIn('bank_accounts.owner_id', [
                $owner->id,
                $owner->parent_company_id
            ]);

            return DataTables::eloquent($query)
            ->addColumn('bank_name', function ($query) {
                return '<div>' . $query->bank->name . '</div>' . ($query->bank->short_name ? '<div class="small">' . $query->bank->short_name . '</div>' : '');
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
                                <a class="dropdown-item" href="' . route('payments.bank-accounts.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
                                </a>
                                <a class="dropdown-item datatable-btn-destroy" href="javascript:;">
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
                'data-url' => function($query) {
                    return route('payments.bank-accounts.show', $query->id);
                },
                'data-bank-name' => function($query) {
                    return $query->bank->name;
                },
                'data-name' => function($query) {
                    return $query->name;
                },
                'data-account-number' => function($query) {
                    return $query->account_number;
                },
            ])
            ->rawColumns(['bank_name', 'is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('bank-accounts.index');
    }

    public function create()
    {
        $data['banks'] = Bank::query()->orderBy('name')->get()->all();
        return view('bank-accounts.create', $data);
    }

    public function store(Request $request)
    {
        $query = new BankAccounts;
        return response()->json($query->store($request));
    }

    public function edit($id)
    {
        $data['query'] = BankAccount::query()->findOrFail($id);
        $data['banks'] = Bank::query()->orderBy('name')->get()->all();

        return view('bank-accounts.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new BankAccounts;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new BankAccounts;
        return response()->json($query->destroy($request, $id));
    }
}