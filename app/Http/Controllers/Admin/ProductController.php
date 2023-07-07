<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Lib\Field;
use App\Lib\Image;
use App\Models\Product;
use App\Traits\ChecksPermission;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ChecksPermission;
    public function index(Request $request) {
        if ($request->ajax()) {
            return datatables(Product::query())
                ->toJson();
        }

        return view('admin.resource.index', [
            'name'             => 'admin.product',
            'permissionPrefix' => 'product',
            'heading'          => [
                'index'  => 'product',
                'create' => 'Create product',
            ],
            'columns'          => ['id', 'name', 'image','status'],
            'statusMap'        => ProductStatus::class,

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
                Field::select('status')->required()->options(ProductStatus::getInstances()),

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
            $validated['image'] = Image::store('image', 'upload/product');
        }
        return response()->report(ProductStatus::create($validated), 'Product create successfully');
    }

    public function show(Product $product) {
        return view('admin.resource.show', [
            'name'             => 'admin.category',
            'heading'          => [
                'index'  => 'Category',
                'create' => 'Create Category',
                'show'   => 'Show Category',
            ],
            'permissionPrefix' => 'category',
            'model'            => $product,
            'columns'          => ['name'],
        ]);
    }

    public function edit(Product $product) {
        return view('admin.resource.edit', [
            'model'            => $product,
            'name'             => 'admin.product',
            'heading'          => [
                'index'  => 'Category',
                'create' => 'Create Category',
                'edit'   => 'Edit Category',
            ],
            'permissionPrefix' => 'category',
            'fields'           => [
                Field::text('name')->required(),
                Field::file('image')->label('Image'),
                Field::select('status')->required()->options(ProductStatus::getInstances()),

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
