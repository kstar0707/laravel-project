<?php

namespace App\Http\Controllers;

use App\Models\IntroBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class IntroBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $introbadges = IntroBadge::select("*")->orderBy("tag_color", "asc")->get();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.introbadge', ['introbadges' => $introbadges]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    // Send listing of the Intro badge
    public function getIntrobadge() {
        $introbadges = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->get();
        return response()->json(['data' => $introbadges, "type" => "success"]);
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
        $text = $request['text'];
        $color = $request['color'];

        $newDate = Carbon::now();

        $data = DB::table('intro_badge')->where('tag_text', $text)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        } else {
            $introbadge = new IntroBadge();
            $introbadge->tag_text = $text;
            $introbadge->tag_color = $color;
            $introbadge->created_at = $newDate;
            $introbadge->updated_at = $newDate;
            $introbadge->save();

            return response()->json(['result' =>"データ保存成功", "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IntroBadge  $introBadge
     * @return \Illuminate\Http\Response
     */
    public function show(IntroBadge $introBadge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IntroBadge  $introBadge
     * @return \Illuminate\Http\Response
     */
    public function edit(IntroBadge $introBadge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IntroBadge  $introBadge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IntroBadge $introBadge)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $text = $request['text'];
        $color = $request['color'];
        $id = $request['id'];

        $newDate = Carbon::now();

        $data = DB::table('intro_badge')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('intro_badge')->where('id', $id)->update([
                'tag_text' => $text,
                'tag_color' => $color,
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
     * @param  \App\Models\IntroBadge  $introBadge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, IntroBadge $introBadge)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];


        $data = DB::table('intro_badge')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('intro_badge')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
