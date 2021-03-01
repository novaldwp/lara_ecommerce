<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\WarrantyRequest;
use App\Models\Admin\Warranty;
use RealRashid\SweetAlert\Facades\Alert;

class WarrantyController extends Controller
{
    public function index()
    {
        $warranties = Warranty::orderByDesc('id')->paginate(5);

        return view('admin.warranty.index', compact('warranties'));
    }

    public function create()
    {
        return view('admin.warranty.create');
    }

    public function store(WarrantyRequest $request)
    {
        $params['name'] = $request->name;

        $warranty = \DB::transaction(
            function () use ($params) {
                $warranty = Warranty::create($params);

                return $warranty;
            }
        );

        Alert::success("Success", "Created new warranty");

        return redirect()->route('warranties.index');
    }

    public function edit($id)
    {
        $warranty = Warranty::findOrFail($id);

        return view('admin.warranty.edit', compact('warranty'));
    }

    public function update(WarrantyRequest $request, $id)
    {
        $warranty       = Warranty::findOrFail($id);
        $params['name'] = $request->name;

        $update = \DB::transaction(
            function () use ($warranty, $params) {
                $warranty->update($params);

                return $warranty;
            }
        );

        Alert::success("Success", "Update entire warranty");

        return redirect()->route('warranties.index');
    }

    public function destroy($id)
    {
        $warranty = Warranty::findOrFail($id);
        $warranty->delete();

        Alert::success("Success", "Delete entire warranty");

        return redirect()->route('warranties.index');
    }
}
