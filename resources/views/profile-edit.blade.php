@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="main-area">
    <div class="general-box profile-edit-container">
        <h2>プロフィール編集</h2>
        
        {{-- エラー表示用（デバッグに役立ちます） --}}
        @if ($errors->any())
            <div class="error-msg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 1. 修正：画像送信のために enctype="multipart/form-data" を追加 --}}
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            {{-- @method('PUT') は削除（POST送信のため） --}}

            <div class="profile-edit-avatar-group">
                <img
                    id="profile-preview"
                    src="{{ Auth::user()->icon_ext ? asset('storage/icons/' . Auth::user()->id . '.' . Auth::user()->icon_ext . '?' . time()) : asset('images/Profile_pic.JPG') }}"
                    alt="ユーザーの現在のアバター画像"
                    class="profile-avatar"
                />
                {{-- 2. 修正：nameを 'icon' に変更（コントローラーのuploadIconと合わせる） --}}
                <input
                    type="file"
                    accept="image/png, image/jpeg"
                    id="avatar-img"
                    name="icon"
                />
                <label for="avatar-img">画像をアップロード</label>
            </div>

            <label for="username">表示名</label>
            {{-- 3. 重要：nameを 'name' に変更 --}}
            <input
                type="text"
                id="username"
                name="name" 
                required
                value="{{ old('name', Auth::user()->name) }}"
            />

            {{-- 4. 任意：ログイン名（ID）の変更も必要なら追加 --}}
            <label for="login_name">ログインID</label>
            <input
                type="text"
                id="login_name"
                name="login_name"
                required
                value="{{ old('login_name', Auth::user()->login_name) }}"
            />

            <label for="current-password">現在のパスワード</label>
            <input
                type="password"
                id="current-password"
                name="current-password"
            />

            <label for="new-password">新しいパスワード</label>
            <input
                type="password"
                id="new-password"
                name="new-password"
            />

            <label for="confirm-password">新しいパスワード(確認用)</label>
            <input
                type="password"
                id="confirm-password"
                name="confirm-password"
            />

            <button type="button" onclick="location.href='{{ route('profile.2fa') }}'">二要素認証を設定</button>
            <div class="profile-edit-button-group">
                <button
                    class="profile-edit-button-cancel"
                    type="button"
                    onclick="location.href = '{{ route('profile') }}'"
                >
                    キャンセル
                </button>
                <button type="submit">
                    保存
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('avatar-img');
        const previewImage = document.getElementById('profile-preview');

        if (fileInput && previewImage) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                // 2MB limit (bytes)
                const LIMIT = 2 * 1024 * 1024;

                if (file.size <= LIMIT) {
                    // No compression needed, just preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    return;
                }

                // Compression needed
                // プレビューを一旦クリア等の処理を入れても良いが、ここではそのまま処理

                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function(event) {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        
                        // Resize logic (max dimension 1024px)
                        const MAX_WIDTH = 1024;
                        const MAX_HEIGHT = 1024;
                        let width = img.width;
                        let height = img.height;

                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width *= MAX_HEIGHT / height;
                                height = MAX_HEIGHT;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        // Compress to JPEG with 0.8 quality
                        canvas.toBlob(function(blob) {
                            if (!blob) {
                                alert('画像の圧縮に失敗しました。');
                                return;
                            }

                            if (blob.size > LIMIT) {
                                alert('画像サイズが大きすぎます。自動圧縮しても2MB以下になりませんでした。別の画像を選択してください。');
                                fileInput.value = ''; // Clear
                                return;
                            }

                            // Create new File object
                            const compressedFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            // Replace file input files
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(compressedFile);
                            fileInput.files = dataTransfer.files;

                            // Update preview with compressed image
                            previewImage.src = URL.createObjectURL(compressedFile);
                            
                            console.log('Image compressed:', file.size, '->', compressedFile.size);

                        }, 'image/jpeg', 0.8);
                    };
                };
            });
        }
    });
</script>