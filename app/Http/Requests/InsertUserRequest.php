<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->permission === 0;
    }

    public function rules(): array
    {
        return [
            'login_name' => [
                'required',
                'string',
                'max:255',
                'unique:users,login_name',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // confirmed: password_confirmation と一致を確認
            'name' => ['required', 'string', 'max:255'],
            'permission' => ['required', 'integer', Rule::in([0, 1, 2])],
            // 住所情報は任意
            'postal_code' => ['nullable', 'integer', 'digits:7'],
            'addr_city' => ['nullable', 'integer'],
            'addr_detail' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'login_name' => 'ログインID',
            'password' => 'パスワード',
            'name' => '表示名',
            'permission' => '権限',
            'postal_code' => '郵便番号',
            'addr_city' => '市区町村コード',
            'addr_detail' => '住所詳細',
        ];
    }
}
