<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\BrandRequest;
use App\Services\BrandService;

class BrandController extends Controller
{
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index()
    {
        $title          = "Daftar Merk | Toko Putra Elektronik";
        $countStatus    = $this->brandService->getCountStatusBrands();
        $brands         = $this->brandService->getAllBrands(request());

        if (request()->ajax()) {
            return datatables()::of($brands)
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('images', function($data) {
                $images = "";
                $images .= '<a href="' . asset("uploads/images/brands/".$data->image) . '" data-lightbox="' . $data->slug . '" alt="' . $data->name . '">';
                $images .= '<img src="' . asset("uploads/images/brands/thumb/".$data->image) . '" data-lightbox="' . $data->slug . '" alt="' . $data->name . '" width="80px" height="40px">';
                $images .= '</a>';
                return $images;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.brands.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

                if($data->deleted_at == "")
                {
                    $button .= '<button class="btn btn-danger" id="deleteButton" data-brand="' . simple_encrypt($data->id) . '">Non-Aktifkan</button>';
                }
                else {
                    $button .= '<button class="btn btn-primary" id="restoreButton" data-brand="' . simple_encrypt($data->id) . '">Aktifkan</button>';
                }

                return $button;
            })
            ->rawColumns(['action', 'images', 'status'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.brand.index', compact('title', 'countStatus'));
    }

    public function create()
    {
        $title = "Tambah Merk | Toko Putra Elektronik";

        return view('admin.brand.create', compact('title'));
    }

    public function store(BrandRequest $request)
    {
        return $this->brandService->create($request);
    }

    public function edit($id)
    {
        $title = "Ubah Merk | Toko Putra Elektronik";
        $brand = $this->brandService->getBrandById($id);

        return view('admin.brand.edit', compact('title', 'brand'));
    }

    public function update(BrandRequest $request, $id)
    {
        return $this->brandService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->brandService->delete($id);
    }

    public function restore($id)
    {
        return $this->brandService->restore($id);
    }
}
