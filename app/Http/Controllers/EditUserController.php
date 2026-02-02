<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        return view('user-detail', ['user' => $user])->with(
            $this->selectPrefsCities(),
        );
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $id = $request->input('id');
        $user = User::find($id);
        $fileName = $id . '.' . $user->icon_ext;

        // ユーザ削除
        User::findOrFail($id)->delete();

        // 画像削除
        Storage::disk('public')->delete('icons/' . $fileName);

        return redirect()->route('admin.users.list');
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
            'login_name' => ['required', 'string', 'max:255'],
            'icon' => ['image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
            'permission' => ['required', 'integer', 'between:0,2'],
            'num_plan_std' => ['required', 'integer', 'between:0,4294967295'],
            'num_plan_prm' => ['required', 'integer', 'between:0,4294967295'],
            'post_code' => ['required', 'digits:7'], // ハイフンなし7桁
            'city' => ['required', 'integer', 'between:1100,47999'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find($request->input('id'));

        // 対象のユーザは除いてlogin_nameカラムの値の重複を検証
        $request->validate([
            'login_name' => "unique:users,login_name,{$user->login_name},login_name",
        ]);

        // 利用者モジュールを用いて利用者編集処理を実行
        $user->name = $request->input('name');
        $user->login_name = $request->input('login_name');
        $user->permission = $request->input('permission');
        $user->num_plan_std = $request->input('num_plan_std');
        $user->num_plan_prm = $request->input('num_plan_prm');

        if ($user->permission == User::PERMISSION_BUSINESS) {
            // 住所情報の更新（任意項目）
            $user->postal_code = $request->input('post_code');
            $user->addr_city = $request->input('city');
            $user->addr_detail = $request->input('address');
        }

        // アイコンが含まれていれば保存する
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            if ($file->isValid()) {
                $this->storeIcon($user, $file);
                // 拡張子の記録
                $extension = $file->getClientOriginalExtension();
                $user->icon_ext = $extension;
            } else {
                return back()->withErrors('アイコンの保存に失敗しました。');
            }
        }

        // ユーザーデータ保存
        if ($user->save()) {
            // 利用者一覧画面へのリダイレクトを含む応答
            return back();
        }

        // エラーメッセージを含むページを応答
        return redirect()
            ->back()
            ->withErrors('利用者情報の更新に失敗しました。');
    }

    private function storeIcon(User $user, UploadedFile $file): void
    {
        // 古い拡張子
        $oldExt = $user->icon_ext;
        // 新しい拡張子
        $ext = $file->getClientOriginalExtension();
        // ファイル名を「ユーザーID.拡張子」にする
        $fileName = $user->id . '.' . $ext;

        // 古い画像があれば削除
        if (isset($oldExt)) {
            $oldFileName = $user->id . '.' . $oldExt;
            Storage::disk('public')->delete('icons/' . $oldFileName);
        }
        // storage/app/public/icons に保存
        $file->storeAs('icons', $fileName, 'public');
    }
}
