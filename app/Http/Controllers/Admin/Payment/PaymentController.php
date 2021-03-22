<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Admin\Bank;
use App\Models\Admin\Payment;
use App\Models\Admin\PaymentMethod;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['banks', 'payment_methods'])->orderByDesc('id')->paginate(5);
        // dd($payments);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('admin.payments.create', compact('banks', 'paymentMethods'));
    }

    public function store(PaymentRequest $request)
    {
        $params['name']     = $request->name;
        $params['number']   = $request->number;
        $params['bank_id']  = $request->bank_id;
        $params['payment_method_id'] = $request->payment_method_id;

        $store = \DB::transaction(
            function() use($params)
            {
                $payment = Payment::create($params);

                return $payment;
            }
        );

        Alert::success("Success", "Create new payment");

        return redirect()->route('payments.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $banks   = Bank::orderBy('name')->get();

        return view('admin.payments.edit', compact('payment', 'banks'));
    }

    public function update(PaymentRequest $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $params['name']     = $request->name;
        $params['number']   = $request->number;
        $params['bank_id']  = $request->bank_id;
        $params['payment_method_id'] = $request->payment_method_id;

        $update = \DB::transaction(
            function() use($payment, $params)
            {
                $payment->update($params);
            }
        );

        Alert::success("Success", "Update entire payment");

        return redirect()->route('payments.index');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        Alert::success("Success", "Delete entire payment");

        return back();
    }
}
