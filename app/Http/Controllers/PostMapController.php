<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\User;

class PostMapController extends Controller
{
    public function index()
    {
        return view('photo');
    }

    public function getPosts(Request $request)
    {
        $cner_ne_lat = $request->query('ne_lat');
        $cner_ne_lng = $request->query('ne_lng');
        $cner_sw_lat = $request->query('sw_lat');
        $cner_sw_lng = $request->query('sw_lng');

        $photos = Photo::with('user')->get();

        $photos = $photos->filter(function ($photo) use (
            $cner_ne_lat,
            $cner_ne_lng,
            $cner_sw_lat,
            $cner_sw_lng,
        ) {
            $lat = $photo->lat;
            $lng = $photo->lng;

            return $lat <= $cner_ne_lat &&
                $lat >= $cner_sw_lat &&
                $lng <= $cner_ne_lng &&
                $lng >= $cner_sw_lng;
        });

        $photos = $photos->map(function ($photo) {
            $user = $photo->user;
            return [
                'username' => $user->name,
                'avatar_url' => asset(
                    'storage/icons/' . $user->id . '.' . $user->icon_ext,
                ),
                'photo_img_url' => asset(
                    'storage/posts/' . $photo->id . '.' . $photo->img_ext,
                ),
                'comment' => $photo->comment,
                'lat' => $photo->lat,
                'lng' => $photo->lng,
                'created_at' => $photo->created_at->timestamp,
                'updated_at' => $photo->updated_at->timestamp,
            ];
        });

        return response()->json($photos->toArray());
    }
}
