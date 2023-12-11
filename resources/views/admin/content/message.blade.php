@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">メッセージ</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small sendMessageModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#sendMessageModal">
                            <i class="zmdi zmdi-plus"></i>新規追加</button>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table id="myTable" class="table table-borderless table-striped ">
                            <thead style="background: black">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">タイトル</th>
                                    <th class="text-center">コンテンツ</th>
                                    <th class="text-center">受信したユーザー</th>
                                    <th class="text-center">メッセージ送信日</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                $
                            @foreach($messages as $mmm => $message)
                                <tr>
                                    <td class="text-center">{{ $mmm +1 }}</td>
                                    <td class="text-center">{{ $message->title }}</td>
                                    <td class="text-center">{{ $message->content }}</td>
                                    <td class="text-center">{{ $message->received_name }}</td>
                                    <td class="text-center">{{ $message->created_at }}</td>
                                    <td class="colAction">
                                        <button type="button" class="btn btn-success btn-sm updateMessage" data-id="{{ $message->id }}" >編集</button>
                                        <button type="button" class="btn btn-danger btn-sm removeMessage" data-id="{{ $message->id }}" >削除</button>
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

    <div class="modal fade" id="sendMessageModal" role="dialog" aria-labelledby="SendMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SendMessageModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="received_by" />
                    <input type="hidden" id="edtFlag" />
                    <div class="row">
                        <div class="form-check-inline" style="margin-left: 15px;">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input sent-user all-user" name="optradio" data-val="0" checked>全ユーザー
                            </label>
                        </div>
                        <div class="form-check-inline" style="margin-left: 15px;">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input sent-user free-user" name="optradio" data-val="1">無料ユーザー
                            </label>
                        </div>
                        <div class="form-check-inline" style="margin-left: 15px;">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input sent-user paid-user" name="optradio" data-val="2">有料ユーザー
                            </label>
                        </div>
                        <div class="form-check-inline" style="margin-left: 15px;">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input sent-user person" name="optradio" data-val="3">個人ユーザー
                            </label>
                        </div>
                    </div>
                    <div class="row user-panel d-none mt-3" style="padding: 1rem;">
                        <select class="js-select2 form-control" id="received_user" style="width:500px;">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->user_nickname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-12">
                            <input type="text" class="form-control msg-title" id="title" placeholder="タイトルを入力してください。" />
                        </div>
                        <div class="col-12 col-md-12 mt-3">
                            <textarea class="form-control msg-content" rows="5" id="content" placeholder="内容を入力してください。" ></textarea>
                        </div>
                    </div>
                    <input type="hidden" id = "messageID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" id="sendMessageBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="ResidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MessageModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="MessageID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeMessageConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection
