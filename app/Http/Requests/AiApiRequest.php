<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AiApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 安全のため文字数制限を追加 (推奨)
            'chat' => 'required|string|max:1000',
            // ▼ 変更: "to" が空っぽの時だけ、"from" を必須にする
            'from' => 'nullable|integer|exists:spots,id|required_without:to',
            // ▼ 変更: "from" が空っぽの時だけ、"to" を必須にする
            'to' =>
                'nullable|integer|exists:spots,id|required_without:from|different:from',
        ];
    }
}
