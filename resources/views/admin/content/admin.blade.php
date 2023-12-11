@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<?php

?>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">マネージャー</h2>
                        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" id="admin_modal">
                            <i class="zmdi zmdi-plus"></i>管理者追加</button>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 1%">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table id="myTable" class="table table-borderless table-striped ">
                            <thead style="background-color: black">
                                <tr>
                                    {{-- <th class="text-center">お名前</th> --}}
                                    {{-- <th class="text-center">メイル</th> --}}
                                    <th class="text-center">ID</th>
                                    <th class="text-center">管理者名</th>
                                    <th class="text-center">管理メール</th>
                                    <th class="text-center" style="width:10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($manager as  $item => $row)
                                    <tr>
                                        {{-- <td class="text-center">{{ $customer->user_name }}</td> --}}
                                        {{-- <td class="text-center">{{ $customer->email }}</td> --}}
                                        <td class="text-center">{{ $item +1 }}</td>
                                        <td class="text-center">{{ $row->name }}</td>
                                        <td class="text-center">{{ $row->email }} </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-sm" onclick="updateAdmin({{$row->id}})">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeAdmin({{$row->id}})">削除</button>
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

    <div class="modal fade" id="removeAdminModal" tabindex="-1" role="dialog" aria-labelledby="CustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CustomerModalLabel">削除</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="adminID" />
                    <p>データを削除してもよろしいですか?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="removeAdminData()">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title" id="mediumModalLabel">追加/編集</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
                    <form method="POST" enctype="multipart/form-data" id="file-upload" >
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            管理者名:
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="name">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            管理メール:
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="email">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            パスワード:
                        </div>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="password">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger" placehold="秘密パスワードの初期化" data-toggle="modal" data-target="#passFormat">初期化</button>
                        </div>
                    </div>
                    <input type="hidden" id="uid">
                </div>
			</div>
            </form>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
				<button type="button" class="btn btn-primary" onclick="adminSaveData()">提出する</button>
			</div>
		</div>
	</div>
</div>
<!-- modal medium -->
<div class="modal fade" id="passFormat" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mediumModalLabel">お知らせ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <p>ワードをリセットしてもよろしいですか?
                    初期化パスワード (123456789)</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
				<button type="button" class="btn btn-primary" onclick="passFormat();">確認</button>
			</div>
		</div>
	</div>
</div>

<!-- END MAIN CONTENT-->
@endsection
@include('admin.layout.footer');
<script src="<?= asset("assets/customer/customer.js") ?>"></script>
