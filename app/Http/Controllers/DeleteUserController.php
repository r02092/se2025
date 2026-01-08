<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * MA06: 利用者削除画面構成モジュール
 */
class DeleteUserController extends Controller
{
    /**
     * 利用者削除処理を実行 (POST/DELETE)
     * * @param Request $request 利用者から受け取った利用者のIDを含むHTTPリクエスト
     * @return \Illuminate\Http\RedirectResponse Webページまたはリダイレクト応答となるHTTPレスポンス
     */
    public function destroy(Request $request)
    {
        // 1. 入力内容のバリデーション（IDの存在確認）
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $targetId = $request->input('id');
        $admin = Auth::user();

        // セキュリティ上の制限: 自分自身を削除できないようにする
        if ($admin->id == $targetId) {
            return redirect()
                ->back()
                ->withErrors([
                    'error' =>
                        '自分自身のアカウントを削除することはできません。',
                ]);
        }

        // 2. 利用者モジュールを用いて利用者削除処理を実行
        $user = User::find($targetId);

        if ($user) {
            // ソフトデリート（usersテーブルのdeleted_atを更新）
            $user->delete();

            // 3. 利用者一覧画面へのリダイレクトを含む応答
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'user-deleted');
        }

        // エラーメッセージを含むページを応答
        return redirect()
            ->back()
            ->withErrors(['error' => '指定された利用者の削除に失敗しました。']);
    }

    /**
     * 削除確認画面を表示する場合 (GET)
     */
    public function showConfirm($id)
    {
        $user = User::findOrFail($id);

        return view('user-delete-confirm', [
            'user' => $user,
        ]);
    }
}
