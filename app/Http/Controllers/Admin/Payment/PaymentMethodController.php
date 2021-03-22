<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentMethodRequest;
use App\Models\Admin\PaymentMethod;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderByDesc('id')->paginate(5);

        return view('admin.paymentmethods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.paymentmethods.create');
    }

    public function store(PaymentMethodRequest $request)
    {
        $params['name'] = $request->name;

        $store = \DB::transaction(
            function() use($params)
            {
                $paymentMethod = PaymentMethod::create($params);

                return $paymentMethod;
            }
        );

        Alert::success("Success", "Create new payment method");

        return redirect()->route('payment-methods.index');
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('admin.paymentmethods.edit', compact('paymentMethod'));
    }

    public function update(PaymentMethodRequest $request, $id)
    {
        $paymentMethod  = PaymentMethod::findOrFail($id);
        $params['name'] = $request->name;

        $update = \DB::transaction(
            function() use($paymentMethod, $params)
            {
                return $paymentMethod->update($params);
            }
        );

        Alert::success("Success", "Update entire payment method");

        return redirect()->route('payment-methods.index');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        Alert::success("Success", "Delete entire payment method");

        return back();
    }
}
