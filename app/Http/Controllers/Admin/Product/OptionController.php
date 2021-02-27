<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\OptionRequest;
use App\Models\Admin\Option;
use RealRashid\SweetAlert\Facades\Alert;

class OptionController extends Controller
{
    public function index()
    {
        $options = Option::orderByDesc('id')->paginate(5);

        return view('admin.option.index', compact('options'));
    }

    public function create()
    {
        return view('admin.option.create');
    }

    public function store(OptionRequest $request)
    {
        $params['name']         = $request->name;
        $params['description']  = $request->description;

        $option = \DB::transaction(
            function () use($params) {
                $option = Option::create($params);

                return $option;
            }
        );

        Alert::success("Success", "Created new option");

        return redirect()->route('options.index');
    }

    public function edit($id)
    {
        $option = Option::findOrFail($id);

        return view('admin.option.edit', compact('option'));
    }

    public function update(OptionRequest $request, $id)
    {
        $option                 = Option::findOrFail($id);
        $params['name']         = $request->name;
        $params['description']  = $request->description;

        $update = \DB::transaction(
            function () use($option, $params) {
                $option->update($params);

                return $option;
            }
        );

        Alert::success("Success", "Created new option");

        return redirect()->route('options.index');
    }

    public function destroy($id)
    {
        $option = Option::findOrFail($id);
        $option->delete();

        Alert::success("Success", "Delete selected option");

        return redirect()->route('options.index');
    }
}
