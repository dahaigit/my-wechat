<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;


class UserController extends ApiController
{
    public function info(Request $request)
    {
        dd($request->user());
        return view('info');
    }
}
