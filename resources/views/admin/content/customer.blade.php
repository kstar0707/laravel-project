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
                        <h2 class="title-1">ユーザー</h2>
                        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" id="modal_show">
                            <i class="zmdi zmdi-plus"></i>ユーザー追加</button>
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
                                    <th class="text-center">ニックネーム</th>
                                    <th class="text-center">生年月日</th>
                                    <th class="text-center">居住地</th>
                                    <th class="text-center">コミュニティ</th>
                                    <th class="text-center">身長</th>
                                    <th class="text-center">体型</th>
                                    <th class="text-center">利用目的</th>
                                    <th class="text-center" style="width:5%">紹介バッジ</th>
                                    {{-- <th class="text-center">ユーザー写真</th>
                                    <th class="text-center">自己紹介</th> --}}
                                    <th class="text-center">プランタイプ</th>
                                    <th class="text-center">いいね数</th>
                                    <th class="text-center">coin数</th>
                                    <th class="text-center">本人確認状態</th>
                                    <th class="text-center" style="width:5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as  $item => $customer)
                                    <?php
                                        $birthday = $customer->birthday;
                                        $formattedBirthday = date('Y-m-d', strtotime($birthday));
                                        $height = $customer->height;
                                        $formatheight = (explode(".",$height));
                                        $identity = $customer->identity_state;
                                        $formatIdentity = "";
                                        if($identity == "ブロック"){
                                            $formatIdentity = "待機中";
                                        }else if($identity == "承認"){
                                            $formatIdentity = "承認";
                                        }else if($identity == "block"){
                                            $formatIdentity = "ブロック";
                                        }else if($identity == "waiting"){
                                            $formatIdentity = "申請前";
                                        }
                                    ?>
                                    <tr>
                                        {{-- <td class="text-center">{{ $customer->user_name }}</td> --}}
                                        {{-- <td class="text-center">{{ $customer->email }}</td> --}}
                                        <td class="text-center">{{ $item +1 }}</td>
                                        <td class="text-center">{{ $customer->user_nickname }}</td>
                                        <td class="text-center">{{ $formattedBirthday}} </td>
                                        <td class="text-center">{{ $customer->residence }}</td>
                                        <td class="text-center">
                                            @foreach($customer->community as $community)
                                                <div class="community-card">
                                                    <p>{{ $community->community_name }}</p>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ $formatheight[0]."cm" }}</td>
                                        <td class="text-center">{{ $customer->type_name }}</td>
                                        <td class="text-center">{{ $customer->use_purpose }}</td>
                                        <td class="text-center">
                                            @foreach($customer->intro_badge as $badge)
                                                <span class="badge" style="background-color: {{ $badge->tag_color }}; color: white;">{{ $badge->tag_text }}</span>
                                            @endforeach
                                        </td>
                                        {{-- <td class="text-center"><img src="{{ $customer->photo }}" /></td>
                                        <td class="text-center">{{ $customer->introduce }}</td> --}}
                                        <td class="text-center">{{ $customer->pay_user?"有料プラン":"無料プラン" }}</td>
                                        <td class="text-center">{{ $customer->likes_rate }}</td>
                                        <td class="text-center">{{ $customer->coin }}</td>
                                        <td class="text-center">{{ $formatIdentity }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-sm" onclick="updateCustomer({{ $customer->id }})">編集</button>
                                            <button type="button" class="btn btn-danger btn-sm" id="removeCustomerWin" data-id="{{ $customer->id }}">削除</button>
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
                    <button type="button" id="removeCustomerConfirmBtnWin" class="btn btn-primary">確認</button>
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
                    {{-- <div class="row" id= "login_info1">
                        <div class="col-md-3">
                            加入方法:
                        </div>
                        <div class="col-md-9">
                            <label>
                                <input type="radio" id="apple_id" name="fav_language" value="apple_id" checked>
                                <span class="badge">Appleログイン</span>
                            </label>&nbsp;&nbsp;
                            <label>
                                <input type="radio" id="phone_id" name="fav_language" value="phone_id">
                                <span class="badge">Phoneログイン</span>
                            </label>
                        </div>
                    </div> --}}
                    <div class="row" style="margin-top:10px"  id= "login_info2">
                        <div class="col-md-3">
                            電話番号:
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="login_id">
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            ニックネーム:
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="nickname">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            生年月日:
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" id="birthday">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            居住地:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="address">
                                <option value="0">値を選択</option>
                                @foreach ($residences as $address)
                                    <option value="{{ $address['id'] }}">{{ $address['residence'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            コミュニティ:
                        </div>
                        <div class="col-md-9">
                            @foreach ($communities as $key => $community)
                                <label>
                                    <input type="checkbox" class="community_check" data-id="{{ $community['id'] }}">
                                    <span class="badge">{{ $community['community_name'] }}</span>
                                </label>&nbsp;&nbsp;
                            @endforeach
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            身長:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="height">
                                <option value="0">値を選択</option>
                                @for ($i = 130; $i < 211; $i++)
                                    <option value="{{ $i }}">{{$i . "cm"}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            体型:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="bodytype">
                                <option value="0">値を選択</option>
                                @foreach ($bodytypes as $bodytype)
                                    <option value="{{ $bodytype['id'] }}">{{ $bodytype['type_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            利用目的:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="use_purpose">
                                <option value="0">値を選択</option>
                                @foreach ($usepurposes as $use_purpose)
                                    <option value="{{ $use_purpose['id'] }}">{{ $use_purpose['use_purpose'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            紹介バッジ:
                        </div>
                        <div class="col-md-9">
                            @foreach ($introbadges as $key => $introbadge)
                                <label>
                                    <input type="checkbox" class="badge_check" data-id="{{ $introbadge['id'] }}">
                                    <span class="badge">{{ $introbadge['tag_text'] }}</span>
                                </label>&nbsp;&nbsp;
                            @endforeach
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            プランタイプ:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="pay_user" onchange="playType(this.value)">
                                <option value="-1">値を選択</option>
                                <option value="0">無料プラン</option>
                                <option value="1">有料プラン</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px; display:none" id ="hidden_card" >
                        <div class="col-md-3">
                            有料日付:
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" id="pay_date">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            いいね数:
                        </div>
                        <div class="col-md-9">
                            <input type="number" class="form-control" id="like_rate" placeholder="0">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            coin数:
                        </div>
                        <div class="col-md-9">
                            <input type="number" class="form-control" id="coin" placeholder="0">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            血液型:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="blood_type">
                                <option value="">値を選択</option>
                                <option value="A型">A型</option>
                                <option value="B型">B型</option>
                                <option value="O型">O型</option>
                                <option value="AB型">AB型</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            学齢:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="education">
                                <option value="">値を選択</option>
                                <option value="高校卒">高校卒</option>
                                <option value="専門/高専卒">専門/高専卒</option>
                                <option value="大学卒">大学卒</option>
                                <option value="大学院卒">大学院卒</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            お酒:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="alchol">
                                <option value="">値を選択</option>
                                <option value="選択しない">選択しない</option>
                                <option value="飲む">飲む</option>
                                <option value="たまに">たまに</option>
                                <option value="飲まない">飲まない</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            たばこ:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="ciga">
                                <option value="">値を選択</option>
                                <option value="選択しない">選択しない</option>
                                <option value="吸う">吸う</option>
                                <option value="たまに">たまに</option>
                                <option value="飲まない">飲まない</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            年収:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="annual_income">
                                <option value="">値を選択</option>
                                <option value="200万円未満">200万円未満</option>
                                <option value="200万円～400万円">200万円～400万円</option>
                                <option value="400万円～600万円">400万円～600万円</option>
                                <option value="600万円から800万円">600万円から800万円</option>
                                <option value="800万円以上">800万円以上</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            プロフィール写真:
                        </div>
                        <div class="col-md-9">
                            <img class="align-self-center rounded-circle mr-3" style="width: 85px; height: 85px;" alt="" id="preview-selected-image">
                            <input type="file" id="fileInput" accept="image/*" onchange="previewImage(event);" />

                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-3">
                            本人確認状態:
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="identity">
                                <option value="">値を選択</option>
                                <option value="-1">申請前</option>
                                <option value="0">待機中</option>
                                <option value="1">承認</option>
                                <option value="2">否認</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="uid">
                    <input type="hidden" id="edittype">
                </div>
			</div>
            </form>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
				<button type="button" class="btn btn-primary" onclick="saveData()">提出する</button>
			</div>
		</div>
	</div>
</div>
<!-- END MAIN CONTENT-->
@endsection
@include('admin.layout.footer');
<script src="<?= asset("assets/customer/customer.js") ?>"></script>
