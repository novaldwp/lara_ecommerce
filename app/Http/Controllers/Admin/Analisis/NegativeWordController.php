<?php

namespace App\Http\Controllers\Admin\Analisis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analysis\NegativeWordRequest;
use App\Http\Requests\Import\NegativeWordImportRequest;
use App\Services\NegativeWordService;
use App\Services\RedirectService;
use Exception;
use Illuminate\Http\Request;

class NegativeWordController extends Controller
{
    protected $negativeWordService;
    protected $redirectService;

    public function __construct(NegativeWordService $negativeWordService, RedirectService $redirectService)
    {
        $this->negativeWordService  = $negativeWordService;
        $this->redirectService      = $redirectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title          = "Analisis Kata Negatif | Toko Putra Elektronik";
        $positiveWords  = $this->negativeWordService->getNegativeWords(request());
        $countStatus    = $this->negativeWordService->getCountNegativeWords();

        if (request()->ajax()) {
            return datatables()::of($positiveWords)
            ->addColumn('status', function($data) {
                $condition = ($data->deleted_at == "") ? "active" : "deactive";
                $status = '<span class="badge ' . (($condition == "active") ? "badge-primary":"badge-danger")  . '">' . (($condition == "active") ? getStatus(1) : getstatus(0)) . '</span>';

                return $status;
            })
            ->addColumn('action', function($data){
                $button = "";
                $button .= '<a href="' . route('admin.analyst.negative-words.edit', simple_encrypt($data->id)) . '" class="btn btn-success" >Ubah</a> &nbsp;&nbsp;&nbsp;';

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

        return view('admin.negativeword.index', compact('title', 'countStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Kata Negatif | Toko Putra Elektronik";

        return view('admin.negativeword.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NegativeWordRequest $request)
    {
        try {
            $result = $this->negativeWordService->store($request);

            return $this->redirectService->indexPage("admin.analyst.negative-words.index", "Data Berhasil Ditambahkan!");
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($word_id)
    {
        $title = "Ubah Kata Negatif | Toko Putra Elektronik";

        try {
            $negativeWord = $this->negativeWordService->getNegativeWordById($word_id);

            return view('admin.negativeword.edit', compact('title', 'negativeWord'));
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $word_id)
    {
        try {
            $result = $this->negativeWordService->update($request, $word_id);

            return $this->redirectService->indexPage("admin.analyst.negative-words.index", "Data Berhasil Diperbarui!");
        }
        catch (Exception $e) {
            return $this->redirectService->backPage($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($word_id)
    {
        $result['status'] = "200";

        try {
            $result['data']     = $this->negativeWordService->delete($word_id);
            $result['message']  = "Kata Negatif Berhasil di non-aktifkan!";
            $result['count']    = $this->negativeWordService->getCountNegativeWords();
        }
        catch (Exception $e) {
            $result['status']   = "404";
            $result['message']  = $e->getMessage();
        }

        return response()->json($result, $result['status']);
    }

    public function restore($word_id)
    {
        $result['status'] = "200";

        try {
            $result['data']     = $this->negativeWordService->restore($word_id);
            $result['message']  = "Kata Negatif Berhasil di non-aktifkan!";
            $result['count']    = $this->negativeWordService->getCountNegativeWords();
        }
        catch (Exception $e) {
            $result['status']   = "404";
            $result['message']  = $e->getMessage();
        }

        return response()->json($result, $result['status']);
    }

    public function import(NegativeWordImportRequest $request)
    {
        if ($request->hasFile('file'))
        {
            $result['status'] = "200";

            try {
                $this->negativeWordService->_importFile($request);

                $result['message'] = "Import Kata Negatif Berhasil Dilakukan";
                $result['count']   = $this->negativeWordService->getCountNegativeWords();
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
            return $this->negativeWordService->_exportFile();
        }
        catch (Exception $e) {
            return $this->redirectService->indexPage("admin.analyst.negative-words.index", $e->getMessage());
        }
    }
}
