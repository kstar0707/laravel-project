@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">違反報告</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table id="myTable" class="table table-borderless table-striped tblResidence">
                            <thead style="background-color: black">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">申告者</th>
                                    <th class="text-center">報告されたユーザー</th>
                                    <th class="text-center">報告日</th>
                                    <th class="text-center">違反内容</th>
                                    {{-- <th class="text-center"></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($violationreports as $item => $violationreport)
                                    <tr>
                                        <td class="text-center">{{ $item +1 }}</td>
                                        <td class="text-center">{{ $violationreport->res_nickname }}</td>
                                        <td class="text-center">{{ $violationreport->user_nickname }}</td>
                                        <td class="text-center">{{ $violationreport->created_at }}</td>
                                        <td class="text-center">{{$violationreport->user_nickname}}から{{$violationreport->res_nickname}}がポリシーに違反しているという通知が来ました。</td>
                                        {{-- <td class="text-center">
                                            <button type="button" class="btn btn-success btn-sm">無視</button>
                                            <button type="button" class="btn btn-danger btn-sm" >アカウント停止</button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ViolationModal" tabindex="-1" role="dialog" aria-labelledby="ViolationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ViolationModalLabel">居住地を追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label for="violationID">Violation ID : </label>
                            <input type="text" class="form-control" id="violationID" />
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="userID">User ID : </label>
                            <input type="text" class="form-control" id="userID" />
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="userNickname">User NiceName : </label>
                            <input type="text" class="form-control" id="userNickname" />
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="violationDate"> : </label>
                            <input type="text" class="form-control" id="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <label for="violationContent"></label>
                            <textarea rows="5" class="form-control" id="violationContent"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="ViolationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ViolationModalLabel">delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="ViolationID" />
                    <p>remove data</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeViolationConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
