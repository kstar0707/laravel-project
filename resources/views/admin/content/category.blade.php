@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">コミュニティカテゴリー</h2>
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small newCategoryModalBtn" style="margin-bottom: 20px" data-toggle="modal" data-target="#addCategoryModal">
                            <i class="zmdi zmdi-plus"></i>新規追加</button>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblCategory">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 60%">カテゴリー名</th>
                                    <th class="text-center" style="width: 10%">画像</th>
                                    <th class="text-center" style="width: 30%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="text-center">{{ $category->category_name }}</td>
                                        <td><img src="{{ asset('uploads/category/' . $category->category_image) }}" /></td>
                                        <td class="colAction">
                                            <button type="button" class="btn btn-success btn-sm updateCategory" data-id="{{ $category->id }}" data-val="{{ $category->category_name }}">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm removeCategory" data-id="{{ $category->id }}" data-val="{{ $category->category_name }}">削除</button>
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

    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="CategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form method="POST" enctype="multipart/form-data" id="categoryForm" action="javascript:void(0)" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CategoryModalLabel">追加</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edtFlag" />
                        <input type="hidden" id="edtCategoryId" name="edtCategoryId" />
                        <input type="text" class="form-control" id="edtCategoryName" name="category_name" />
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
                        <button type="submit" id="saveNewCategory" class="btn btn-primary">確認</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="CategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CategoryModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="categoryID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="removeCategoryConfirmBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

@include('admin.layout.footer');
