@extends('layouts.app')

@section('title', '二要素認証設定')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">二要素認証設定</h4>
                </div>
                <div class="card-body">
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($enabled)
                        {{-- ▼ 設定済みの場合 ▼ --}}
                        <div class="text-center py-4">
                            <div class="mb-3 text-success">
                                <i class="bi bi-shield-check" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold">二要素認証は有効です</h5>
                            <p class="text-muted">アカウントのセキュリティは強化されています。</p>
                            
                            <hr class="my-4">
                            
                            <form action="{{ route('profile.2fa.destroy') }}" method="POST" onsubmit="return confirm('セキュリティレベルが下がりますが、本当に解除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    設定を解除する
                                </button>
                            </form>
                        </div>

                    @else
                        {{-- ▼ 未設定（これから設定）の場合 ▼ --}}
                        <div class="row">
                            <div class="col-md-6 text-center border-end">
                                <h5 class="fw-bold mb-3">Step 1</h5>
                                <p class="small text-muted">認証アプリでQRコードを読み取ってください。</p>
                                
                                <div class="my-4">
                                    {{-- QRコード表示 (SVG) --}}
                                    {!! $qrImage !!}
                                </div>
                                
                                <p class="small text-muted mb-1">読み取れない場合の手動入力キー:</p>
                                <code class="bg-light px-2 py-1 user-select-all">{{ $secretKey }}</code>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Step 2</h5>
                                <p class="small text-muted">アプリに表示された6桁のコードを入力して有効化してください。</p>

                                <form action="{{ route('profile.2fa.store') }}" method="POST">
                                    @csrf
                                    {{-- 秘密鍵をhiddenで送る --}}
                                    <input type="hidden" name="secret_key" value="{{ $secretKey }}">

                                    <div class="mb-3">
                                        <label for="code" class="form-label">認証コード</label>
                                        <input type="text" name="one_time_password" id="code" 
                                               class="form-control form-control-lg text-center @error('one_time_password') is-invalid @enderror" 
                                               placeholder="000000" maxlength="6" inputmode="numeric" required>
                                        @error('one_time_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            有効にする
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('profile') }}" class="text-decoration-none">プロフィールに戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection