<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Product\CategoryRequest;
use Illuminate\Support\Str;
use DB;
use Alert;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent'])->orderByDesc('id')->paginate(5);

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::with(['child'])->whereNull('parent_id')->orderBy('id')->get();

        return view('admin.category.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $params['name']         = $request->name;
        $params['slug']         = Str::slug($request->name);
        $params['parent_id']    = $request->parent_id;

        $category = \DB::transaction(
            function() use ($params) {
                $category = Category::create($params);

                return $category;
            }
        );
        Alert::success('Success', 'Created New Category');

        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category   = Category::findOrFail($id);
        $categories = Category::with(['child'])->whereNull('parent_id')->orderBy('id')->get();

        return view('admin.category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $params['name']         = $request->name;
        $params['slug']         = Str::slug($request->name);
        $params['parent_id']    = $request->parent_id;
        $category               = Category::findOrFail($id);

        $update = DB::transaction(
            function() use ($params, $category)
            {
                $category->update($params);

                return $category;
            }
        );

        Alert::success('Success', 'Updating Category');

        return redirect()->route('categories.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();
        Alert::success('Success', 'Deleting Category');

        return redirect()->back();
    }
}
