<?php

namespace App\Http\Controllers;

use App\Models\BlockList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BlockListController extends Controller
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
     * Send block list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBlockList(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $user_id = $request['data'];

        $res = BlockList::select("customer.photo1, customer.id, customer.user_name, customer.user_nickname, customer.birthday")
                        ->join("customer", "block_list.blocked_user", "=", "customer.id")
                        ->where("blocked_by", $user_id)->get();
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
        $user = $request['blocked_by'];
        $blocked = $request['blocked_user'];

        $insertData = new BlockList();
        $insertData->blocked_by = $user;
        $insertData->blocked_user_id = $blocked;
        $insertData->save();

        return response()->json(['result' => true, "type" => "success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlockList  $blockList
     * @return \Illuminate\Http\Response
     */
    public function show(BlockList $blockList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlockList  $blockList
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockList $blockList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlockList  $blockList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockList $blockList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlockList  $blockList
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, BlockList $blockList)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $user = $request['blocked_by'];
        $blocked = $request['blocked_user'];

        DB::table('block_list')->where([['blocked_by', $user], ['blocked_user_id', $blocked]])->delete();
        return response()->json(['result' => true, "type" => "success"]);
    }
}
