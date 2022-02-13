<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\MiscService;
use App\Http\Requests\Front\Auth\RegisterRequest;
use App\Mail\MemberRegisterMail;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Support\Str;

class RegisterCustomerController extends Controller
{
    public function index(MiscService $misc) {
        $provinces = $misc->getProvinces();

        return view('ecommerce.auth.register', compact('provinces'));
    }

    public function store(RegisterRequest $request, CustomerService $customer)
    {
        $customer = $customer->create($request);
        return $customer;
        Mail::to($request->email)->send(new MemberRegisterMail($params));

        return redirect()->route('ecommerce.login.index')->with([
            'success' => 'Congratulations! You\'ve already registered an account, but you need to check your email
                            registration to verify your account.'
        ]);
    }

    public function verifyEmail($token)
    {
        $member = Member::where('active_token', $token)->first();

        if ($member)
        {
            $member->update([
                'active_token'  => null,
                'status'        => 1
            ]);

            return redirect()->route('ecommerce.login.index')->with([
                "success" => "Congratulation! Your email already get verified.
                    And now, you can login to start shopping at E-Store!"
            ]);
        }

        return redirect()->route('ecommerce.register.verify.expired');
    }
}
