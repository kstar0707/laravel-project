<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ResidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $residences = Residence::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.residence', ['residences' => $residences]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    // Send a listing of the resource
    public function getResidence() {
        $residence = Residence::select("id", "residence")->get();
        return response()->json(['data' => $residence, "type" => "success"]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __invoke(Request $request)
    {
        // Your code logic goes here
    }
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

        $data = DB::table('residence')->where('residence', $newData)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        } else {
            $residence = new Residence();
            $residence->residence = $newData;
            $residence->created_at = $newDate;
            $residence->updated_at = $newDate;
            $residence->save();

            return response()->json(['result' =>"データ保存成功", "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function show(Residence $residence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function edit(Residence $residence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Residence $residence)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $name = $request['name'];
        $id = $request['id'];

        $data = DB::table('residence')->where('residence', $name)->get();
        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        }

        $newDate = Carbon::now();
        $data = DB::table('residence')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('residence')->where('id',$id)->update([
                'residence' => $name,
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
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Residence $residence)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('residence')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('residence')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
