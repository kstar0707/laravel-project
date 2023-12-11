@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">本日のおすすめ</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblResidence">
                            <thead>
                                <tr>
                                    <th>ユーザーID</th>
                                    <th>ニックネーム</th>
                                    <th>おすすめ1 ユーザー ID</th>
                                    <th>おすすめ1 ニックネーム</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ユーザーID</td>
                                    <td>ニックネーム</td>
                                    <td>おすすめ1 ユーザー ID</td>
                                    <td>おすすめ1 ニックネーム</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm">編集</button>
                                        <button type="button" class="btn btn-danger btn-sm">削除</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection

