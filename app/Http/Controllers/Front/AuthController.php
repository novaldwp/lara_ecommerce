<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Auth\LoginRequest;
use App\Http\Requests\Front\Auth\RegisterRequest;
use App\Models\Front\Member;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberRegisterMail;
use Str;

class AuthController extends Controller
{
    public function loginForm()
    {
        session()->put('backUrl', url()->previous());

        return view('ecommerce.auth.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $params = $request->only('email', 'password');

        if (auth()->guard('members')->attempt($params))
        {
            $token = auth()->guard('members')->user()->active_token;

            if ($token)
            {
                return redirect()->route('ecommerce.login.index')->with([
                    'message' => 'You need to verify your email first if you want to get login access.'
                ]);
            }

            return (session()->has('backUrl')) ? redirect(session()->get('backUrl')) : redirect()->route('ecommerce.index');
        }

        return back()->withErrors(['email' => 'The user credentials does not match on our records.']);
    }

    public function registerForm()
    {
        return view('ecommerce.auth.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        $params['first_name']   = $request->first_name;
        $params['last_name']    = $request->last_name;
        $params['email']        = $request->email;
        $params['password']     = $request->password;
        $params['phone']        = $request->phone;
        $params['active_token'] = Str::random(40);
        $params['status']       = 0;

        \DB::transaction(
            function() use($params) {
                return Member::create($params);
            }
        );
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

    public function sendVerifyWhenExpired()
    {
        // on going
    }

    public function getForgotPassword()
    {
        // on going
    }

    public function getTokenForgotPassword($token)
    {
        // on going
    }

    public function logout()
    {
        auth()->guard('members')->logout();

        return redirect()->route('ecommerce.index');
    }
}
