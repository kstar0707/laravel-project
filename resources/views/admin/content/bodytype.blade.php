@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">体型</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newBodytypeModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addBodytypeModal">
                            <i class="zmdi zmdi-plus"></i>新規追加</button>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblBodytype">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70%">体型</th>
                                    <th class="text-center" style="width: 30%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bodytypes as $bodytype)
                                    <tr>
                                        <td class="text-center">{{ $bodytype->type_name }}</td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updatebodytype" data-id="{{ $bodytype->id }}" data-val="{{ $bodytype->type_name }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removebodytype" data-id="{{ $bodytype->id }}" data-val="{{ $bodytype->type_name }}">削除</button>
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

    <div class="modal fade" id="addBodytypeModal" tabindex="-1" role="dialog" aria-labelledby="BodytypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BodytypeModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtBodytypeId" />
                    <input type="text" class="form-control" id="edtBodytypeName" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="saveNewBodytype" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="ResidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bodytypeModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="bodytypeID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeBodytypeConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
