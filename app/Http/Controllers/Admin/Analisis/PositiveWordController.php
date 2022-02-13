<?php

namespace App\Http\Controllers\Admin\Analisis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analysis\PositiveWordRequest;
use App\Http\Requests\Import\PositiveWordImportRequest;
use App\Services\PositiveWordService;
use App\Services\RedirectService;
use Exception;

class PositiveWordController extends Controller
{
    protected $positiveWordService;
    protected $redirectService;

    public function __construct(PositiveWordService $positiveWordService, RedirectService $redirectService)
    {
        $this->positiveWordService  = $positiveWordService;
        $this->redirectService      = $redirectService;
    }

    public function index()
    {
        $title          = "Analisis Kata Positif | Toko Putra Elektronik";
        $positiveWords  = $this->positiveWordService->getPositiveWords(request());
        $countStatus    = $this->positiveWordService->getCountStatusPositiveWords();

        if (request()->ajax()) {
            return datatables()::of($positiveWords)
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.analyst.positive-words.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

                if($data->deleted_at == "")
                {
                    $button .= '<button class="btn btn-danger" id="deleteButton" data-word="' . simple_encrypt($data->id) . '">Non-Aktifkan</button>';
                }
                else {
                    $button .= '<button class="btn btn-primary" id="restoreButton" data-word="' . simple_encrypt($data->id) . '">Aktifkan</button>';
                }

                return $button;
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.positiveword.index', compact('title', 'countStatus'));
    }

    public function create()
    {
        $title = "Tambah Kata Positif | Toko Putra Elektronik";

        return view('admin.positiveword.create', compact('title'));
    }

    public function store(PositiveWordRequest $request)
    {
        try {
            $result = $this->positiveWordService->store($request);

            return $this->redirectService->indexPage("admin.analyst.positive-words.index", 'Data Berhasil Ditambahkan!');
        } catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function edit($word_id)
    {
        $title = "Ubah Kata Positif | Toko Putra Elektronik";

        try {
            $positiveWord = $this->positiveWordService->getPositiveWordById($word_id);

           return view('admin.positiveword.edit', compact('title', 'positiveWord'));
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function update(PositiveWordRequest $request, $word_id)
    {
        try {
            $result = $this->positiveWordService->update($request, $word_id);

            return $this->redirectService->indexPage("admin.analyst.positive-words.index", 'Data Berhasil Diperbarui!');
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    public function destroy($word_id)
    {
        $result['status'] = "200";

        try {
            $result['data']     = $this->positiveWordService->delete($word_id);
            $result['message']  = "Kata Positif Berhasil di non-aktifkan!";
            $result['count']    = $this->positiveWordService->getCountStatusPositiveWords();
        } catch (Exception $e) {
            $result['status']   = "404";
            $result['message']  = $e->getMessage();
        }

        return response()->json($result, $result['status']);
    }

    public function restore($word_id)
    {
        $result['status'] = "200";

        try {
            $result['data']     = $this->positiveWordService->restore($word_id);
            $result['message']  = "Kata Positif Berhasil di non-aktifkan!";
            $result['count']    = $this->positiveWordService->getCountStatusPositiveWords();
        } catch (Exception $e) {
            $result['status']   = "404";
            $result['message']  = $e->getMessage();
        }

        return response()->json($result, $result['status']);
    }

    public function import(PositiveWordImportRequest $request)
    {
        if ($request->hasFile('file'))
        {
            $result['status'] = "200";

            try {
                $this->positiveWordService->_importFile($request);

                $result['message'] = "Import Kata Positif Berhasil Dilakukan";
                $result['count']   = $this->positiveWordService->getCountStatusPositiveWords();
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
            return $this->positiveWordService->_exportFile();
        }
        catch (Exception $e) {
            return $this->redirectService->indexPage("admin.analyst.positive-words.index", $e->getMessage());
        }
    }
}
