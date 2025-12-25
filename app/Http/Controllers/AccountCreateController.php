<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCreateRequest;
use App\Models\User;

class AccountCreateController extends Controller
{
    public function get()
    {
        return response(''); // 仮
    }
    public function post(AccountCreateRequest $request)
    {
        if (
            $request->input('password') !== $request->input('password_confirm')
        ) {
            return response('', 400); // 仮
        }
        $user = new User();
        $user->name = $request->input('name');
        $user->login_name = $request->input('login_name');
        $user->password = password_hash(
            $request->input('password'),
            PASSWORD_ARGON2ID,
        );
        $user->save();
        return response(''); // 仮
    }
}
