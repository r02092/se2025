@extends('layouts.app')

@section('title', '利用規約')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0 fw-bold">利用規約</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        本サービスをご利用いただく前に、以下の利用規約を必ずお読みください。
                    </p>

                    {{-- 規約本文エリア（編集する必要あり） --}}
                    <div class="terms-content border rounded p-3 mb-4" style="height: 400px; overflow-y: scroll; background-color: #f9f9f9;">
                        <h5 class="fw-bold">第1条（適用）</h5>
                        <p>
                            本規約は、ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。<br>
                            当社は本サービスに関し、本規約のほか、ご利用にあたってのルール等、各種の定め（以下「個別規定」といいます。）をすることがあります。これら個別規定はその名称のいかんに関わらず、本規約の一部を構成するものとします。
                        </p>

                        <h5 class="fw-bold">第2条（利用登録）</h5>
                        <p>
                            本サービスにおいては、登録希望者が本規約に同意の上、当社の定める方法によって利用登録を申請し、当社がこれを承認することによって、利用登録が完了するものとします。<br>
                            当社は、利用登録の申請者に以下の事由があると判断した場合、利用登録の申請を承認しないことがあり、その理由については一切の開示義務を負わないものとします。
                        </p>
                        <ul>
                            <li>利用登録の申請に際して虚偽の事項を届け出た場合</li>
                            <li>本規約に違反したことがある者からの申請である場合</li>
                            <li>その他、当社が利用登録を相当でないと判断した場合</li>
                        </ul>

                        <h5 class="fw-bold">第3条（ユーザーIDおよびパスワードの管理）</h5>
                        <p>
                            ユーザーは、自己の責任において、本サービスのユーザーIDおよびパスワードを適切に管理するものとします。<br>
                            ユーザーは、いかなる場合にも、ユーザーIDおよびパスワードを第三者に譲渡または貸与し、もしくは第三者と共用することはできません。
                        </p>

                        <h5 class="fw-bold">第4条（禁止事項）</h5>
                        <p>ユーザーは、本サービスの利用にあたり、以下の行為をしてはなりません。</p>
                        <ul>
                            <li>法令または公序良俗に違反する行為</li>
                            <li>犯罪行為に関連する行為</li>
                            <li>本サービスの内容等、本サービスに含まれる著作権、商標権ほか知的財産権を侵害する行為</li>
                            <li>当社、ほかのユーザー、またはその他第三者のサーバーまたはネットワークの機能を破壊したり、妨害したりする行為</li>
                            <li>本サービスによって得られた情報を商業的に利用する行為</li>
                        </ul>

                        <h5 class="fw-bold">第5条（本サービスの提供の停止等）</h5>
                        <p>
                            当社は、以下のいずれかの事由があると判断した場合、ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができるものとします。
                        </p>
                        <ul>
                            <li>本サービスにかかるコンピュータシステムの保守点検または更新を行う場合</li>
                            <li>地震、落雷、火災、停電または天災などの不可抗力により、本サービスの提供が困難となった場合</li>
                            <li>コンピュータまたは通信回線等が事故により停止した場合</li>
                        </ul>

                        {{-- 以下、必要に応じて条文を追加 --}}
                        <p class="mt-4 text-center text-muted">- 以上 -</p>
                    </div>

                    {{-- ボタンエリア --}}
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        {{-- 戻るボタン（前のページへ） --}}
                        <button type="button" class="btn btn-secondary px-5" onclick="window.close()">
                            閉じる
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection