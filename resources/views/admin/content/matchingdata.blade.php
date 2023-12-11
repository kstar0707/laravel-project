@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">マッチング</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblResidence">
                            <thead>
                                <tr>
                                    <th>提案ユーザー</th>
                                    <th>提案日付</th>
                                    <th>承諾ユーザーニックネーム</th>
                                    <th>承諾日</th>
                                    <th>受信状態</th>
                                    <th>提案状態</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matchingdatas as $matchingdata)
                                    <tr>
                                        <td>{{ $matchingdata->proposed_user_nickname }}</td>
                                        <td>{{ $matchingdata->proposed_date }}</td>
                                        <td>{{ $matchingdata->accepted_user_nickname }}</td>
                                        <td>{{ $matchingdata->accepted_date }}</td>
                                        <td>{{ $matchingdata->receiving_message_state }}</td>
                                        <td>{{ $matchingdata->proposal_state }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm viewMatchingData" data-id="{{ $matchingdata->id }}">見晴らし</button>
                                            <button type="button" class="btn btn-danger btn-sm removeMatchingData" data-id="{{ $matchingdata->id }}">削除</button>
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

    <div class="modal fade" id="MatchingDataModal" tabindex="-1" role="dialog" aria-labelledby="MatchingDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MatchingDataModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="proposedUserId">提案されたユーザーID : </label>
                            <input type="text" class="form-control" id="proposedUserId" />    
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="proposedUserNickname">提案されたユーザーのニックネーム : </label>
                            <input type="text" class="form-control" id="proposedUserNickname" />    
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="proposedDate">提案された日付 : </label>
                            <input type="text" class="form-control" id="proposedDate" />    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="acceptedUserId">受け入れられたユーザーID : </label>
                            <input type="text" class="form-control" id="acceptedUserId" />    
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="acceptedUserNickname">受け入れられるユーザーニックネーム : </label>
                            <input type="text" class="form-control" id="acceptedUserNickname" />    
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="acceptedDate">受理日 : </label>
                            <input type="text" class="form-control" id="acceptedDate" />    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="receivingMsgState">メッセージ受信状態 : </label>
                            <input type="text" class="form-control" id="receivingMsgState" />    
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="proposalState">提案の状態 : </label>
                            <input type="text" class="form-control" id="proposalState" />    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="MatchingDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MatchingDataModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="MatchingDataID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeMatchingDataConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
