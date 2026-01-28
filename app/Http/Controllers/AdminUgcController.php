<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Photo;
use DB;
use App\Http\Requests\AdminUgcRequest;

class AdminUgcController extends Controller
{
    const DISPLAY_NUM = 20;
    public function get($page)
    {
        return view('post', [
            'page' => $page,
            'posts' => array_map(
                function ($item) {
                    return [
                        'type' => $item['type'],
                        'data' =>
                            $item['type'] !== 'photo'
                                ? Review::with('user')->find($item['id'])
                                : Photo::with('user')->find($item['id']),
                    ];
                },
                Review::select(DB::raw('"review" as type'), 'id', 'updated_at')
                    ->union(
                        Photo::select(
                            DB::raw('"photo" as type'),
                            'id',
                            'updated_at',
                        ),
                    )
                    ->orderBy('updated_at', 'desc')
                    ->offset($page * self::DISPLAY_NUM)
                    ->limit(self::DISPLAY_NUM)
                    ->get()
                    ->toArray(),
            ),
            'nextBtn' =>
                Review::count() + Photo::count() >
                ($page + 1) * self::DISPLAY_NUM,
        ]);
    }
    public function post(AdminUgcRequest $request)
    {
        $id = $request->input('id');
        ($request->input('type') !== 'photo'
            ? Review::find($id)
            : Photo::find($id)
        )->delete();
        return redirect()->back();
    }
}
