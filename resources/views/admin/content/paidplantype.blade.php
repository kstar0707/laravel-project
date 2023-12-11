@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">有料プランタイプ</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newPaidPlanTypeModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addPaidPlanTypeModal">
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
                                    <th class="text-center" style="width: 40%;">タイプ</th>
                                    <th class="text-center" style="width: 40%;">タイプ別金額</th>
                                    <th class="text-center" style="width: 20%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paidplantypes as $paidplantype)
                                    <tr>
                                        <td class="text-center">{{ $paidplantype->paid_type }}</td>
                                        <td class="text-center">{{ $paidplantype->price }}</td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updatePaidPlanType" data-id="{{ $paidplantype->id }}" data-type="{{ $paidplantype->paid_type }}" data-price="{{ $paidplantype->price }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removePaidPlanType" data-id="{{ $paidplantype->id }}" data-type="{{ $paidplantype->paid_type }}" data-price="{{ $paidplantype->price }}">削除</button>
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

    <div class="modal fade" id="addPaidPlanTypeModal" tabindex="-1" role="dialog" aria-labelledby="PaidPlanTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PaidPlanTypeModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtPaidPlanTypeId" />
                    <div>
                        <label for="edtPaidPlanTypeName">タイプ</label>
                        <input type="text" class="form-control" id="edtPaidPlanTypeName" />
                    </div>
                    <div>
                        <label for="edtPaidPlanTypePrice">タイプ別金額</label>
                        <input type="number" class="form-control mt-2" id="edtPaidPlanTypePrice" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="saveNewPaidPlanType" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="PaidPlanTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PaidPlanTypeModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="PaidPlanTypeID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removePaidPlanTypeConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
