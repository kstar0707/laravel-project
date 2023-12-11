@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">ユーザー</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning tblResidence">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Nice Name</th>
                                    <th>Birthday</th>
                                    <th>Address</th>
                                    <th>Community</th>
                                    <th>Height</th>
                                    <th>Body type</th>
                                    <th>User Purpose</th>
                                    <th>Intro Badge</th>
                                    <th>User photo</th>
                                    <th>Introduce</th>
                                    <th>Plan Type</th>
                                    <th>Number of Likes</th>
                                    <th>Coin Amount</th>
                                    <th>Identity Verify</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>21</td>
                                    <td>User Name</td>
                                    <td>Email</td>
                                    <td>Nice Name</td>
                                    <td>Birtdday</td>
                                    <td>Address</td>
                                    <td>Community</td>
                                    <td>Height</td>
                                    <td>Body type</td>
                                    <td>User Purpose</td>
                                    <td>Intro Badge</td>
                                    <td>User photo</td>
                                    <td>Introduce</td>
                                    <td>Plan Type</td>
                                    <td>Number of Likes</td>
                                    <td>Coin Amount</td>
                                    <td>Identity Verify</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm">Remove</button>
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

