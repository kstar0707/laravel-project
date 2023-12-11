@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">利用目的</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newUsepurposeModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addUsepurposeModal">
                            <i class="zmdi zmdi-plus"></i>新規追加</button>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblUsepurpose">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70%">利用目的</th>
                                    <th class="text-center" style="width: 30%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usepurposes as $data)
                                    <tr>
                                        <td class="text-center">{{ $data->use_purpose }}</td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updateUsepurpose" data-id="{{ $data->id }}" data-val="{{ $data->use_purpose }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removeUsepurpose" data-id="{{ $data->id }}" data-val="{{ $data->use_purpose }}">削除</button>
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

    <div class="modal fade" id="addUsepurposeModal" tabindex="-1" role="dialog" aria-labelledby="UsepurposeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UsepurposeModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtUsepurposeId" />
                    <input type="text" class="form-control" id="edtUsepurposeName" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="saveNewUsepurpose" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="UsepurposeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UsepurposeModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="usepurposeID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeUsepurposeConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
