<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\MiscService;
use App\Services\UserService;

class AuthController extends Controller
{
    private $userService;
    private $miscService;

    public function __construct(UserService $userService, MiscService $miscService)
    {
        $this->userService = $userService;
        $this->miscService = $miscService;
    }

    public function showLoginForm()
    {
        session()->put('backUrl', url()->previous());

        $url = explode("/", url()->previous());
        // return $url;
        // if ($url[3] == "login" || $url[3] == "register") session()->put('backUrl', '/');

        return view('ecommerce.auth.login');
    }

    public function userLogin(LoginRequest $request)
    {
        $params = $request->only('email', 'password');

        $token = User::whereEmail($request->email)->first()->active_token;
        if ($token)
        {
            return back()->with([
                'message' => 'You need to verify your email first if you want to get login access.'
            ]);
        }

        auth()->attempt($params);

        if (auth()->check())
        {
            if(auth()->user()->hasRole('customer'))
            {
                return redirect()->route('ecommerce.index');
            }
            else {
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors(['email' => 'The user credentials does not match on our records.']);
    }

    public function showRegisterForm()
    {
        $provinces = $this->miscService->getProvinces();

        return view('ecommerce.auth.register', compact('provinces'));
    }

    public function userRegister(RegisterRequest $request)
    {
        return $this->userService->create($request, "customer");
    }

    public function verifyEmail($token)
    {
        $user = User::where('active_token', $token)->first();

        if ($user)
        {
            $user->update([
                'active_token'  => null
            ]);

            return redirect()->route('ecommerce.login.index')->with([
                "success" => "Congratulation! Your email already get verified.
                    And now, you can login to start shopping at E-Store!"
            ]);
        }

        return redirect()->route('ecommerce.register.verify.expired');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('ecommerce.index');
    }
}
