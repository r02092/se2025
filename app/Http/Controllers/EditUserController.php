<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * MA05: 利用者編集画面構成モジュール
 */
class EditUserController extends Controller
{
    /**
     * 利用者編集画面を表示する (GET)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('user-detail', ['user' => $user])->with(
            $this->selectPrefsCities(),
        );
    }

    /**
     * 利用者編集処理を実行 (POST/PUT)
     * * @param Request $request 利用者から受け取った利用者情報を含むHTTPリクエスト
     * @return \Illuminate\Http\RedirectResponse 利用者一覧画面へのリダイレクト
     */
    public function update(Request $request)
    {
        // 入力内容のバリデーション
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'login_name' => [
                'required',
                'string',
                'max:255',
                'unique:App\Models\User,login_name',
            ],
            'icon' => ['image', 'mimes:jpeg,jpg,png,gif', 'max:2048']
            'permission' => ['required', 'integer', 'between:0,2'],
            'num_plan_std' => ['required', 'integer', 'between:0,4294967295'],
            'num_plan_prm' => ['required', 'integer', 'between:0,4294967295'],
            'post_code' => ['required', 'digits:7'], // ハイフンなし7桁
            'city' => ['required', 'integer', 'between:1100,47999'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find($request->input('id'));

        // 利用者モジュールを用いて利用者編集処理を実行
        $user->name = $request->input('name');
        $user->name = $request->input('login_name');
        $user->permission = $request->input('permission');
        $user->num_plan_std = $request->input('num_plan_std');
        $user->num_plan_prm = $request->input('num_plan_prm');

        // 住所情報の更新（任意項目）
        $user->postal_code = $request->input('postal_code');
        $user->addr_city = $request->input('city');
        $user->addr_detail = $request->input('address');

        // アイコンが含まれていれば保存する
        if($request->hasFile('icon')) {
            $file = $request->file('icon');
            if($file->isValid()) {
                $this->storeIcon($user, $file);
                // 拡張子の記録
                $extension = $file->getClientOriginalExtension();
                $user->icon_ext = $extension;
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['error' => 'アイコンの保存に失敗しました。'])
                    ->withInput();
            }
        }

        // ユーザーデータ保存
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

    private function storeIcon(User $user, UploadedFile $file): void
    {
        // 古い画像があれば削除
        if ($user->icon_ext) {
            Storage::delete(
                'public/icons/' . $user->id . '.' . $user->icon_ext,
            );
        }
        $extension = $file->getClientOriginalExtension();
        // ファイル名を「ユーザーID.拡張子」にする
        $fileName = $user->id . '.' . $extension;
        // storage/app/public/icons に保存
        $file->storeAs('public/icons', $fileName);
    }
}
