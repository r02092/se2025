<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ImgValidateTrait
{
    public function validateImg($file)
    {
        if (!$file->isValid()) {
            $errorMsg = $file->getErrorMessage();
            // PHP設定(upload_max_filesize)によるサイズオーバーの特定
            if ($file->getError() == UPLOAD_ERR_INI_SIZE) {
                $errorMsg =
                    '画像のサイズが大きすぎます（2MiB以下の画像を使用してください）。';
            }

            return [
                'icon' => 'アップロードエラー: ' . $errorMsg,
            ];
        }

        // バリデーション (MIMEタイプなど)
        $validator = Validator::make(
            ['icon' => $file],
            [
                'icon' =>
                    'image|mimes:avif,bmp,gif,jpg,png,svg,webp|max:2048|extensions:avif,bmp,gif,jfif,jpeg,jpg,png,svg,webp',
            ], // 2MiB制限
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }
}
