<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = DB::select('select * from message ORDER BY created_at desc');
        $customers = Customer::all();

        foreach($messages as $message) {

            if($message->received_by == 0) {
                $message->received_name = "全ユーザー";
            } else if($message->received_by == 1) {
                $message->received_name = "無料ユーザー";
            } else if($message->received_by == 2) {
                $message->received_name = "有料ユーザー";
            }
            else {
                $message->received_name = DB::table('customer')->where('id', $message->received_id)->first()->user_nickname;
            }

        }
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.message', ['messages' => $messages, 'customers' => $customers]);
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
    public function getTitleList(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $queryArr = [];

        $planType = DB::table('customer')->where('id', $id)->first()->plan_type;
        // plan type 0: free, 1: paid
        // received message 0: all, -1: free, -2: paid
        if($planType == '0') {
            $queryArr = array(
                '0', '-1', $id
            );
        } else if($planType == '1') {
            $queryArr = array(
                '0', '-2', $id
            );
        }

        $res = Message::whereIn("received_by", $queryArr)->select("id", "title")->get();
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
        $title = $request['title'];
        $content = $request['content'];
        $received_by = $request['received_by'];
        $received_id = $request['received_id'];
        $newDate = Carbon::now();

        // $message = new Message();
        // $message->title = $title;
        // $message->content = $content;
        // $message->received_by = $received_by;
        // $message->received_id = $received_id;
        // $customer->created_at = $newDate;
        // $customer->updated_at = $newDate;
        // $message->save();

        $insert = DB::insert('insert into message (title, content, received_by, received_id, created_at, updated_at) values (?,?,?,?,?,?)', array($title, $content, $received_by, $received_id, $newDate, $newDate));

        return response()->json(['result' =>"データ保存成功", "type" => "success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Message $message)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('message')->where('id', $id)->first();

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $title = $request['title'];
        $content = $request['content'];
        $received_by = $request['received_by'];
        $received_id = $request['received_id'];
        $newDate = Carbon::now();

        // DB::table('message')->where('id', $id)->update([
        //     'title' => $title,
        //     'content' => $content,
        //     'updated_at' => $newDate,
        // ]);


        DB::update('update message set title = ?, content = ?, received_by = ?, received_id = ?, updated_at = ? where id = ?', array($title, $content, $received_by, $received_id, $newDate, $id));

        return response()->json(['result' =>"保管されました。", "type" => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        DB::delete('delete from message where id = ?', array($id));

        return response()->json(['result' =>"データ削除成功", "type" => "success"]);
    }

    public function getAdminNotif(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $user_id = $request['user_id'];
        $type = "";

        $user = DB::select('select * from customer where id = ?', array($user_id));

        if($user[0]->pay_user == "1") {
            $type = "2";
        }
        else{
            $type = "1";
        }

        $data = DB::select('select * from message where (received_by = ? or received_by = ? or received_id = ?)', array("0",$type, $user_id));

        return response()->json(['result' =>$data, "type" => "success"]);
    }
}
