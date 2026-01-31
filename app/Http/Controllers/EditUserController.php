<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ToStringTrait;

/**
 * MA05: 利用者編集画面構成モジュール
 */
class EditUserController extends Controller
{
    use ToStringTrait;
    /**
     * 利用者編集画面を表示する (GET)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $postal_code_str = $this->postalCodeToString($user->postal_code);
        $addr_city_str = $this->cityToString($user->addr_city);

        return view('user-detail', [
            'user' => $user,
            'postal_code_str' => $postal_code_str,
            'addr_city_str' => $addr_city_str,
        ]);
    }

    /**
     * 利用者編集処理を実行 (POST/PUT)
     * * @param Request $request 利用者から受け取った利用者情報を含むHTTPリクエスト
     * @return \Illuminate\Http\RedirectResponse 利用者一覧画面へのリダイレクト
     */
    public function update(Request $request)
    {
        // 1. 入力内容のバリデーション
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'login_id' => 'required|string|max:255',
            'permission' => 'required|integer|between:0,2',
            'num_plan_std' => 'required|integer|min:0',
            'num_plan_prm' => 'required|integer|min:0',
            'postal_code' => 'nullable|integer',
            'addr_city' => 'nullable|integer',
            'addr_detail' => 'nullable|string|max:255',
        ]);

        $user = User::find($request->input('id'));

        // 2. 利用者モジュールを用いて利用者編集処理を実行
        $user->name = $request->input('name');
        $user->permission = $request->input('permission');
        $user->num_plan_std = $request->input('num_plan_std');
        $user->num_plan_prm = $request->input('num_plan_prm');

        // 住所情報の更新（任意項目）
        $user->postal_code = $request->input('postal_code');
        $user->addr_city = $request->input('addr_city');
        $user->addr_detail = $request->input('addr_detail');

        // 保存
        if ($user->save()) {
            // 3. 利用者一覧画面へのリダイレクトを含む応答
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'user-updated');
        }

        // エラーメッセージを含むページを応答
        return redirect()
            ->back()
            ->withErrors(['error' => '利用者情報の更新に失敗しました。'])
            ->withInput();
    }
}
