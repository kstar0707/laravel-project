@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">コミュニティ</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newCommunityModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addCommunityModal">
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
                                    <th class="text-center" style="width: 30%">タイトル</th>
                                    <th class="text-center" style="width: 30%">カテゴリー</th>
                                    <th class="text-center" style="width: 20%">画像</th>
                                    <th class="text-center" style="width: 20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($communities as $community)
                                    <tr>
                                        <td class="text-center">{{ $community->community_name }}</td>
                                        <td class="text-center">{{ $community->category_name }}</td>
                                        <td><img src="{{ asset('uploads/' . $community->community_photo) }}" /></td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updateCommunity" data-id="{{ $community->id }}" data-name="{{ $community->category_name }}" data-category="{{ $community->community_category }}" data-image="{{ asset('uploads/' . $community->community_photo) }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removeCommunity" data-id="{{ $community->id }}" data-name="{{ $community->category_name }}" data-category="{{ $community->community_category }}" data-image="{{ asset('uploads/' . $community->community_photo) }}">削除</button>
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

    <div class="modal fade" id="addCommunityModal" tabindex="-1" role="dialog" aria-labelledby="CommunityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" id="image-upload" action="javascript:void(0)" >
                <div class="modal-header">
                    <h5 class="modal-title" id="CommunityModalLabel">追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edtFlag" />
                    <input type="hidden" id="edtCommunityId" name="edtCommunityId" />
                    <div>
                        <label for="edtCommunityName">タイトル</label>
                        <input type="text" class="form-control" id="edtCommunityName" name="edtCommunityName" />
                    </div>
                    <div>
                        <label for="edtCommunityCategory">カテゴリー</label>
                        <select class="form-control" id="edtCommunityCategory" name="edtCommunityCategory" >
                            <option value="">コミュニティカテゴリー選択してください</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="panel">
                            <div class="button_outer">
                                <div class="btn_upload">
                                    <input type="file" id="image" name="image">
                                    画像をアップロードする
                                </div>
                                <div class="processing_bar"></div>
                                <div class="success_box"></div>
                            </div>
                        </div>
                        <div class="error_msg"></div>
                        <div class="uploaded_file_view" id="uploaded_view">
                            <span class="file_remove">X</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" id="saveNewCommunity" class="btn btn-primary">確認</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="CommunityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CommunityModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="CommunityID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeCommunityConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
