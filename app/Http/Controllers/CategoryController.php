<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Modals\Categories;
use App\Models\Category;

use DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $owner = auth()->user()->parentCompany;
            $query = Category::query()
            ->select(['categories.*'])
            ->whereIn('categories.owner_id', [
                $owner->id,
                $owner->parentCompany->id
            ]);

            return DataTables::eloquent($query)
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

                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-' . $query->id . '" >
                                <span class="dropdown-header">Options</span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('items.categories.edit', $query->id) . '">
                                    <i class="bi-pencil dropdown-item-icon"></i> Edit
                                </a>
                                <a class="dropdown-item datatable-btn-destroy" href="javascript:;" data-url="' . route('items.categories.show', $query->id) . '">
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
                'data-name' => function($query) {
                    return $query->name;
                },
                'data-is-enable' => function($query) {
                    return $query->is_enable;
                },
            ])
            ->rawColumns(['is_enable', 'actions'])
            ->addIndexColumn()
            ->toJson();
        }

        return view('categories.index');
    }

    public function create()
    {
        return view('categories.create');
    }

    public function edit($id)
    {
        $data['category'] = Category::query()->findOrFail($id);

        return view('categories.edit', $data);
    }

    public function store(Request $request)
    {
        $categories = new Categories;
        return $categories->store($request);
    }

    public function update(Request $request, $id)
    {
        $categories = new Categories;
        return $categories->update($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        $categories = new Categories;
        return $categories->destroy($request, $id);
    }
}