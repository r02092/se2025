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

    public function getPosts($request Request)
    {
        $cner_ne_lat = $request->query('ne_lat');
        $cner_ne_lng = $request->query('ne_lng');
        $cner_sw_lat = $request->query('sw_lat');
        $cner_sw_lng = $request->query('sw_lng');

        $photos = Photo::with('user')->all();

        $photos = $photos->filter(function ($photo) use ($corner_ne, $corner_sw) {
            $lat = $photo->get('lat');
            $lng = $photo->get('lng');

            return $lat <= $cner_ne_lat && $lat >= $cner_sw_lat && $lng <= $cner_ne_lng && $lng >= $cner_sw_lng;
        });

        $photos = $photos->map(function ($photo ) {
            $user = $photo->user;
            return [
                'username' => $user->get('name'),
                'avatar_url' => Strage::url(
                    '/app/public/icons/' .
                        $user->get('id') .
                        '.' .
                        $user->get('img_ext'),
                ),
                'photo_image_url' => Strage::url(
                    '/posts/' .
                        $photo->get('id') .
                        '.' .
                        $photo->get('img_ext'),
                ),
                'comment' => $photo->get('comment'),
                'created_at' => $photo->get('created_at')->timestamp,
                'updated_at' => $photo->get('updated_at')->timestamp,
            ];
        });

        return response()->json($photos->toArray());
    }
}
