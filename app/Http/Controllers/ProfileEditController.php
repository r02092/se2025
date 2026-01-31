<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 追加
use App\Traits\ImgValidateTrait;

/**
 * MC09: プロフィール編集画面構成モジュール
 */
class ProfileEditController extends Controller
{
    use ImgValidateTrait;
    /**
     * プロフィール編集画面を表示する (GET)
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile-edit', [
            'user' => $user,
        ]);
    }

    /**
     * プロフィール情報（名前・ログイン名）の更新 (POST/PUT)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // POSTサイズ超過チェック (POSTデータが空で、かつContent-Lengthがある場合)
        if (empty($request->all()) && $request->server('CONTENT_LENGTH') > 0) {
            return redirect()
                ->back()
                ->withErrors([
                    'icon' =>
                        'アップロードされたファイルのサイズが大きすぎます。設定を確認してください(post_max_size)。',
                ]);
        }

        // 1. 入力値のバリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'login_name' =>
                'required|string|max:255|unique:users,login_name,' . $user->id,
            'new-password' => 'nullable|string|min:8|same:confirm-password',
            // iconのバリデーションは個別に行う
        ]);

        // 3. プロフィール更新処理を実行
        $user->name = $request->input('name');
        $user->login_name = $request->input('login_name');

        // アイコン画像のアップロード処理
        // hasFile() はファイルが有効でない場合(サイズオーバー等)に false を返すことがあるため、
        // file() を直接取得してエラーを確認する
        $file = $request->file('icon');

        if ($file) {
            $error = $this->validateImg($file);

            if ($error) {
                return redirect()->back()->withErrors($error);
            }

            $extension = $file->getClientOriginalExtension();
            $fileName = $user->id . '.' . $extension;

            // publicディスクのiconsディレクトリに保存
            $file->storeAs('icons', $fileName, 'public');

            $user->icon_ext = $extension;
        }

        // 入力欄（new-password）が空でない場合のみ処理する
        if ($request->filled('new-password')) {
            // 現在のパスワードが合っているかチェック
            if (
                !password_verify(
                    $request->input('current-password'),
                    $user->password,
                )
            ) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'current-password' =>
                            '現在のパスワードが正しくありません。',
                    ]);
            }
            // パスワードをハッシュ化して保存
            $user->password = password_hash(
                $request->input('new-password'),
                PASSWORD_ARGON2ID,
            );
        }

        $user->save();

        // 4. 更新完了画面（または編集画面）へ遷移
        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile.updated');
    }
}
