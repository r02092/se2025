<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiKeyRequest;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function get()
    {
        $apiKeys = Auth::user()->apiKeys()->get();
        return response(''); // 仮: APIキー一覧表示
    }
    public function post(ApiKeyRequest $request)
    {
        $apiKeysTable = Auth::user()->apiKeys();
        if ($request->filled('delete_id')) {
            $apiKey = $apiKeysTable->find($request->input('delete_id'));
            $apiKey->delete();
            return response(''); // 仮: APIキー削除
        }
        $apiKeyString = '';
        for ($i = 0; $i < 85; $i++) {
            $rnd = random_int(0, 67);
            $apiKeyString .=
                $rnd < 3
                    ? '+_~'[$rnd]
                    : chr($rnd + ($rnd < 16 ? 42 : ($rnd < 42 ? 49 : 55)));
        }
        $apiKeysTable->create([
            'name' => $request->input('create_name'),
            'key' => hash('sha3-512', $apiKeyString, true),
            'ip_addr' => $request->ip() ?? '192.0.2.0',
            'port' => $request->getPort() ?? 0,
            'user_agent' => $request->userAgent() ?? '',
        ]);
        return response(''); // 仮: APIキー作成
    }
}
