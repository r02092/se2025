@extends('layouts.app')

@section('title', '二要素認証')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">二要素認証</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        認証アプリ（Google Authenticator等）に表示されている6桁のコードを入力してください。
                    </p>

                    <form action="{{ route('2fa.verify') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="one_time_password" class="form-label fw-bold">認証コード</label>
                            <input type="text" 
                                   id="one_time_password" 
                                   name="one_time_password" 
                                   class="form-control form-control-lg text-center letter-spacing-2 @error('one_time_password') is-invalid @enderror" 
                                   placeholder="000000" 
                                   maxlength="6" 
                                   inputmode="numeric" 
                                   autofocus
                                   required>
                            
                            @error('one_time_password')
                                <div class="invalid-feedback text-center">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">認証する</button>
                        </div>
                    </form>
                    
                    <hr class="mt-4">
                    <div class="text-center">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-secondary text-decoration-none">
                                キャンセルしてログアウト
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 入力文字の間隔を広げて見やすくするCSS */
    .letter-spacing-2 {
        letter-spacing: 0.2em;
        font-size: 1.5rem;
    }
</style>
@endsection