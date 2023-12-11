<?php

namespace App\Http\Controllers;

use App\Models\MatchingData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MatchingDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matchingdatas = MatchingData::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.matchingdata', ['matchingdatas' => $matchingdatas]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MatchingData  $matchingData
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, MatchingData $matchingData)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('matching_data')->where('id', $id)->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MatchingData  $matchingData
     * @return \Illuminate\Http\Response
     */
    public function edit(MatchingData $matchingData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MatchingData  $matchingData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MatchingData $matchingData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MatchingData  $matchingData
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MatchingData $matchingData)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('matching_data')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('matching_data')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }

    public function getChattingGroup(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $data = DB::select("SELECT
        a.sent_user_id AS user_id,
        a.received_user_id AS receiver_id,
        a.last_date_time,
        a.last_time,
        DATEDIFF(NOW(), a.last_date_time) AS last_date_time,  -- Calculate the difference in days
        b.user_nickname,
        b.photo1,
        b.intro_badge,
        c.residence,
        b.identity_state,
        b.height,
        b.online_status,
        a.last_msg,
        d.type_name AS body_name,
        b.holiday,
        e.use_purpose,
        b.cigarette,
        b.alcohol,
        b.phone_token,
        b.phone_number,
        a.is_read,
        a.register_method,
        a.unread_message,
        GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name,
        GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color,
        a.created_at,
        a.status,
        TIMESTAMPDIFF(YEAR, b.birthday, CURDATE()) AS age
    FROM
        likes_list AS a
    JOIN
        customer AS b ON a.received_user_id = b.id
    JOIN
        intro_badge r ON FIND_IN_SET(r.id, b.intro_badge)
    JOIN
        residence AS c ON b.address = c.id
    JOIN
        body_type AS d ON b.body_type = d.id
    JOIN
        use_purpose AS e ON b.use_purpose = e.id
    LEFT JOIN block_list w ON w.blocked_user_id = a.received_user_id
    WHERE
        a.sent_user_id = ?  AND a.matching = 2 AND (w.blocked_by IS NULL OR w.blocked_by <> ?)
    GROUP BY
        a.sent_user_id,
        b.user_nickname,
        b.photo1,
        b.intro_badge,
        c.residence,
        b.identity_state,
        b.height,
        d.type_name,
        b.holiday,
        e.use_purpose,
        b.cigarette,
        b.alcohol,
        b.birthday,
        b.phone_token,
        b.phone_number,
        a.created_at,
        a.status,
        b.online_status,
        a.last_msg,
        a.last_time,
        a.register_method,
        a.is_read,
        a.unread_message,
        a.received_user_id,
        a.last_date_time
    ORDER BY
        a.last_date_time DESC
    ", array($user_id,$user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }
}
