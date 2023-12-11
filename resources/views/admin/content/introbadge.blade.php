@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">紹介バッジ</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newIntrobadgeModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addIntrobadgeModal">
                            <i class="zmdi zmdi-plus"></i>新規追加</button>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblIntrobadge">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40%;">タグのテキスト</th>
                                    <th class="text-center" style="width: 40%;">タグの色</th>
                                    <th class="text-center" style="width: 20%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($introbadges as $introbadge)
                                    <tr>
                                        <td class="text-center">{{ $introbadge->tag_text }}</td>
                                        <td class="text-center"><div style="background-color: {{ $introbadge->tag_color }}; padding: 1rem;"></div></td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updateIntrobadge" data-id="{{ $introbadge->id }}" data-text="{{ $introbadge->tag_text }}" data-color="{{ $introbadge->tag_color }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removeIntrobadge" data-id="{{ $introbadge->id }}" data-text="{{ $introbadge->tag_text }}" data-color="{{ $introbadge->tag_color }}">削除</button>
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

    <div class="modal fade" id="addIntrobadgeModal" tabindex="-1" role="dialog" aria-labelledby="IntrobadgeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="IntrobadgeModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtIntrobadgeId" />
                    <div>
                        <label for="edtIntrobadgeName">タグのテキスト</label>
                        <input type="text" class="form-control" id="edtIntrobadgeName" />
                    </div>
                    <div>
                        <label for="edtIntrobadgeColor">タグの色</label>
                        <input type="color" class="form-control mt-2" id="edtIntrobadgeColor" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="saveNewIntrobadge" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="IntrobadgeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="IntrobadgeModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="IntrobadgeID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeIntrobadgeConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
