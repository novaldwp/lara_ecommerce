<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\OptionValueRequest;
use App\Models\Admin\Option;
use App\Models\Admin\OptionValue;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OptionValueController extends Controller
{
    public function index()
    {
        $optionValues   = OptionValue::with(['options'])->orderByDesc('id')->paginate(5);

        return view('admin.optionvalues.index', compact('optionValues'));
    }

    public function create()
    {
        $options    = Option::orderBy('name')->get();

        return view('admin.optionvalues.create', compact('options'));
    }

    public function store(OptionValueRequest $request)
    {
        $params['name']         = $request->name;
        $params['option_id']    = $request->option_id;

        $optionValues = \DB::transaction(
            function () use ($params) {
                $optionValues = OptionValue::create($params);

                return $optionValues;
            }
        );

        Alert::success("Success", 'Created new option values');

        return redirect()->route('option-values.index');
    }

    public function edit($id)
    {
        $optionValue    = OptionValue::with(['options'])->findOrfail($id);
        $options        = Option::orderBy('name')->get();

        return view('admin.optionvalues.edit', compact('optionValue', 'options'));
    }

    public function update(OptionValueRequest $request, $id)
    {
        $optionValue = OptionValue::findOrFail($id);
        $params['name']         = $request->name;
        $params['option_id']    = $request->option_id;

        $update = \DB::transaction(
            function() use($optionValue, $params)
            {
                $optionValue->update($params);

                return $optionValue;
            }
        );

        Alert::success("Success", "Update entire option value");

        return redirect()->route('option-values.index');
    }

    public function destroy($id)
    {
        $optionValue = findOrFail($id);
        $optionValue->delete();

        Alert::success("Success", "Delete selected option value");

        return redirect()->route('option-values.index');
    }
}
