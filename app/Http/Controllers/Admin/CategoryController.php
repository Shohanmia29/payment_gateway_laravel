<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryStatus;
use App\Http\Controllers\Controller;
use App\Lib\Field;
use App\Lib\Image;
use App\Models\Category;
use App\Traits\ChecksPermission;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ChecksPermission;
    public function index(Request $request) {
        if ($request->ajax()) {
            return datatables(Category::query())
                ->toJson();
        }

        return view('admin.resource.index', [
            'name'             => 'admin.category',
            'permissionPrefix' => 'category',
            'heading'          => [
                'index'  => 'category',
                'create' => 'Create Category',
            ],
            'columns'          => ['id', 'name', 'image','status'],
            'statusMap'        => CategoryStatus::class,

        ]);
    }

    public function create() {
        return view('admin.resource.create', [
            'name'             => 'admin.category',
            'heading'          => [
                'index'  => 'category',
                'create' => 'Create Category',
            ],
            'permissionPrefix' => 'cateogry',
            'fields'           => [
                // Field::select('building_id')->label('Building')->required()->options(Building::select('id', 'name')->get()),
                Field::text('name')->required()->label('Category Name'),
                Field::file('image')->label('Image')->required(),
                Field::select('status')->required()->options(CategoryStatus::getInstances()),

            ],
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name'   => 'required',
            'image'   => 'nullable|image',
            'status' => 'required',

        ]);
        if (!empty($validated['image'])) {
            $validated['image'] = Image::store('image', 'upload/category');
        }
        return response()->report(Category::create($validated), 'Category create successfully');
    }

    public function show(Category $category) {
        return view('admin.resource.show', [
            'name'             => 'admin.category',
            'heading'          => [
                'index'  => 'Category',
                'create' => 'Create Category',
                'show'   => 'Show Category',
            ],
            'permissionPrefix' => 'category',
            'model'            => $category,
            'columns'          => ['name'],
        ]);
    }

    public function edit(Category $category) {
        return view('admin.resource.edit', [
            'model'            => $category,
            'name'             => 'admin.category',
            'heading'          => [
                'index'  => 'Category',
                'create' => 'Create Category',
                'edit'   => 'Edit Category',
            ],
            'permissionPrefix' => 'category',
            'fields'           => [
                Field::text('name')->required(),
                Field::file('image')->label('Image'),
                Field::select('status')->required()->options(CategoryStatus::getInstances()),

            ],
        ]);
    }

    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'image'  => 'nullable|image',
            'name'   => 'required',
            'status' => 'required',
        ]);
        if (!empty($validated['image'])) {
            $validated['image'] = Image::store('image', 'upload/category');
        }
        return response()->report($category->update($validated), 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        return response()->report($category->delete(), 'Category deleted successfully');
    }
}
