<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\MonthlyJournals;
use App\Models\MonthlyJournal;

use DataTables;

class MonthlyJournalController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = MonthlyJournal::query()
            ->with([
                'status',
            ])
            ->select(['monthly_journals.*'])
            ->whereIn('monthly_journals.owner_id', [
                $owner->id,
            ]);

            return DataTables::eloquent($query)
            ->editColumn('name', function ($query) {
                return $query->name . '<div class="small">' . date('M Y', strtotime($query->name)) . '</div>';
            })
            ->editColumn('status', function ($query) {
                return '<span class="badge ' . $query->status->background_color . ' ' . $query->status->font_color . '">' . $query->status->name . '</span>';
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
                                <a class="dropdown-item datatable-btn-edit" href="javascript:;" data-value="Draft">
                                    <i class="bi-pencil dropdown-item-icon"></i> Draft
                                </a>
                                <a class="dropdown-item datatable-btn-edit" href="javascript:;" data-value="Lock">
                                    <i class="bi-pencil dropdown-item-icon"></i> Lock
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
                    return route('monthly-journals.show', $query->id);
                },
                'data-name' => function($query) {
                    return $query->name;
                },
            ])
            ->rawColumns(['value', 'deadline_type', 'is_enable','actions'])
            ->addIndexColumn()
            ->toJson();
        }

        $data['statuses'] = MonthlyJournal::STATUSES();

        return view('monthly-journals.index', $data);
    }

    public function update(Request $request, $id)
    {
        $query = new MonthlyJournals;
        return response()->json($query->update($request, $id));
    }

    public function destroy(Request $request, $id)
    {
        $query = new MonthlyJournals;
        return response()->json($query->destroy($request, $id));
    }
}