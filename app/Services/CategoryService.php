<?php

namespace App\Services;

use App\Models\Admin\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryService {

    private $redirectService;
    private $categoryRepository;

    public function __construct(RedirectService $redirectService, CategoryRepository $categoryRepository)
    {
        $this->redirectService = $redirectService;
        $this->categoryRepository = $categoryRepository;
    }

    // GET CATEGORY
    public function getAllCategories($request)
    {
        if ($request->filter == 0)
        {
            return $this->categoryRepository->getAllCategories();
        }
        else if ($request->filter == 1)
        {
            return $this->categoryRepository->getAllActiveCategories();
        }
        else {
            return $this->categoryRepository->getAllNonActiveCategories();
        }
    }

    public function getAllParentCategories()
    {
        return $this->categoryRepository->getAllParentCategories();
    }

    public function getCategoryById($id)
    {
        try {
            $category = $this->categoryRepository->getCategoryById($id);

            return $category;
        }
        catch (\Exception $e) {
            return $this->redirectService->indexPage("admin.categories.index", $e->getMessage());
        }
    }

    public function getCategoryByParentId($id)
    {
        $category = $this->categoryRepository->getCategoryByParentId($id);

        return $category;
    }

    public function getCategoryBySlug($slug)
    {
        try {
            $result = $this->categoryRepository->getCategoryBySlug($slug);

            return $result;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCategoryByIdParentId($category_id, $parent_id)
    {
        $result = $this->categoryRepository->getCategoryByIdParentId($category_id, $parent_id);

        return $result;
    }

    public function getCountStatusCategories()
    {
        $all        = $this->categoryRepository->getAllCategories()->count();
        $active     = $this->categoryRepository->getAllActiveCategories()->count();
        $nonactive  = $this->categoryRepository->getAllNonActiveCategories()->count();

        $res = [
            'all'       => $all,
            'active'    => $active,
            'nonactive' => $nonactive
        ];

        return $res;
    }

    public function getCategoriesHasChildHasProduct()
    {
        $categories = $this->categoryRepository->getCategoriesHasChildHasProduct(); // fetch categories who has child and products
        $result     = collect($categories)->filter(function($value, $key) { // filter/remove category who has child > 1
            return count($value->child) > 0;
        });

        return $result;
    }

    public function isParentCategory($id)
    {
        $category = $this->categoryRepository->getCategoryById($id);

        if ($category->parent_id == "") // check if category is parent
        {
            return true;
        }
        else {
            return false;
        }
    }

    public function isChildCategory($id)
    {
        $category = $this->categoryRepository->getCategoryById($id);

        if ($category->parent_id != "")
        {
            return true;
        }
        else {
            return false;
        }
    }

    // CRUD
    public function create($request)
    {
        try {
            $data = [
                'name'      => $request->name,
                'parent_id' => $request->parent_id,
                'slug'      => Str::slug($request->name)
            ];

            $category = $this->categoryRepository->create($data);

            return $this->redirectService->indexPage("admin.categories.index", 'Kategori "' . $category->name . '" berhasil ditambahkan!');
        }
        catch (\Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            // to do => kalo yang di update adalah parent class, maka childnya akan jadi NULL
            $data = [
                'name'      => $request->name,
                'parent_id' => $request->parent_id,
                'slug'      => Str::slug($request->name)
            ];

            $category = $this->categoryRepository->update($id, $data);
            DB::commit();

            return $this->redirectService->indexPage("admin.categories.index", 'Kategori "' . $category->name . '" berhasil diperbarui!');
        }
        catch (\Exception $e) {
            DB::rollback();

            return $this->redirectService->backPage($e);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->categoryRepository->delete($id); // delete kategori
            $category = $this->isParentCategory($id); // cek apakah kategori merupakan parent

            if ($category)
            {
                $categories = $this->categoryRepository->getActiveCategoryByParentId($id); // jika iya, fetch data child
                foreach($categories as $cat)
                {
                    $this->categoryRepository->delete(simple_encrypt($cat->id)); // soft delete data child kategori
                }
            }
            DB::commit();

            return response()->json([
                'status'    => 'success',
                'message'   => 'Kategori berhasil di non-aktifkan!',
                'count'     => $this->getCountStatusCategories()
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $isChild = $this->isChildCategory($id); // cek apakah ini child kategori
            if ($isChild) // jika iya
            {
                $child = $this->categoryRepository->getCategoryById($id); // fetch kategori by id
                $parent = $this->categoryRepository->getCategoryById(simple_encrypt($child->parent_id)); // fetch parent kategori by parent_id dari child

                if ($parent->deleted_at != "") // jika parent idnya di hapus
                {
                    return response()->json([ // thrown error
                        'status'    => 'error',
                        'message'   => 'Parent kategori harus aktif!',
                        'count'     => $this->getCountStatusCategories()
                    ]);
                }
            }

            $this->categoryRepository->restore($id); // restore kategori
            $isParent = $this->isParentCategory($id); // cek apakah kategori merupakan parent

            if ($isParent)
            {
                $categories = $this->categoryRepository->getNonActiveCategoryByParentId($id); // jika iya, fetch data child
                foreach($categories as $cat)
                {
                    $this->categoryRepository->restore(simple_encrypt($cat->id)); // soft delete data child kategori
                }
            }
            DB::commit();

            return response()->json([
                'status'    => 'success',
                'message'   => 'Kategori berhasil di aktifkan!',
                'count'     => $this->getCountStatusCategories()
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
