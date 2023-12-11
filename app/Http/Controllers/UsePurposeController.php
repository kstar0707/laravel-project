<?php

namespace App\Http\Controllers;

use App\Models\UsePurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UsePurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usepurposes = UsePurpose::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.usepurpose', ['usepurposes' => $usepurposes]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    // Send listing of the Use purpose
    public function getUsepurpose() {
        $usepurposes = UsePurpose::select("id", "use_purpose")->get();
        return response()->json(['data' => $usepurposes, "type" => "success"]);
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
        $token = request()->header('X-CSRF-TOKEN');
        $newData = $request['data'];

        $newDate = Carbon::now();

        $data = DB::table('use_purpose')->where('use_purpose', $newData)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        } else {
            $usepurposes = new UsePurpose();
            $usepurposes->use_purpose = $newData;
            $usepurposes->created_at = $newDate;
            $usepurposes->updated_at = $newDate;
            $usepurposes->save();

            return response()->json(['result' =>"データ保存成功", "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UsePurpose  $usePurpose
     * @return \Illuminate\Http\Response
     */
    public function show(UsePurpose $usePurpose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UsePurpose  $usePurpose
     * @return \Illuminate\Http\Response
     */
    public function edit(UsePurpose $usePurpose)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsePurpose  $usePurpose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsePurpose $usePurpose)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $name = $request['type'];
        $id = $request['id'];

        $newDate = Carbon::now();

        $data = DB::table('use_purpose')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('use_purpose')->where('id', $id)->update([
                'use_purpose' => $name,
                'updated_at' => $newDate,
            ]);

            return response()->json(['result' =>"データが更新されました", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsePurpose  $usePurpose
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, UsePurpose $usePurpose)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];


        $data = DB::table('use_purpose')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('use_purpose')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
