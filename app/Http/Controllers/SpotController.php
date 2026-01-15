<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/*
マップ上でのタッチ（自動入力）と、キーボードによる座標の手打ち入力の
両方をサポートします。どちらの場合も送信されるパラメータは同一です。
*/
class SpotController extends Controller
{
    /**
     * MA01: スポット追加画面構成モジュール (画面表示)
     */
    public function create(): View
    {
        return view('admin.spots.create');
    }

    /**
     * MA01: スポット追加処理 (申請処理)
     */
    public function store(Request $request): RedirectResponse
    {
        // バリデーションルール
        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'location_str' => 'required|string',
            
            // 手打ち入力も許可するため、数値チェックを厳密に行う
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            
            'description' => 'nullable|string',
        ];

        // 手打ち入力時のミスを想定したカスタムエラーメッセージ
        $messages = [
            'latitude.required' => '緯度を入力してください（マップを選択するか、数値を入力してください）。',
            'latitude.numeric' => '緯度は半角数字で入力してください。',
            'latitude.between' => '緯度は -90 から 90 の間で入力してください。',
            'longitude.required' => '経度を入力してください（マップを選択するか、数値を入力してください）。',
            'longitude.numeric' => '経度は半角数字で入力してください。',
            'longitude.between' => '経度は -180 から 180 の間で入力してください。',
        ];

        // 検証実行
        $validated = $request->validate($rules, $messages);

        // ステータスを 'pending' (承認待ち) として作成
        $spot = Spot::create([
            ...$validated,
            'status' => 'pending', 
        ]);

        return redirect()->route('admin.spots.index')
            ->with('success', 'スポットの追加申請を受け付けました。承認をお待ちください。');
    }

    /**
     * 【追加機能】スポット承認処理
     */
    public function approve(int $id): RedirectResponse
    {
        $spot = Spot::findOrFail($id);
        $spot->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'スポットを正式に追加（承認）しました。');
    }

    /**
     * MA02: スポット編集画面構成モジュール
     */
    public function edit(int $id): View
    {
        $spot = Spot::findOrFail($id);
        return view('admin.spots.edit', compact('spot'));
    }

    /**
     * MA02: スポット編集処理
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // 追加時と同様のルールとメッセージを適用
        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'location_str' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
        ];

        $messages = [
            'latitude.numeric' => '緯度は半角数字で入力してください。',
            'latitude.between' => '緯度は -90 から 90 の間で入力してください。',
            'longitude.numeric' => '経度は半角数字で入力してください。',
            'longitude.between' => '経度は -180 から 180 の間で入力してください。',
        ];

        $validated = $request->validate($rules, $messages);

        $spot = Spot::findOrFail($id);
        $spot->update($validated);

        return redirect()->route('admin.spots.index')
            ->with('success', 'スポット情報を更新しました。');
    }

    /**
     * MA03: スポット削除処理
     */
    public function destroy(int $id): RedirectResponse
    {
        $spot = Spot::findOrFail($id);
        $spot->delete();

        return redirect()->route('admin.spots.index')
            ->with('success', 'スポットを削除しました。');
    }
}