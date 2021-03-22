<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\BankRequest;
use App\Models\Admin\Bank;
use RealRashid\SweetAlert\Facades\Alert;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderByDesc('id')->paginate(5);

        return view('admin.bank.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.bank.create');
    }

    public function store(BankRequest $request)
    {
        $params['name']     = $request->name;

        $store = \DB::transaction(
            function() use($params) {
                $bank = Bank::create($params);

                return $bank;
            }
        );

        Alert::success("Success", "Created New Bank");

        return redirect()->route('banks.index');
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);

        return view('admin.bank.edit', compact('bank'));
    }

    public function update(BankRequest $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $params['name']  = $request->name;

        $update = \DB::transaction(
            function() use($bank, $params) {
                $bank->update($params);

                return $bank;
            }
        );

        Alert::success("Success", "Update entire bank");

        return redirect()->route('banks.index');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        Alert::success("Success", "Delete entire bank");

        return back();
    }
}
