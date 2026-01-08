<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\DupLoginNameCheckTrait; // MC06: ログイン名重複チェック

/**
 * MC09: プロフィール編集画面構成モジュール
 */
class ProfileEditController extends Controller
{
    use DupLoginNameCheckTrait;

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

        // 1. 入力値のバリデーション (MC05等のフロントエンド検知と同期)
        $request->validate([
            'name' => 'required|string|max:255',
            'login_name' =>
                'required|string|max:255|unique:users,login_name,' . $user->id,
        ]);

        // 2. ログイン名重複チェック (Trait MC06 の呼び出し)
        // ※バリデーションのuniqueでもチェック可能ですが、設計書のフローに従います
        if (
            $this->isDuplicateLoginName(
                $request->input('login_name'),
                $user->id,
            )
        ) {
            return redirect()
                ->back()
                ->withErrors([
                    'login_name' => 'このログイン名は既に使用されています。',
                ])
                ->withInput();
        }

        // 3. プロフィール更新処理を実行
        $user->name = $request->input('name');
        $user->login_name = $request->input('login_name');
        $user->save();

        // 4. 更新完了画面（または編集画面）へ遷移
        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * アイコン画像のアップロード処理 (POST)
     */
    public function uploadIcon(Request $request)
    {
        $user = Auth::user();

        // 画像のバリデーション
        $request->validate([
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('icon')->isValid()) {
            $file = $request->file('icon');
            $extension = $file->getClientOriginalExtension();

            // 保存先は storage/app/public/icons 等を想定
            $fileName = $user->id . '.' . $extension;
            $file->storeAs('public/icons', $fileName);

            // DB更新: icon_ext カラム
            $user->icon_ext = $extension;
            $user->save();

            return redirect()
                ->route('profile.edit')
                ->with('status', 'icon-updated');
        }

        return redirect()
            ->back()
            ->withErrors(['icon' => '画像のアップロードに失敗しました。']);
    }
}
