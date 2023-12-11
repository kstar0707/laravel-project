@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">本人確認</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table id="myTable" class="table table-borderless table-striped ">
                            <thead style="background: black">
                                <tr>
                                    <th class="text-center">id</th>
                                    <th class="text-center">ニックネーム</th>
                                    <th class="text-center">申請日</th>
                                    <th class="text-center">認証タイプ</th>
                                    <th class="text-center">認証写真</th>
                                    <th class="text-center">認証状態</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($identifydatas as $key => $identifydata)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td class="text-center">{{ $identifydata->nick_name }}</td>
                                    <td class="text-center">{{ $identifydata->request_date }}</td>
                                    <td class="text-center">{{ $identifydata->identity_type }}</td>
                                    <td class="text-center"><img style="width:100px; height:100px;" src="{{asset('uploads/'.$identifydata->identity_photo)}}" /></td>
                                    <td class="text-center">{{ $identifydata->identity_state == "block" ? "遮断" : "保留中" }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm viewIndentifyUser" data-id="{{ $identifydata->user_id }}">表示</button>
                                        <button type="button" class="btn btn-success btn-sm activeIndentify" data-id="{{ $identifydata->user_id }}" data-type="allow">承認</button>
                                        <button type="button" class="btn btn-danger btn-sm activeIndentify" data-id="{{ $identifydata->user_id }}" data-type="block">ブロック</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="CustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" id="customer-data" action="javascript:void(0)" >
                <div class="modal-header">
                    <h5 class="modal-title" id="CustomerModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtCustomerId" />
                    <div class="row pb-2">
                        <div class="col-md-4 col-12">
                            <label for="edtUserName">お名前</label>
                            <input type="text" class="form-control" readonly="true" id="edtUserName" name="edtUserName" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtNickName1">ニックネーム</label>
                            <input type="text" class="form-control" readonly="true" id="edtNickName1" name="edtNickName1" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtBirthday">生年月日</label>
                            <input type="text" class="form-control" readonly="true" id="edtBirthday" name="edtIdentifyState" />
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col-md-4 col-12">
                            <label for="editAge">年齢</label>
                            <input type="text" class="form-control" readonly="true" id="editAge" name="editAge" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtAddress">居住地</label>
                            <input class="form-control" readonly="true" id="edtAddress" name="edtAddress" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtHeight">身長</label>
                            <input type="number" class="form-control" readonly="true" id="edtHeight" name="edtHeight" />
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col-md-4 col-12">
                            <label for="edtBodyType">体型</label>
                            <input class="form-control" readonly="true" id="edtBodyType" name="edtBodyType" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtUsePurpose">利用目的</label>
                            <input class="form-control" readonly="true" id="edtUsePurpose" name="edtUsePurpose" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtIntroBadge">紹介バッジ</label>
                            <input type="text" class="form-control" readonly="true" id="edtIntroBadge" name="edtIntroBadge" />
                        </div>
                    </div>
                    <div class="row additional d-none pb-2">
                        <div class="col-md-4 col-12">
                            <label for="edtPlanType">プランタイプ</label>
                            <input class="form-control" readonly="true" id="edtPlanType" name="edtPlanType" >
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtLikesRate">いいね数</label>
                            <input type="text" class="form-control" readonly="true" id="edtLikesRate" name="edtLikesRate" />
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="edtCoin">coin数</label>
                            <input type="number" class="form-control" readonly="true" id="edtCoin" name="edtCoin" />
                        </div>
                    </div>
                    <div class="row additional d-none pb-2">
                        <div class="col-md-4 col-12">
                            <label for="edtCommunity">コミュニティ</label>
                            <input type="text" class="form-control" readonly="true" id="edtCommunity" name="edtCommunity" />
                        </div>
                        <div class="col-md-8 col-12">
                            <label for="edtIdentifyState">認証タイプ</label>
                            <input type="text" class="form-control" readonly="true" id="edtIdentifyState" name="edtIdentifyState" />
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col-md-6 col-12">
                            <label for="edtPhoto">ユーザー写真</label><br />
                            <img src="" id="edtUserPhoto" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="edtPhoto">認証写真</label>
                            <img src="" id="edtIdentifyPhoto" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {{-- <button type="submit" id="saveNewCustomer" class="btn btn-primary">確認</button> --}}
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

