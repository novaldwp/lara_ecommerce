<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Slider\CreateSliderRequest;
use App\Http\Requests\Slider\UpdateSliderRequest;
use App\Services\RedirectService;
use App\Services\SliderService;
use Exception;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $sliderService;
    protected $redirectService;

    public function __construct(SliderService $sliderService, RedirectService $redirectService)
    {
        $this->sliderService    = $sliderService;
        $this->redirectService  = $redirectService;
    }

    public function index()
    {
        $title          = "Pengaturan Slider | Toko Putra Elektronik";
        $sliders        = $this->sliderService->getSliders(request());
        $countStatus    = $this->sliderService->getCountStatusSliders();

        if (request()->ajax()) {
            return datatables()::of($sliders)
            ->addColumn('images', function($data) {
                $images = "";
                $images .= '<a href="' . asset("uploads/images/sliders/" . $data->image) . '" data-lightbox="' . $data->name . '" alt="' . $data->name . '">';
                $images .= '<img src="' . asset("uploads/images/sliders/thumb/" . $data->image) . '" data-lightbox="' . $data->name . '" alt="' . $data->name . '" width="80px" height="40px">';
                $images .= '</a>';

                return $images;
            })
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.setting.sliders.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

                if($data->deleted_at == "")
                {
                    $button .= '<button class="btn btn-danger" id="deleteButton" data-slider="' . simple_encrypt($data->id) . '">Non-Aktifkan</button>';
                }
                else {
                    $button .= '<button class="btn btn-primary" id="restoreButton" data-slider="' . simple_encrypt($data->id) . '">Aktifkan</button>';
                }

                return $button;
            })
            ->rawColumns(['images', 'action', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.slider.index', compact('title', 'countStatus'));
    }

    public function create()
    {
        $title = "Tambah Slider | Toko Putra Elektronik";

        return view('admin.slider.create', compact('title'));
    }

    public function store(CreateSliderRequest $request)
    {
        try {
            $result = $this->sliderService->store($request);

            return $this->redirectService->indexPage("admin.setting.sliders.index", 'Slider "' . $result->name . '" Berhasil Ditambahkan!');
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function edit($slider_id)
    {
        $title = "Ubah Slider | Toko Putra Elektronik";

        try {
            $slider = $this->sliderService->getSliderById($slider_id);
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }

        return view('admin.slider.edit', compact('title', 'slider'));
    }

    public function update(UpdateSliderRequest $request, $slider_id)
    {
        try {
            $result = $this->sliderService->update($request, $slider_id);

            return $this->redirectService->indexPage("admin.setting.sliders.index", "Slider berhasil diperbarui!");
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function destroy($slider_id)
    {
        try {
            $slider = $this->sliderService->delete($slider_id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Slider "' . $slider->name . '" berhasil di non-aktifkan!',
                'count'     => $this->sliderService->getCountStatusSliders()
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function restore($slider_id)
    {
        try {
            $slider = $this->sliderService->restore($slider_id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Slider "' . $slider->name . '" berhasil di aktifkan!',
                'count'     => $this->sliderService->getCountStatusSliders()
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }
}
