<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Product\CategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $title          = "Daftar Kategori | Toko Putra Elektronik";
        $countStatus    = $this->categoryService->getCountStatusCategories();
        $categories     = $this->categoryService->getAllCategories(request());

        if (request()->ajax()) {
            return datatables()::of($categories)
            ->addColumn('parent', function($data) {
                $parent = ($data->parent == "") ? " - " : $data->parent->name;

                return $parent;
            })
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.categories.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

                if($data->deleted_at == "")
                {
                    $button .= '<button class="btn btn-danger" id="deleteButton" data-category="' . simple_encrypt($data->id) . '">Non-Aktifkan</button>';
                }
                else {
                    $button .= '<button class="btn btn-primary" id="restoreButton" data-category="' . simple_encrypt($data->id) . '">Aktifkan</button>';
                }

                return $button;
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.category.index', compact('title', 'countStatus'));
    }

    public function create()
    {
        $title      = "Tambah Kategori | Toko Putra Elektronik";
        $categories = $this->categoryService->getAllParentCategories();

        return view('admin.category.create', compact('title', 'categories'));
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryService->create($request);
    }

    public function edit($id)
    {
        $title      = "Ubah Kategori | Toko Putra Elektronik";
        $category   = $this->categoryService->getCategoryById($id);
        $categories = $this->categoryService->getAllParentCategories();

        return view('admin.category.edit', compact('title', 'category', 'categories'));
    }

    public function update(CategoryRequest $request, $id)
    {
        return $this->categoryService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->categoryService->delete($id);
    }

    public function restore($id)
    {
        return $this->categoryService->restore($id);
    }
}
