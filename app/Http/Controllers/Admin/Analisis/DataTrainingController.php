<?php

namespace App\Http\Controllers\Admin\Analisis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analysis\DataTrainingRequest;
use App\Http\Requests\Import\DataTrainingImportRequest;
use App\Imports\DataTrainingImport;
use App\Services\DataTrainingService;
use App\Services\RedirectService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DataTrainingController extends Controller
{
    protected $dataTrainingService;
    protected $redirectService;
    protected $path;

    public function __construct(DataTrainingService $dataTrainingService, RedirectService $redirectService)
    {
        $this->dataTrainingService  = $dataTrainingService;
        $this->redirectService      = $redirectService;
    }

    public function index()
    {
        $title          = "Data Latih | Toko Putra Elektronik";
        $dataTrainings  = $this->dataTrainingService->getDataTrainings(request());
        $countStatus    = $this->dataTrainingService->getCountDataTrainings();

        if (request()->ajax()) {
            return datatables()::of($dataTrainings)
            ->addColumn('class', function($data) {
                $condition  = ($data->class == 0) ? "negative" : "positive";
                $class      = '<span class="badge ' . (($condition == "positive") ? "badge-primary":"badge-danger")  . '">' . (($condition == "positive") ? getStatus(5) : getstatus(6)) . '</span>';

                return $class;
            })
            ->addColumn('status', function($data) {
                $condition  = ($data->deleted_at == "") ? "active" : "deactive";
                $status     = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.analyst.data-trainings.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

                if($data->deleted_at == "")
                {
                    $button .= '<button class="btn btn-danger" id="deleteButton" data-training="' . simple_encrypt($data->id) . '">Non-Aktifkan</button>';
                }
                else {
                    $button .= '<button class="btn btn-primary" id="restoreButton" data-training="' . simple_encrypt($data->id) . '">Aktifkan</button>';
                }

                return $button;
            })
            ->rawColumns(['class', 'action', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.datatraining.index', compact('title', 'countStatus'));
    }

    public function create()
    {
        $title = "Tambah Data Latih | Toko Putra Elektronik";

        return view('admin.datatraining.create', compact('title'));
    }

    public function store(DataTrainingRequest $request)
    {
        try {
            $result = $this->dataTrainingService->store($request);

            return $this->redirectService->indexPage("admin.analyst.data-trainings.index", "Data Latih Berhasil Ditambahkan");
        } catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function edit($dataTrainingId)
    {
        $title = "Ubah Data Latih | Toko Putra Elektronik";

        try {
            $dataTraining = $this->dataTrainingService->getDataTrainingById($dataTrainingId);

            return view('admin.datatraining.edit', compact('dataTraining', 'title'));
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function update(DataTrainingRequest $request, $dataTrainingId)
    {
        try {
            $result = $this->dataTrainingService->update($request, $dataTrainingId);

            return $this->redirectService->indexPage("admin.analyst.data-trainings.index", "Data Latih Berhasil Diperbarui");
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function destroy($dataTrainingId)
    {
        $result['status'] = "200";

        try {
            $result['data']     = $this->dataTrainingService->delete($dataTrainingId);
            $result['message']  = "Kata Negatif Berhasil di non-aktifkan!";
            $result['count']    = $this->dataTrainingService->getCountDataTrainings();
        }
        catch (Exception $e) {
            $result['status']   = "404";
            $result['message']  = $e->getMessage();
        }

        return response()->json($result, $result['status']);
    }

    public function restore($dataTrainingId)
    {
        $result['status'] = "200";

        try {
            $result['data']     = $this->dataTrainingService->restore($dataTrainingId);
            $result['message']  = "Kata Negatif Berhasil di non-aktifkan!";
            $result['count']    = $this->dataTrainingService->getCountDataTrainings();
        }
        catch (Exception $e) {
            $result['status']   = "404";
            $result['message']  = $e->getMessage();
        }

        return response()->json($result, $result['status']);
    }

    public function import(DataTrainingImportRequest $request)
    {
        if ($request->hasFile('file'))
        {
            $result['status'] = "200";

            try {
                $this->dataTrainingService->_importFile($request);

                $result['message'] = "Import Data Latih Berhasil Dilakukan";
                $result['count']   = $this->dataTrainingService->getCountDataTrainings();
            }
            catch (Exception $e) {
                $result['status']   = "500";
                $result['message']  = $e->getMessage();
            }

            return response()->json($result, $result['status']);
        }
        else {
            return response()->json("Something has been wrong..");
        }
    }

    public function export()
    {
        try {
            return $this->dataTrainingService->_exportFile();
        }
        catch (Exception $e) {
            return $this->redirectService->indexPage("admin.analyst.data-trainings.index", $e->getMessage());
        }
    }
}
