<?php

namespace App\Http\Controllers;

use App\Models\Bodytype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BodytypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bodytypes = Bodytype::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.bodytype', ['bodytypes' => $bodytypes]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    // Send listing of the Body type
    public function getBodytype() {
        $bodytypes = BodyType::select("id", "type_name")->get();
        return response()->json(['data' => $bodytypes, "type" => "success"]);
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

        $data = DB::table('body_type')->where('type_name', $newData)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        } else {
            $body_type = new Bodytype();
            $body_type->type_name = $newData;
            $body_type->created_at = $newDate;
            $body_type->updated_at = $newDate;
            $body_type->save();

            return response()->json(['result' =>"データ保存成功", "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bodytype  $bodytype
     * @return \Illuminate\Http\Response
     */
    public function show(Bodytype $bodytype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bodytype  $bodytype
     * @return \Illuminate\Http\Response
     */
    public function edit(Bodytype $bodytype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bodytype  $bodytype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bodytype $bodytype)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $type = $request['type'];
        $id = $request['id'];

        $newDate = Carbon::now();

        $data = DB::table('body_type')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('body_type')->where('id', $id)->update([
                'type_name' => $type,
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
     * @param  \App\Models\Bodytype  $bodytype
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Bodytype $bodytype)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];


        $data = DB::table('body_type')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('body_type')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
