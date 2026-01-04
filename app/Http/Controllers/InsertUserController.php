<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\validation\ValidationException;

class InsertUserController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }
    public function store(Request $requst)
    {
        $validated = $request->validate([
            'login_name' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['requrired', 'string', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
            'permission' => ['required', 'integer', Rule::in([
                User::PERMISSION_ADMIN,
                User::PERMISSION_USER,
                User::PERMISSION_BUSINESS
            ])],
            'postal_code' => ['nullable', 'intager'],
            'address' => ['nullable', 'intager'],
            'addr_detail' => ['nullable', 'string'],
        ]);
        User::create([
            'provider' => User::PROVIDER_SCENETRIP,
            'login_name' => $validated['login_name'],
            'password' => $validated['password'],
            'permission' => $validated['permission'],
            'name' => $validated['name'],
            'icon_ext' => 'png',
            'num_plan_std' => 0,
            'num_plan_prm' => 0,
            'postal_code' => $validated['postal_code'] ?? null,
            'addr_city' => $validated['addr_city'] ?? null,
            'addr_detail' => $validated['addr_detail'] ?? null,
        ]);
        return redirect()->route('admin.users.index')
            ->with('success', '利用者を登録しました。');
    }
}
