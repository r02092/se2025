<?php

namespace App\Http\Controllers;

use ZipArchive;

class AddrApiController extends Controller
{
    public function get($pc)
    {
        if (!preg_match('/^\d{7}$/', $pc)) {
            return response()->json(['city' => 0, 'addr' => '']);
        }
        foreach (
            [
                [
                    'utf/zip/utf_ken_all.zip',
                    '/^(\d{5}),"(\d{3}  |\d{5})","',
                    '",".+",".+",".+",".+","(.+)","(.+)",[01],[01],[01],[01],[0-2],[0-6]\r$/m',
                    'utf_ken_all.csv',
                ],
                [
                    'jigyosyo/zip/jigyosyo.zip',
                    '/^(\d{5}),".+",".+",".+",".+","(.+)","(.+)","',
                    '","(\d{3}  |\d{5})",".+",[01],[0-3],[015]\r$/m',
                    'JIGYOSYO.CSV',
                ],
            ]
            as $key => $vals
        ) {
            $path = storage_path('app/private/' . basename($vals[0]));
            if (
                !file_exists($path) ||
                filemtime($path) < time() - 7 * 24 * 60 * 60
            ) {
                file_put_contents(
                    $path,
                    file_get_contents(
                        'https://www.post.japanpost.jp/zipcode/dl/' . $vals[0],
                    ),
                );
            }
            $zip = new ZipArchive();
            if (
                $zip->open($path) &&
                preg_match_all(
                    $vals[1] . $pc . $vals[2],
                    $zip->getFromName($vals[3]),
                    $matches,
                    PREG_SET_ORDER,
                )
            ) {
                $addrs = [];
                foreach ($matches as $match) {
                    if (count($matches) > 1 && $match[1] !== $matches[1][1]) {
                        break;
                    }
                    switch ($key) {
                        case 0:
                            $addr = $match[4];
                            if (
                                $addr === '以下に掲載がない場合' ||
                                preg_match(
                                    '/（([１-９][０-９]*階|地階・階層不明)）$/u',
                                    $addr,
                                )
                            ) {
                                $addr = '';
                            } else {
                                if (preg_match('/^(.+)（.+）$/', $addr, $m)) {
                                    $addr = $m[1];
                                }
                                if (
                                    preg_match(
                                        '/^' .
                                            preg_replace(
                                                '/^.+郡/',
                                                '',
                                                $match[3],
                                            ) .
                                            '(の次に.+がくる場合|一円)$/',
                                        $addr,
                                    )
                                ) {
                                    $addr = '';
                                } elseif (
                                    preg_match(
                                        '/^(.*).+[、〜]\1.+$/u',
                                        $addr,
                                        $m,
                                    )
                                ) {
                                    $addr = $m[1];
                                } elseif (
                                    preg_match(
                                        '/^(.+)の次に.+がくる場合$/',
                                        $addr,
                                        $m,
                                    )
                                ) {
                                    $addr = $m[1];
                                }
                            }
                            break;
                        case 1:
                            $addr = mb_convert_encoding(
                                $match[2] . $match[3],
                                'UTF-8',
                                'SJIS',
                            );
                            break;
                    }
                    $addrs[] = $addr;
                }
                $pref = intdiv(intval($matches[0][1]), 1000);
                if (!count($addrs)) {
                    if (
                        array_any(array_column($matches, 1), function ($v) use (
                            $pref,
                        ) {
                            return intdiv(intval($v), 1000) !== $pref;
                        })
                    ) {
                        $pref = 0;
                    }
                    $city = $pref . '000';
                    $addr = '';
                } else {
                    $city = $matches[0][1];
                    for ($i = mb_strlen($addrs[0]); $i >= 0; $i--) {
                        $addr = mb_substr($addrs[0], 0, $i);
                        if (
                            array_all($addrs, function ($v) use ($addr) {
                                return str_starts_with($v, $addr);
                            })
                        ) {
                            break;
                        }
                    }
                }
                return response()->json([
                    'city' => intval($city),
                    'addr' => $addr,
                ]);
            }
        }
    }
}
