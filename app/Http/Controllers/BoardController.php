<?php

namespace App\Http\Controllers;

use App\Models\Bodytype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function getBoardData(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['uid'];

        // Get today's date
        $today = Carbon::now()->toDateString();
        // Get the date from 1 week ago
        $oneWeekAgo = Carbon::now()->subWeek()->toDateString();

        // Delete all the dates from 1 week ago
        DB::table('active_board')->where('created_date', '<', $today)->update([
            "status" => "1",
        ]);

        $users = DB::table('users')->where('available_date', '<', $today)->where('user_id',$user_id)->get();

        if(count($users) > 0)
        {
            DB::table('customer')->where('id', $user_id)->update([
                "pay_user" => "0",
            ]);
        }

        $data = DB::select("SELECT DISTINCT
                a.*,
                b.user_name,
                b.user_nickname,
                b.address,
                b.birthday,
                b.height,
                b.photo1,
                c.residence,
                b.private_age,
                b.private_matching,
                b.pay_user,
                TIMESTAMPDIFF(
                    YEAR,
                    b.birthday,
                CURDATE()) AS age
            FROM
                active_board AS a
                LEFT JOIN response_board AS rb ON a.id = rb.board_id AND rb.res_user_id = ?
                LEFT JOIN customer AS b ON a.user_id = b.id
                LEFT JOIN residence AS c ON b.address = c.id
            WHERE
                a.user_id != ?
                AND a.user_id NOT IN (
                SELECT DISTINCT
                CASE

                    WHEN
                        sent_user_id = ? THEN
                            received_user_id
                            WHEN received_user_id = ? THEN
                            sent_user_id
                        END AS user_id
                    FROM
                        likes_list
                    WHERE
                        STATUS <> 0
                        AND ( sent_user_id = ? OR received_user_id = ? )
                    )
                    AND rb.board_id IS NULL
                    AND a.STATUS <> 1
            ORDER BY
                a.created_date DESC
        ", array($user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function postBoardData(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');

        $currentTime = Carbon::now();

        $board_id = $request['board_id'];
        $active_user_id = $request['active_user_id'];
        $res_board_content = $request['res_board_content'];
        $res_user_id = $request['res_user_id'];

        $data = DB::table('customer')->where('id', $res_user_id)->get();

        if(count($data) > 0)
        {
            $identity = $data->first();

            if($identity->identity_state == "0")
            {
                return response()->json(['result' =>"error", "type" => "error"]);
            }

            $boards = DB::table('response_board')->insert([
                'board_id' => $board_id,
                'active_user_id' => $active_user_id,
                'res_board_content' => $res_board_content,
                'res_user_id' => $res_user_id,
                'created_date' => $currentTime,
                'status' => '0',
                'updated_at' => $currentTime,
                'created_at' => $currentTime,
            ]);

            if($boards) {
                return response()->json(['result' =>"success", "type" => "success"]);
            } else {
                return response()->json(['result' =>"Wrong", "type" => "error"]);
            }

        }

    }

    public function activeBoardData(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');

        $currentTime = Carbon::now();

        $user_id = $request['uid'];
        $created_date = $request['created_date'];
        $board_text = $request['board_text'];

        $data = DB::table('customer')->where('id', $user_id)->get();

        if(count($data) > 0)
        {
            $identity = $data->first();

            if($identity->identity_state == "0")
            {
                return response()->json(['result' =>"error", "type" => "error"]);
            }

            $boards = DB::table('active_board')->insert([
                'user_id' => $user_id,
                'created_date' => $created_date,
                'board_content' => $board_text,
                'created_at' => $currentTime,
                'status' => '0',
            ]);

            if($boards) {
                return response()->json(['result' =>"success", "type" => "success"]);
            } else {
                return response()->json(['result' =>"Wrong", "type" => "error"]);
            }

        }
    }

    public function getResBoardData(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');

        $data = DB::table('active_board AS a')
        ->select(
            DB::raw('GROUP_CONCAT(DISTINCT a.id SEPARATOR ",") AS article_id'),
            DB::raw('(SELECT COUNT(id) FROM response_board WHERE board_id = a.id and status = 0) AS article_count'),
            DB::raw('GROUP_CONCAT(DISTINCT a.created_date SEPARATOR ",") AS created_date'),
            DB::raw('GROUP_CONCAT(DISTINCT a.board_content SEPARATOR ",") AS board_content'),
            DB::raw('GROUP_CONCAT(DISTINCT a.user_id SEPARATOR ",") AS user_id'),

        )
        ->where('a.user_id', $request['uid'])
        ->groupBy('a.id')
        ->get();
        // $data = DB::select("SELECT a.id,a.board_content, a.created_date, a.user_id FROM active_board AS a LEFT JOIN response_board AS b ON a.id = b.board_id WHERE a.user_id = '90' ");

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>"No internet", "type" => "error"]);
        }
    }

    public function getResDetail(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');

        $board_id = $request['dataValue'];

        $data = DB::select("select a.id as res_id, a.board_id, a.res_board_content, a.active_user_id, a.created_date, a.status, b.user_nickname, c.residence, b.photo1, a.res_user_id, TIMESTAMPDIFF(YEAR, b.birthday, CURDATE()) AS age from response_board as a left join customer as b on a.res_user_id = b.id left join residence as c on b.address = c.id where a.board_id = ? and a.status = ?", array($board_id, '0'));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function addMatchingData(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');

        $res_id = $request['res_id'];

        $send_id = $request['my_id'];

        $receiver_id = $request['res_user_id'];

        $newDate = Carbon::now();

        $valid = DB::select('select * from response_board where id = ?', array($res_id));

        $data = DB::table('response_board')->where('board_id', $valid[0]->board_id)->where('res_user_id', $receiver_id)->update([
            "status" => "1",
        ]);


        $users = DB::table('customer')
        ->where('id', $send_id)
        ->get();

        if(count($users) > 0)
        {
            $user = $users->first();

            if($user->likes_rate == 0)
            {

                return response()->json(['result' =>"buy_favorite", "type" => "warning"]);
            }

            $likes = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();
            $data = "";

            if(count($likes) > 0)
            {
                DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)
                    ->update([
                    'status' => '1',
                    'matching' => '2',
                    'register_method' => '1'
                ]);

                DB::table('likes_list')
                    ->where('received_user_id', $send_id)
                    ->where('sent_user_id', $receiver_id)
                    ->update([
                    'status' => '1',
                    'matching' => '2',
                    'register_method' => '1'
                ]);
                $users = DB::table('customer')
                ->where('id', $send_id)
                ->update([
                    'likes_rate' => $user->likes_rate - 1,
                ]);
                if($users) {
                    return response()->json(['result' =>"success", "type" => "success"]);
                }
            }
            else{
                $likes = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->get();
                if(count($likes) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching, register_method) VALUES (?,?,?,now(),now(),now(),?,?,?)", array($send_id,$receiver_id,'2',"1","2","1"));

                }
                $likess = DB::table('likes_list')
                ->where('sent_user_id', $receiver_id)
                ->where('received_user_id', $send_id)
                ->get();
                if(count($likess) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching,register_method) VALUES (?,?,?,now(),now(),now(),?,?,?)", array($receiver_id,$send_id,'2',"1","2","1"));
                }
                $users = DB::table('customer')
                    ->where('id', $send_id)
                    ->update([
                        'likes_rate' => $user->likes_rate - 1,
                ]);
                if($users) {
                    return response()->json(['result' =>"success", "type" => "success"]);
                }
            }
        }
        return response()->json(['result' =>"error", "type" => "error"]);
    }


    public function getData()
    {

        $data = DB::select("select a.*,b.user_nickname from active_board as a left join customer as b on a.user_id = b.id");

        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.actboard', ['actboard' => $data]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }
    }

    public function removeActboard(Request $request) {
        $res_id = $request['id'];

        $data = DB::table('active_board')->where('id', $res_id)->delete();
        DB::table('response_board')->where('board_id', $res_id)->delete();

        if($data) {
            return response()->json(['result' =>"削除されました", "type" => "success"]);
        } else {
            return response()->json(['result' =>"誤解が発生しました。", "type" => "error"]);
        }
    }

    public function getResData()
    {

        $data = DB::select("select a.*,b.user_nickname from response_board as a left join customer as b on a.res_user_id = b.id");
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.resboard', ['resboard' => $data]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    public function removeResboard(Request $request) {
        $res_id = $request['id'];

        $data = DB::table('response_board')->where('id', $res_id)->delete();

        if($data) {
            return response()->json(['result' =>"削除されました", "type" => "success"]);
        } else {
            return response()->json(['result' =>"誤解が発生しました。", "type" => "error"]);
        }
    }
}
