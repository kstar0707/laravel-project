<?php

namespace App\Http\Controllers;

use App\Models\PaidPlanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaidPlanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paidplantypes = PaidPlanType::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.paidplantype', ['paidplantypes' => $paidplantypes]);
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
        $token = request()->header('X-CSRF-TOKEN');
        $type = $request['type'];
        $price = $request['price'];

        $newDate = Carbon::now();

        $data = DB::table('paid_plan_type')->where('paid_type', $type)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        } else {
            $paidplantypes = new PaidPlanType();
            $paidplantypes->paid_type = $type;
            $paidplantypes->price = $price;
            $paidplantypes->created_at = $newDate;
            $paidplantypes->updated_at = $newDate;
            $paidplantypes->save();

            return response()->json(['result' =>"データ保存成功", "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaidPlanType  $paidPlanType
     * @return \Illuminate\Http\Response
     */
    public function show(PaidPlanType $paidPlanType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaidPlanType  $paidPlanType
     * @return \Illuminate\Http\Response
     */
    public function edit(PaidPlanType $paidPlanType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaidPlanType  $paidPlanType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaidPlanType $paidPlanType)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $type = $request['type'];
        $price = $request['price'];
        $id = $request['id'];

        $newDate = Carbon::now();

        $data = DB::table('paid_plan_type')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('paid_plan_type')->where('id', $id)->update([
                'paid_type' => $type,
                'price' => $price,
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
     * @param  \App\Models\PaidPlanType  $paidPlanType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PaidPlanType $paidPlanType)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];


        $data = DB::table('paid_plan_type')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('paid_plan_type')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
