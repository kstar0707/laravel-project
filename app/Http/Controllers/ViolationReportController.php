<?php

namespace App\Http\Controllers;

use App\Models\ViolationReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ViolationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $violationreports = DB::select("SELECT vr.id AS report_id, c1.user_nickname AS res_nickname, c2.user_nickname AS user_nickname, vr.created_at
        FROM violation_report vr
        JOIN customer c1 ON vr.violation_id = c1.id
        JOIN customer c2 ON vr.user_id = c2.id");
          if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.violationreport', ['violationreports' => $violationreports]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViolationReport  $violationReport
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ViolationReport $violationReport)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('violation_report')->where('id', $id)->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ViolationReport  $violationReport
     * @return \Illuminate\Http\Response
     */
    public function edit(ViolationReport $violationReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViolationReport  $violationReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ViolationReport $violationReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViolationReport  $violationReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ViolationReport $violationReport)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('violation_report')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('violation_report')->where('id', $id)->delete();
            return response()->json(['result' =>"データの削除に成功しました", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
