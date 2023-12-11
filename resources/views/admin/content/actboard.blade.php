@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">自分ボード</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table id="myTable" class="table table-borderless table-striped">
                            <thead style="background-color: black">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">ユーザーID</th>
                                    <th class="text-center">お名前</th>
                                    <th class="text-center">作成日</th>
                                    <th class="text-center">内容</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($actboard as  $item => $row)
                                <tr>
                                    <td class="text-center">{{ $item +1 }}</td>
                                    <td class="text-center">{{ $row->id }}</td>
                                    <td class="text-center">{{ $row->user_nickname }}</td>
                                    <td class="text-center">{{ $row->created_at }}</td>
                                    <td class="text-center">{{ $row->board_content }}</td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-success btn-sm" >編集</button> --}}
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeBoardModal({{$row->id}})">削除</button>
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
</div>
<div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="CustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CustomerModalLabel">削除</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="CustomerID" />
                <p>データを削除してもよろしいですか?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="removeCustomerConfirmBtnWin()">確認</button>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
@endsection
<script>
    function removeBoardModal(id) {
        $("#removeConfirmModal").modal("show");
        $("#CustomerID").val(id);
    }

    function removeCustomerConfirmBtnWin(){
        var id = $("#CustomerID").val();

        $.ajax({
            url: "/remove_actboard",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id : id},
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            success: function(response) {
                toastr[response['type']](response['result']);
                setTimeout(() => {
                    location.href = "/actboard";
                }, 1500);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    };
</script>

