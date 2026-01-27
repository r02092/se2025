<?php

namespace App\Http\Controllers;

use App\Traits\ToStringTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Spot;
use Illuminate\Http\Request;

class EditSpotController extends Controller
{
    use ToStringTrait;

    const DISPLAY_NUM = 5;
    public function get($page)
    {
        return view(
            'spot-edit',
            array_merge(
                [
                    'spots' => (Auth::user()->permission
                        ? Spot::where('user_id', Auth::user()->id)
                        : Spot::with('user')
                    )
                        ->offset($page * self::DISPLAY_NUM)
                        ->limit(self::DISPLAY_NUM)
                        ->get(),
                    'types' => $this->types,
                    'enablePlans' => Auth::user()->permission
                        ? array_map(function ($v) {
                            return Spot::where('user_id', Auth::user()->id)
                                ->where('plan', $v)
                                ->count() <
                                [
                                    Auth::user()->num_plan_std,
                                    Auth::user()->num_plan_prm,
                                ][$v];
                        }, range(0, 1))
                        : [true, true],
                ],
                $this->selectPrefsCities(),
            ),
        );
    }
    public function update(Request $request)
    {
        if (isset($request->id)) {
            $spot = Spot::find($request->id);
        } else {
            $spot = new Spot();
            $spot->user_id = Auth::user()->id;
            $spot->plan = $request->plan;
            if (
                Spot::where('user_id', Auth::user()->id)
                    ->where('plan', $spot->plan)
                    ->count() >=
                [Auth::user()->num_plan_std, Auth::user()->num_plan_prm][
                    $spot->plan
                ]
            ) {
                return response()->json(
                    [
                        'error' =>
                            '上限を超えてスポットを作成することはできません。',
                    ],
                    400,
                );
            }
            $spot->stamp_key = rand(0, PHP_INT_MAX);
        }
        $spot->name = $request->name;
        $spot->type = intval($request->type);
        if ($file = $request->file('img')) {
            if ($file->isValid()) {
                if ($spot->img_ext) {
                    Storage::delete(
                        'spots/' . $spot->id . '.' . $spot->img_ext,
                        'public',
                    );
                }
                $ext = $file->getClientOriginalExtension();
                $file->storeAs('spots/', $spot->id . '.' . $ext, 'public');
                $spot->img_ext = $ext;
            }
        }
        $spot->description = $request->description;
        $spot->postal_code = $request->pc;
        $spot->addr_city = $request->city;
        $spot->addr_detail = $request->address;
        if (
            preg_match(
                '/^\[(\d+.\d+),(\d+.\d+)\]$/',
                $request->input('coord'),
                $match,
            )
        ) {
            $spot->lng = $match[1];
            $spot->lat = $match[2];
        }
        $spot->save();
        return redirect()->route('business.spots', 0);
    }
    public function delete(Request $request)
    {
        Spot::find($request->id)->delete();
        return redirect()->route('business.spots', 0);
    }
}
