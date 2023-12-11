<?php

namespace App\Http\Controllers;

use App\Models\FootPrint;
use Illuminate\Http\Request;

class FootPrintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Send Footprint list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFootprintList(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $user_id = $request['data'];

        $res = FootPrint::select("customer.photo1, customer.id, customer.user_name, customer.user_nickname, customer.birthday")
                        ->join("customer", "block_list.viewed_by", "=", "customer.id")
                        ->where("shown_user", $user_id)->get();
        return response()->json(['data' => $res, "type" => "success"]);
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
        $viewed_by = $request['viewed_by'];
        $shown_user = $request['shown_user'];

        $insertData = new FootPrint();
        $insertData->viewed_by = $viewed_by;
        $insertData->shown_user = $shown_user;
        $insertData->save();

        return response()->json(['result' => true, "type" => "success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FootPrint  $footPrint
     * @return \Illuminate\Http\Response
     */
    public function show(FootPrint $footPrint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FootPrint  $footPrint
     * @return \Illuminate\Http\Response
     */
    public function edit(FootPrint $footPrint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FootPrint  $footPrint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FootPrint $footPrint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FootPrint  $footPrint
     * @return \Illuminate\Http\Response
     */
    public function destroy(FootPrint $footPrint)
    {
        //
    }
}
