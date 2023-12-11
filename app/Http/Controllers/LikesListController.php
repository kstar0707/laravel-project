<?php

namespace App\Http\Controllers;

use App\Models\LikesList;
use Illuminate\Http\Request;

class LikesListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likeslists = LikesList::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.likeslist', ['introbadges' => $introbadges]);
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
     * Send likes list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLikesList(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $user_id = $request['data'];

        $res = LikesList::select("sent_user_id", "received_user_id")->where("received_user_id", $user_id)->get();
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
        $sent = $request['sent_user'];
        $received = $request['received_user'];
        $amount = $request['amount'];

        $insertData = new LikesList();
        $insertData->sent_user_id = $sent;
        $insertData->received_user_id = $received;
        $insertData->amount = $amount;
        $insertData->save();

        return response()->json(['result' => true, "type" => "success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LikesList  $likesList
     * @return \Illuminate\Http\Response
     */
    public function show(LikesList $likesList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LikesList  $likesList
     * @return \Illuminate\Http\Response
     */
    public function edit(LikesList $likesList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LikesList  $likesList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LikesList $likesList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LikesList  $likesList
     * @return \Illuminate\Http\Response
     */
    public function destroy(LikesList $likesList)
    {
        //
    }
}
