<?php

namespace App\Services;

class RedirectService {

    public function indexPage($route, $message)
    {
        return redirect()->route($route)->withSuccess($message);
    }

    public function backPage($e)
    {
        return back()->withError($e->getMessage())->withInput();
    }
}
