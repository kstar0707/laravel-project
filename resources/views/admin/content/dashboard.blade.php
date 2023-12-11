@extends('admin.layout.admin')
@section('main-content')
    <!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action = "/dashboard" method="Get">
                    <div class="overview-wrap">
                        <h2 class="title-1">概要</h2>

                        <select class="form-control col-md-1" id="year" name="year" onchange="changeYear(this.value)">
                            <?php
                            $currentYear = date("Y"); // Get the current year
                            for ($i = 2020; $i <= 2030; $i++) {
                                if($selectedYear != "")
                                $selected = ($i == $selectedYear) ? 'selected' : '';
                                else $selected = ($i == $currentYear) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                        ?>
                        </select>
                    </form>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:5px">
                <div class="col-sm-12 col-lg-12">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                            <h2>総登録者数 : <?php echo $all_count->count;?>人</h2>
                            <canvas id="sales-chart" height="253" style="display: block; width: 507px; height: 253px;" width="507" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="au-card chart-percent-card">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">無料プランのユーザー数、有料プランのユーザー数</h3>
                            <div class="row no-gutters">
                                <div class="col-xl-6">
                                    <div class="chart-note-wrap">
                                        <div class="chart-note mr-0 d-block">
                                            <span class="dot dot--blue"></span>
                                            <span>有料プランのユーザー数</span>
                                        </div>
                                        <div class="chart-note mr-0 d-block">
                                            <span class="dot dot--red"></span>
                                            <span>無料プランのユーザー数</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="percent-chart">
                                        <canvas id="percent-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="au-card chart-percent-card">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Androidとiosユーザーの人数と割合</h3>
                            <div class="row no-gutters">
                                <div class="col-xl-6">
                                    <div class="chart-note-wrap">
                                        <div class="chart-note mr-0 d-block">
                                            <span class="dot dot--blue"></span>
                                            <span>Android</span>
                                        </div>
                                        <div class="chart-note mr-0 d-block">
                                            <span class="dot dot--red"></span>
                                            <span>ios</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="percent-chart">
                                        <canvas id="percent-chart1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="au-card recent-report">
                        <div class="au-card-inner">
                            <h3 class="title-2">居住地の集計</h3>
                            <div class="chart-info">
                                {{-- <div class="chart-info__left">
                                    <div class="chart-note">
                                        <span class="dot dot--blue"></span>
                                        <span>総ユーザー</span>
                                    </div>
                                    <div class="chart-note mr-0">
                                        <span class="dot dot--green"></span>
                                        <span>有料会員</span>
                                    </div>
                                </div> --}}
                                {{-- <div class="chart-info__right">
                                    <div class="chart-statis">
                                        <span class="index incre">
                                            <i class="zmdi zmdi-long-arrow-up"></i>25%</span>
                                        <span class="label">総ユーザー</span>
                                    </div>
                                    <div class="chart-statis mr-0">
                                        <span class="index decre">
                                            <i class="zmdi zmdi-long-arrow-down"></i>10%</span>
                                        <span class="label">有料会員</span>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="recent-report__chart">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $user_count = []; // Initialize an empty array
@endphp
@foreach ($month_count as $row)
    @php
        $user_count[] = $row->login_number; // Append each value to the $data array
    @endphp
@endforeach
@php
    $pay_count = []; // Initialize an empty array
@endphp
@foreach ($pay_user as $row)
    @php
        $pay_count[] += $row->yuro; // Append each value to the $data array
        $pay_count[] += $row->muro;
    @endphp
@endforeach
@php
    $login_device = []; // Initialize an empty array
@endphp
@foreach ($device as $row)
    @php
        $login_device[] += $row->android; // Append each value to the $data array
        $login_device[] += $row->ios;
    @endphp
@endforeach
<!-- END MAIN CONTENT-->
@php
    $residence_data = []; // Initialize an empty array
    $residence_count = [];
@endphp
@foreach ($residence_dt as $row)
    @php
        $residence_data[] = $row->residence;
        $residence_count[] += $row->residence_count;
    @endphp
@endforeach
@endsection

<!-- END MAIN CONTENT-->
<script>
    var residence_data = @json($residence_data);
    var residence_count = @json($residence_count);
    var login_device = @json($login_device);
    var user_count = @json($user_count);
    var pay_count = @json($pay_count);
</script>
@include('admin.layout.footer');
@push('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endpush

