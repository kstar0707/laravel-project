@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">居住地</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newResidenceModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addResidenceModal">
                            <i class="zmdi zmdi-plus"></i>新規追加</button>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblResidence">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70%">居住地</th>
                                    <th class="text-center" style="width: 30%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($residences as $residence)
                                    <tr>
                                        <td class="text-center">{{ $residence->residence }}</td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updateResidence" data-id="{{ $residence->id }}" data-val="{{ $residence->residence }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removeResidence" data-id="{{ $residence->id }}" data-val="{{ $residence->residence }}">削除</button>
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

    <div class="modal fade" id="addResidenceModal" tabindex="-1" role="dialog" aria-labelledby="ResidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ResidenceModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtResidenceId" />
                    <input type="text" class="form-control" id="edtResidenceName" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="saveNewResidence" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="ResidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ResidenceModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="residenceID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeResidenceConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
