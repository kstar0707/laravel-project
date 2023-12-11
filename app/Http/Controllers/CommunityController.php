<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Category;
use App\Models\Customer;
use App\Models\IntroBadge;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use File;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communities = DB::table('community')->leftjoin("community_category", "community.community_category", "=", "community_category.id")->select("community.id", "community.community_category", "community.community_name", "community.community_photo", "community_category.category_name")->get();
        $categories = Category::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.community', ['communities' => $communities, 'categories' => $categories]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }
    }
    // leftjoin("community_category", "community.community_category", "=", "community_category.id")->
    // Send a listing of the Community
    public function getCommunity() {
        $community = DB::table('community')->select("*")->orderBy("community_category", "asc")->get();
        $category = DB::table('community_category')->select("*")->orderBy("id", "asc")->get();
        $data = [];
        for ($i=0; $i < count($category); $i++) {
            $count = DB::table('community')->where("community_category", "=", $category[$i]->id)->count();
            if($count > 0) {
                $data[] = [
                    'label' => $category[$i]->category_name,
                    'category' => -1,
                    'idx' => $category[$i]->id,
                    'parent' => -1,
                    'image' => 'category/' . $category[$i]->category_image
                ];
                for ($j=0; $j < count($community); $j++) {
                    if ((int) $community[$j]->community_category == $category[$i]->id) {
                        $data[] = [
                            'label' => $community[$j]->community_name,
                            'category' => (int) $community[$j]->community_category,
                            'idx' => $community[$j]->id,
                            'parent' => $category[$i]->id,
                            'image' => $community[$j]->community_photo
                        ];
                    }
                }
            }
        }
        return response()->json(['data' => $data, "type" => "success"]);
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
        $input = $request->all();

        $id = $request->input("edtCommunityId");
        $name = $request->input("edtCommunityName");
        $category = $request->input("edtCommunityCategory");
        $image = $request->input("image");

        if($name == '') {
            return response()->json(['result' =>"コミュニティ名を入力してください", "type" => "warning"]);
        } else if($category == '') {
            return response()->json(['result' =>"コミュニティカテゴリを入力してください", "type" => "warning"]);
        }

        $newDate = Carbon::now();

        $data = DB::table('community')->where('id', $id)->get();

            // return response()->json(['result' =>"データ保存成功", "type" => $data]);
        if(count($data) > 0) {
            if(file_exists($request->file('image'))) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $imageName = now()->format('YmdHis') . $request->file('image')->getClientOriginalName();
                $imageName = str_replace("_", "", $imageName);

                $request->image->move(public_path('uploads'), $imageName);

                DB::table('community')->where('id', $id)->update([
                    'community_name' => $name,
                    'community_category' => $category,
                    'community_photo' => $imageName,
                    'updated_at' => $newDate,
                ]);

                if (File::exists(public_path('uploads/' . $data[0]->community_photo))) {
                    File::delete(public_path('uploads/' . $data[0]->community_photo));
                }
            } else {
                DB::table('community')->where('id', $id)->update([
                    'community_name' => $name,
                    'community_category' => $category,
                    'updated_at' => $newDate,
                ]);
            }
            return response()->json(['result' =>"データが更新されました", "type" => "success"]);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = rand() . '.' .  $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('uploads'), $imageName);

            $community = new Community();
            $community->community_name = $name;
            $community->community_category = $category;
            $community->community_photo = $imageName;
            $community->created_at = $newDate;
            $community->updated_at = $newDate;
            $community->save();

            return response()->json(['result' =>"データ保存成功", "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Community $community)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Community $community)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];


        $data = DB::table('community')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('community')->where('id', $id)->delete();

            if (File::exists(public_path('uploads/community/' . $data[0]->community_photo))) {
                File::delete(public_path('uploads/community/' . $data[0]->community_photo));
            }
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }

    public function getCommunicationData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $board_id = $request['dataValue'];

        $user_id = $request['user_id'];

        $users = DB:: select("SELECT * from likes_list where sent_user_id = ?", array($user_id));
        $data = [];
        $list_users_array = array($user_id);
        for($i = 0; $i < count($users); $i ++) {
            $user = $users[$i]->received_user_id;
            array_push( $list_users_array, $user );
        }
        $list_users_array_to_string = implode(",", $list_users_array);

        $list_users_string_without_comma = rtrim($list_users_array_to_string, ",");

        $string = trim($list_users_string_without_comma);

        $itemcount = count($list_users_array);

        $blocks = DB:: select("SELECT * from block_list where blocked_by = ?", array($user_id));

        $block_list = array($user_id);
        for($i = 0; $i < count($blocks); $i ++) {
            $block = $blocks[$i]->blocked_user_id;
            array_push( $block_list, $block );
        }
        $block_list_string = implode(",", $block_list);

        $combined_string = $list_users_array_to_string . ',' . $block_list_string;

        $block_list_without_comma = rtrim($block_list_string, ",");

        $block_data = trim($block_list_without_comma);

        if($itemcount == 1) {
            $data = DB::select("SELECT
            community_category.id AS category_id,
            community_category.category_name,
            community_category.category_image,
            community.id AS sub_category_id,
            community.community_name,
            community.community_photo,
            COUNT(DISTINCT CASE WHEN customer.id NOT IN ($combined_string) THEN customer.id END) AS community_count,
            IF
                (
                    FIND_IN_SET( community.id, ( SELECT customer.community FROM customer WHERE customer.id = ? ) ) > 0,
                    1,
                    0
                ) AS entry_community
            FROM
                community
                LEFT JOIN (
                    SELECT
                        customer.id,
                        customer.community
                    FROM
                        customer
                ) AS customer ON FIND_IN_SET(community.id, REPLACE(customer.community, ' ', '')) > 0

                LEFT JOIN likes_list ON customer.id = likes_list.sent_user_id
                LEFT JOIN community_category ON community_category.id = community.community_category
                LEFT JOIN block_list w ON w.blocked_user_id = customer.id
            GROUP BY
                community.id
            ORDER BY
                community_category.id", array($user_id));
        }
        else {
            $data = DB::select("SELECT
            community_category.id AS category_id,
            community_category.category_name,
            community_category.category_image,
            community.id AS sub_category_id,
            community.community_name,
            community.community_photo,
            COUNT(DISTINCT CASE WHEN customer.id NOT IN ($combined_string) THEN customer.id END) AS community_count,
            IF
                (
                    FIND_IN_SET( community.id, ( SELECT customer.community FROM customer WHERE customer.id = ? ) ) > 0,
                    1,
                    0
                ) AS entry_community
            FROM
                community
                LEFT JOIN (
                    SELECT
                        customer.id,
                        customer.community
                    FROM
                        customer
                ) AS customer ON FIND_IN_SET(community.id, REPLACE(customer.community, ' ', '')) > 0

                LEFT JOIN likes_list ON customer.id = likes_list.sent_user_id
                LEFT JOIN community_category ON community_category.id = community.community_category
                LEFT JOIN block_list w ON w.blocked_user_id = customer.id
            GROUP BY
                community.id
            ORDER BY
                community_category.id", array($user_id));
        }


        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }



    public function getPeopleData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $sub_id = $request['sub_id'];

        $newDate = Carbon::now();

        $user_id = $request['user_id'];

        $myBirthday = DB::select('select birthday from customer where id = ?', array($user_id));

        $matching = DB::select('select * from block_list where blocked_by = ?', array($user_id));

        $block_list = array($user_id);
        for($i = 0; $i < count($matching); $i ++) {
            $block = $matching[$i]->blocked_user_id;
            array_push( $block_list, $block );
        }

        $list_users_array_to_string = implode(",", $block_list);

        $list_users_string_without_comma = rtrim($list_users_array_to_string, ",");

        $string = trim($list_users_string_without_comma);

        $data = "";
        if($sub_id == "0"){
            $data = DB::select("SELECT
            c.id AS user_id,
            c.user_nickname,
            c.photo1,
            c.intro_badge,
            a.residence,
            c.identity_state,
            c.height,
            w.use_purpose AS purpose_name,
            f.type_name AS body_name,
            c.holiday,
            c.use_purpose,
            c.cigarette,
            c.alcohol,
            c.phone_token,
            c.phone_number,
            c.online_status,
            c.pay_user,
            c.private_age,
            c.private_matching,
            GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name,
            GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color,
            c.birthday,
            c.created_at,
            c.user_name AS status,
            TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age,
            IF(l.received_user_id IS NOT NULL, 1, 0) AS matching_check
        FROM
            customer c
        JOIN
            intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
        JOIN
            residence AS a ON c.address = a.id
        JOIN
            body_type AS f ON f.id = c.body_type
        JOIN
            use_purpose AS w ON c.use_purpose = w.id
        LEFT JOIN
            likes_list l ON l.received_user_id = c.id AND l.sent_user_id = ? AND l.sent_user_id <> c.id
        LEFT JOIN block_list b ON b.blocked_user_id = c.id
        WHERE
            c.id <> ? AND ? <= DATE_ADD(c.created_at, INTERVAL 1 MONTH)
                    AND l.received_user_id IS NULL AND c.identity_state <> 2
                    AND (b.blocked_by IS NULL OR b.blocked_by <> ?)
                    AND c.id NOT IN ($string)
        GROUP BY
            c.id,
            c.user_nickname,
            c.photo1,
            c.intro_badge,
            c.birthday,
            a.residence,
            c.height,
            c.identity_state,
            f.type_name,
            c.holiday,
            c.use_purpose,
            c.cigarette,
            c.alcohol,
            w.use_purpose,
            c.phone_token,
            c.phone_number,
            c.created_at,
            c.user_name,
            c.online_status,
            c.private_age,
            c.private_matching,
            c.pay_user, matching_check", array($user_id,$user_id,$newDate,$user_id));
        }
        else{
            $data = DB::select("SELECT
            c.id AS user_id,
            c.user_nickname,
            c.photo1,
            c.intro_badge,
            a.residence,
            c.identity_state,
            c.height,
            w.use_purpose AS purpose_name,
            f.type_name AS body_name,
            c.holiday,
            c.use_purpose,
            c.cigarette,
            c.alcohol,
            c.phone_token,
            c.phone_number,
            c.online_status,
            c.pay_user,
            c.private_age,
            c.private_matching,
            GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name,
            GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color,
            c.birthday,
            c.created_at,
            c.user_name AS status,
            TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age,
            IF(l.received_user_id IS NOT NULL, 1, 0) AS matching_check
        FROM
            customer c
        JOIN
            intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
        JOIN
            residence AS a ON c.address = a.id
        JOIN
            body_type AS f ON f.id = c.body_type
        JOIN
            use_purpose AS w ON c.use_purpose = w.id
        LEFT JOIN
            likes_list l ON l.received_user_id = c.id AND l.sent_user_id = ? AND l.sent_user_id <> c.id
        LEFT JOIN block_list b ON b.blocked_user_id = c.id
        WHERE
            FIND_IN_SET(?, community) > 0 AND
            c.id <> ?
            AND l.received_user_id IS NULL AND c.identity_state <> 2
            AND (b.blocked_by IS NULL OR b.blocked_by <> ?)
            AND c.id NOT IN ($string)
        GROUP BY
            c.id,
            c.user_nickname,
            c.photo1,
            c.intro_badge,
            c.birthday,
            a.residence,
            c.height,
            c.identity_state,
            f.type_name,
            c.holiday,
            c.use_purpose,
            c.cigarette,
            c.alcohol,
            w.use_purpose,
            c.phone_token,
            c.phone_number,
            c.created_at,
            c.user_name,
            c.online_status,
            c.private_age,
            c.private_matching,
            c.pay_user, matching_check", array($user_id, $sub_id, $user_id, $user_id));
        }


        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function getLikeData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $users = DB::table('customer')->where('id', $user_id)->get();

        $user = $users->first();

        $data = DB::select("SELECT l.received_user_id as user_id, l.sent_user_id AS receiver_id, c.user_nickname, c.photo1, c.address, c.intro_badge, a.residence, c.identity_state, c.height, w.use_purpose as purpose_name, f.type_name as body_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, c.private_age, c.private_matching, GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name, GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color, c.birthday, l.status, TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age, c.phone_token, IF(l.received_user_id IS NOT NULL, 1, 0) AS matching_check, IF(l.status = '2', 1,0 ) AS matching_check1, IF(l.matching = '2', 1,0 ) AS matching_check2
            FROM customer c
                LEFT JOIN residence as a on c.address = a.id
                LEFT JOIN body_type as f on f.id = c.body_type
                LEFT JOIN use_purpose as w on c.use_purpose = w.id
                Left JOIN likes_list l ON (l.sent_user_id = c.id AND l.status <> 3 AND l.received_user_id = ?)
                LEFT JOIN block_list b ON b.blocked_user_id = l.sent_user_id
            JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
            WHERE l.received_user_id = ? AND l.matching <> 0 and l.matching <> 2 AND (b.blocked_by IS NULL OR b.blocked_by <> ?)
            GROUP BY c.id, c.user_nickname, c.photo1, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.private_age, c.private_matching, l.received_user_id, l.status, l.matching", array($user_id, $user_id, $user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function getMatchingData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $data = DB::select("SELECT l.received_user_id as user_id, l.sent_user_id AS receiver_id, c.user_nickname, c.photo1, c.address, c.intro_badge, a.residence, c.identity_state, c.height, w.use_purpose as purpose_name, f.type_name as body_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, c.private_age, c.private_matching, GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name, GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color, c.birthday, l.status, TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age, c.phone_token from likes_list as l left join customer as c on l.received_user_id = c.id left join residence as a on c.address = a.id left join body_type as f on c.body_type = f.id left join use_purpose as w on c.use_purpose = w.id join intro_badge as r on FIND_IN_SET(r.id,c.intro_badge) where l.sent_user_id = ? and matching = '1' GROUP BY l.received_user_id, l.sent_user_id, c.user_nickname, c.photo1, c.address, c.intro_badge, a.residence, c.identity_state, c.height, w.use_purpose, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, c.private_age, c.private_matching, c.birthday, l.status, l.created_at, c.phone_token", array($user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function getPreviewData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $users = DB::table('customer')->where('id', $user_id)->get();

        $user = $users->first();

        $data = DB::select("SELECT c.id as user_id, c.user_nickname, c.photo1, c.address, c.intro_badge, a.residence, c.identity_state, c.height, w.use_purpose as purpose_name, f.type_name as body_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, c.private_age, c.private_matching, GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name, GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color, c.birthday, TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age,
        IF(q.sent_user_id  IS NOT NULL, 1, 0) AS matching_check
               FROM customer c
               JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
               JOIN residence as a on c.address = a.id
               JOIN body_type as f on f.id = c.body_type
               JOIN use_purpose as w on c.use_purpose = w.id
               Left JOIN matching_data l ON (c.id = l.see_id AND l.user_id = ?)
               LEFT JOIN likes_list q ON (c.id = q.received_user_id AND q.sent_user_id = ?)
               LEFT JOIN block_list b ON b.blocked_by = q.sent_user_id
               WHERE l.user_id = ? AND l.status = 0 AND (b.blocked_by IS NULL OR b.blocked_by <> ?)
               GROUP BY c.id, c.user_nickname, c.photo1, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.private_age, c.private_matching, matching_check", array($user_id, $user_id, $user_id, $user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function getBrockData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $users = DB::table('customer')->where('id', $user_id)->get();

        $user = $users->first();

        $data = DB::select("SELECT c.id as user_id, c.user_nickname, c.photo1, c.address, c.intro_badge, a.residence, c.identity_state, c.height, w.use_purpose as purpose_name, f.type_name as body_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, c.private_age, c.private_matching, GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name, GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color, c.birthday, TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age,
        IF(l.sent_user_id  IS NOT NULL, 1, 0) AS matching_check
        FROM customer c
        JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
        JOIN residence as a on c.address = a.id
        JOIN body_type as f on f.id = c.body_type
        JOIN use_purpose as w on c.use_purpose = w.id
        Left JOIN likes_list l ON (c.id = l.received_user_id AND l.sent_user_id = ?)
        LEFT JOIN block_list b ON b.blocked_user_id = c.id
        WHERE b.blocked_by = ?
        GROUP BY c.id, c.user_nickname, c.photo1, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.private_age, c.private_matching, matching_check", array($user_id, $user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function addLikeData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $users = DB::table('customer')
        ->where('id', $send_id)
        ->get();

        DB::delete('delete from matching_data where see_id = ? and user_id = ?', array($send_id, $receiver_id));

        DB::delete('delete from matching_data where see_id = ? and user_id = ?', array($receiver_id, $send_id));

        if(count($users) > 0)
        {
            $user = $users->first();

            if($user->likes_rate == 0)
            {
                return response()->json(['result' =>"error", "type" => "error"]);
            }

            $likes = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();
            $data = "";


            $matching_dd = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();

            if(count($matching_dd) > 0)
            {
                $matching_data = $matching_dd->first()->matching;
                if($matching_data == "0") {
                    $like = $likes->first();
                    $like_data = DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)
                    ->update([
                        'amount' => $like->amount + 1,
                    ]);
                    $users = DB::table('customer')
                    ->where('id', $send_id)
                    ->update([
                        'likes_rate' => $user->likes_rate - 1,
                    ]);
                    $users = DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)
                    ->update([
                        'matching' => "1",
                    ]);

                    $contacts1 = DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)->get();
                    $contacts2 = DB::table('likes_list')
                    ->where('received_user_id', $send_id)
                    ->where('sent_user_id', $receiver_id)->get();

                    $contact1 = $contacts1->first()->matching;
                    $contact2 = $contacts2->first()->matching;

                    if($contact1 == "1" && $contact2 == "1") {
                        $users = DB::table('likes_list')
                        ->where('sent_user_id', $send_id)
                        ->where('received_user_id', $receiver_id)
                        ->update([
                            'status' => "1",
                            'matching' => "2",
                        ]);
                        $users = DB::table('likes_list')
                        ->where('received_user_id', $send_id)
                        ->where('sent_user_id', $receiver_id)
                        ->update([
                            'status' => "1",
                            'matching' => "2",
                        ]);
                        DB::table('response_board')
                        ->where('res_user_id', $receiver_id)
                        ->where('active_user_id', $send_id)
                        ->update([
                            'status' => "1",
                        ]);
                        DB::table('response_board')
                        ->where('res_user_id', $send_id)
                        ->where('active_user_id', $receiver_id)
                        ->update([
                            'status' => "1",
                        ]);

                        return response()->json(['result' =>"matching_succ", "type" => "success"]);
                    }

                    return response()->json(['result' =>"success", "type" => "success"]);
                }

                return response()->json(['result' =>"matching_error", "type" => "error"]);
            }

            $data = "";
            if(count($likes) > 0)
            {
                $like = $likes->first();
                $like_data = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->update([
                    'amount' => $like->amount + 1,
                ]);
            }
            else{
                $likes = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->get();
                if(count($likes) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching) VALUES (?,?,?,now(),now(),now(),?,?)", array($send_id,$receiver_id,'1',"0","1"));
                    $users = DB::table('customer')
                        ->where('id', $send_id)
                        ->update([
                            'likes_rate' => $user->likes_rate - 1,
                    ]);
                }
                $likess = DB::table('likes_list')
                ->where('sent_user_id', $receiver_id)
                ->where('received_user_id', $send_id)
                ->get();
                if(count($likess) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching) VALUES (?,?,?,now(),now(),now(),?,?)", array($receiver_id,$send_id,'1',"0","0"));

                }
            }

            if($data) {
                return response()->json(['result' =>"success", "type" => "success"]);
            } else {
                return response()->json(['result' =>"error", "type" => "error"]);
            }
        }
        return response()->json(['result' =>"error", "type" => "error"]);

    }

    public function addLikeData1(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $sub_id = $request['sub_id'];

        $newDate = Carbon::now();

        $users = DB::table('customer')
        ->where('id', $send_id)
        ->get();

        DB::delete('delete from matching_data where see_id = ? and user_id = ?', array($send_id, $receiver_id));

        if(count($users) > 0)
        {
            $user = $users->first();

            if($user->likes_rate == 0)
            {
                // $like = $likes->first();
                // $like_data = DB::table('likes_list')
                // ->where('sent_user_id', $send_id)
                // ->where('received_user_id', $receiver_id)
                // ->update([
                //     'status' => $like->amount + 1,
                // ]);
                return response()->json(['result' =>"error", "type" => "error"]);
            }

            $likes = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();
            $data = "";


            $matching_dd = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();

            if(count($matching_dd) > 0)
            {
                $matching_data = $matching_dd->first()->matching;
                if($matching_data == "0") {
                    $like = $likes->first();
                    $like_data = DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)
                    ->update([
                        'amount' => $like->amount + 1,
                    ]);
                    $users = DB::table('customer')
                    ->where('id', $send_id)
                    ->update([
                        'likes_rate' => $user->likes_rate - 1,
                    ]);
                    $users = DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)
                    ->update([
                        'matching' => "1",
                    ]);

                    $contacts1 = DB::table('likes_list')
                    ->where('sent_user_id', $send_id)
                    ->where('received_user_id', $receiver_id)->get();
                    $contacts2 = DB::table('likes_list')
                    ->where('received_user_id', $send_id)
                    ->where('sent_user_id', $receiver_id)->get();

                    $contact1 = $contacts1->first()->matching;
                    $contact2 = $contacts2->first()->matching;

                    if($contact1 == "1" && $contact2 == "1") {
                        $users = DB::table('likes_list')
                        ->where('sent_user_id', $send_id)
                        ->where('received_user_id', $receiver_id)
                        ->update([
                            'status' => "1",
                            'matching' => "2",
                        ]);
                        $users = DB::table('likes_list')
                        ->where('received_user_id', $send_id)
                        ->where('sent_user_id', $receiver_id)
                        ->update([
                            'status' => "1",
                            'matching' => "1",
                        ]);

                        return response()->json(['result' =>"matching_succ", "type" => "success"]);
                    }

                    return response()->json(['result' =>"success", "type" => "success"]);
                }

                return response()->json(['result' =>"matching_error", "type" => "error"]);
            }

            $data = "";
            if(count($likes) > 0)
            {
                $like = $likes->first();
                $like_data = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->update([
                    'amount' => $like->amount + 1,
                ]);
            }
            else{
                $likes = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->get();
                if(count($likes) == 0)
                {
                    $community_datas = DB::select("SELECT IF(EXISTS(SELECT * FROM customer WHERE id = ? AND FIND_IN_SET(?, community)), 1, 0) AS community_status", array($send_id,$sub_id));

                    $community_data = $community_datas[0]->community_status;

                    if($community_data == "0") {
                        DB::update("UPDATE customer
                        SET community = CONCAT(community, '$sub_id,')
                        WHERE id = ?", array($send_id));
                    }

                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching) VALUES (?,?,?,now(),now(),now(),?,?)", array($send_id,$receiver_id,'1',"0","1"));
                }
                $likess = DB::table('likes_list')
                ->where('sent_user_id', $receiver_id)
                ->where('received_user_id', $send_id)
                ->get();
                if(count($likess) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching) VALUES (?,?,?,now(),now(),now(),?,?)", array($receiver_id,$send_id,'1',"0","0"));

                }
            }


            $users = DB::table('customer')
            ->where('id', $send_id)
            ->update([
                'likes_rate' => $user->likes_rate - 1,
            ]);

            if($data) {
                return response()->json(['result' =>"success", "type" => "success"]);
            } else {
                return response()->json(['result' =>"error", "type" => "error"]);
            }
        }
        return response()->json(['result' =>"error", "type" => "error"]);

    }

    public function addUserLike(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $users = DB::table('customer')
        ->where('id', $send_id)
        ->get();

        if(count($users) > 0)
        {
            $user = $users->first();

            if($user->likes_rate == 0)
            {
                return response()->json(['result' =>"error", "type" => "error"]);
            }

            $likes = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();
            $data = "";
            if(count($likes) > 0)
            {
                $like = $likes->first();
                $like_data = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->update([
                    'amount' => $like->amount + 1,
                ]);
            }
            else{

                $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, status) VALUES (?,?,?,now(),now(),?)", array($send_id,$receiver_id,'1',"0"));

                $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, status) VALUES (?,?,?,now(),now(),?)", array($receiver_id,$send_id,'1',"0"));

            }


            $users = DB::table('customer')
            ->where('id', $send_id)
            ->update([
                'likes_rate' => $user->likes_rate - 1,
            ]);

            if($data) {
                return response()->json(['result' =>"success", "type" => "success"]);
            } else {
                return response()->json(['result' =>"error", "type" => "error"]);
            }
        }
        return response()->json(['result' =>"error", "type" => "error"]);

    }


    public function addUserTodayLike(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $users = DB::table('customer')
        ->where('id', $send_id)
        ->get();

        if(count($users) > 0)
        {
            $user = $users->first();

            $likes = DB::table('today_recomm')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();

            $data = "";
            if(count($likes) > 0)
            {
                $like = $likes->first();
                $like_data = DB::table('today_recomm')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->update([
                    'amount' => $like->amount + 1,
                ]);
            }
            else{

                $data = DB::insert("INSERT INTO today_recomm (sent_user_id, received_user_id, amount, created_at, updated_at) VALUES (?,?,?,now(),now())", array($send_id,$receiver_id,'1'));

                $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, matching, created_at, updated_at, status) VALUES (?,?,?,?,now(),now(),?)", array($send_id,$receiver_id,'1','1',"0"));

                $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, matching, created_at, updated_at, status) VALUES (?,?,?,?,now(),now(),?)", array($receiver_id,$send_id,'1','0',"0"));
            }

            if($data) {
                return response()->json(['result' =>"success", "type" => "success"]);
            } else {
                return response()->json(['result' =>"error", "type" => "error"]);
            }
        }
        return response()->json(['result' =>"error", "type" => "error"]);

    }

    public function updateLikeData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $msg = $request['msg'];

        $curtime = $request['curtime'];

        $newDate = Carbon::now();

        $customers = DB::table('customer')->where('phone_token', $receiver_id)->get();

        $customer = $customers->first();

        $likes = DB::table('likes_list')->where('received_user_id', $send_id)->where('sent_user_id', $customer->id)->get();

        $like = $likes->first();

        $users = DB::table('likes_list')
        ->where('sent_user_id', $send_id)
        ->where('received_user_id', $customer->id)
        ->update([
            'last_msg' => $msg,
            'last_time' => $curtime,
            'is_read' => null,
            'unread_message' => null,
            'last_date_time' => $newDate,
            'status' => "2",
        ]);

        $users = DB::table('likes_list')
        ->where('received_user_id', $send_id)
        ->where('sent_user_id', $customer->id)
        ->update([
            'last_msg' => $msg,
            'last_time' => $curtime,
            'is_read' => '1',
            'unread_message' => $like->unread_message + 1,
            'last_date_time' => $newDate,
            'status' => "2",
        ]);

        return response()->json(['result' =>"success", "type" => "success"]);

    }

    public function addMatching(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $status = $request['status'];

        $newDate = Carbon::now();

        $customers = DB::table('customer')->where('phone_token', $receiver_id)->get();

        $customer = $customers->first();

        $likes = DB::table('likes_list')->where('received_user_id', $send_id)->where('sent_user_id', $customer->id)->get();

        $like = $likes->first();

        if($status == "0"){
            $users = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $customer->id)
            ->update([
                'status' => "1",
            ]);
            $users = DB::table('likes_list')
            ->where('received_user_id', $send_id)
            ->where('sent_user_id', $customer->id)
            ->update([
                'status' => "1",
            ]);
            DB::delete('delete matching_data where see_id = ? and user_id = ?', array($send_id, $customer->id));
            DB::delete('delete matching_data where see_id = ? and user_id = ?', array($customer->id, $send_id));
        } else {
            $users = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $customer->id)
            ->update([
                'status' => "1",
            ]);
            DB::delete('delete matching_data where see_id = ? and user_id = ?', array($send_id, $customer->id));
            DB::delete('delete matching_data where see_id = ? and user_id = ?', array($customer->id, $send_id));
        }

        return response()->json(['result' =>"success", "type" => "success"]);

    }

    public function addMatching1(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $status = $request['status'];

        $likes = DB::table('likes_list')->where('received_user_id', $send_id)->where('sent_user_id', $receiver_id)->update([
            'status' => "1",
        ]);

        return response()->json(['result' =>"success", "type" => "success"]);

    }

    public function removeMatching(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $customers = DB::table('customer')->where('phone_token', $receiver_id)->get();

        $customer = $customers->first();

        $likes = DB::table('likes_list')->where('received_user_id', $send_id)->where('sent_user_id', $customer->id)->get();

        $like = $likes->first();

        // $users = DB::table('likes_list')
        // ->where('sent_user_id', $send_id)
        // ->where('received_user_id', $customer->id)
        // ->update([
        //     "status" => "3"
        // ]);

        DB::insert('insert into block_list (blocked_by, blocked_user_id) values (?, ?)', array($send_id, $customer->id));

        return response()->json(['result' =>"success", "type" => "success"]);

    }

    public function userReport(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $customers = DB::table('customer')->where('phone_token', $receiver_id)->get();

        $customer = $customers->first();

        $customers = DB::table('violation_report')->where('violation_id', $send_id)->where('user_id', $customer->id)->get();

        if(count($customers) > 0)
        {
            return response()->json(['result' =>"error", "type" => "error"]);
        }

        $data = DB::insert("INSERT INTO violation_report (violation_id, user_id, created_at) VALUES (?,?,now())", array($send_id,$customer->id));

        if($data){
            return response()->json(['result' =>"success", "type" => "success"]);
        }

    }

    public function changeBlock(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        // $customers = DB::table('likes_list')
        //             ->where('sent_user_id', $send_id)
        //             ->where('received_user_id', $receiver_id)
        //             ->update([
        //                 'status' => "2",
        //             ]);

        $customers = DB::delete('delete from block_list where blocked_by = ? and blocked_user_id = ?', array($send_id, $receiver_id));

        if($customers){
            return response()->json(['result' =>"success", "type" => "success"]);
        }
        else{
            return response()->json(['result' =>"error", "type" => "error"]);
        }
    }

    public function changeGoodLuck(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $users = DB::table('customer')
        ->where('id', $send_id)
        ->get();

        $customers = DB::delete('delete from matching_data where see_id = ? and user_id = ?', array($send_id, $receiver_id));

        if(count($users) > 0)
        {
            $user = $users->first();
            if($user->likes_rate == "0")
            {
                return response()->json(['result' =>"no_coin", "type" => "error"]);
            }

            $likes = DB::table('likes_list')
            ->where('sent_user_id', $send_id)
            ->where('received_user_id', $receiver_id)
            ->get();
            $data = "";
            if(count($likes) > 0)
            {
                $like = $likes->first();
                $like_data = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->update([
                    'amount' => $like->amount + 1,
                ]);
            }
            else{
                $likes = DB::table('likes_list')
                ->where('sent_user_id', $send_id)
                ->where('received_user_id', $receiver_id)
                ->get();
                if(count($likes) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching) VALUES (?,?,?,now(),now(),now(),?,?)", array($send_id,$receiver_id,'1',"0",'1'));
                }
                $likess = DB::table('likes_list')
                ->where('sent_user_id', $receiver_id)
                ->where('received_user_id', $send_id)
                ->get();
                if(count($likess) == 0)
                {
                    $data = DB::insert("INSERT INTO likes_list (sent_user_id, received_user_id, amount, created_at, updated_at, last_date_time, status, matching) VALUES (?,?,?,now(),now(),now(),?,?)", array($receiver_id,$send_id,'1',"0",'0'));

                }
            }

            $users = DB::table('customer')
            ->where('id', $send_id)
            ->update([
                'likes_rate' => $user->likes_rate - 1,
            ]);

            $customers = DB::table('matching_data')
                ->where('user_id', $send_id)
                ->where('see_id', $receiver_id)
                ->delete();

            return response()->json(['result' =>"success", "type" => "success"]);
        }
        return response()->json(['result' =>"error", "type" => "error"]);

    }

    public function updateMessageData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $send_id = $request['send_id'];

        $receiver_id = $request['receiver_id'];

        $newDate = Carbon::now();

        $customers = DB::table('customer')->where('phone_token', $receiver_id)->get();

        $customer = $customers->first();

        $users = DB::table('likes_list')
        ->where('sent_user_id', $send_id)
        ->where('received_user_id', $customer->id)
        ->update([
            'is_read' => null,
            'unread_message' => null
        ]);

        return response()->json(['result' =>"success", "type" => "success"]);

    }

    public function getUserList(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $users = DB::table('customer')->where('id', $user_id)->get();

        $user = $users->first();

        $data = DB::select("SELECT c.id as user_id, c.user_nickname, c.photo1, c.address, c.intro_badge, a.residence, c.identity_state, c.height, w.use_purpose as purpose_name, f.type_name as body_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, GROUP_CONCAT(r.tag_text SEPARATOR ', ') AS badge_name, GROUP_CONCAT(r.tag_color SEPARATOR ', ') AS badge_color, c.birthday, TIMESTAMPDIFF(YEAR, c.birthday, CURDATE()) AS age
            FROM customer c
            JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
            JOIN residence as a on c.address = a.id
            JOIN body_type as f on f.id = c.body_type
            JOIN use_purpose as w on c.use_purpose = w.id
            LEFT JOIN likes_list l ON (c.id = l.received_user_id AND l.sent_user_id = ?)
            WHERE c.id <> ? AND c.identity_state <> 0
            AND l.sent_user_id IS NULL
            GROUP BY c.id, c.user_nickname, c.photo1, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address ", array($user_id, $user_id));

        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }

    public function joinCommunity(Request $request) {
        $sub_id = $request['sub_id'];
        $user_id = $request['user_id'];
        $community_datas = DB::select("SELECT IF(EXISTS(SELECT * FROM customer WHERE id = ? AND FIND_IN_SET(?, community)), 1, 0) AS community_status", array($user_id,$sub_id));

        $community_data = $community_datas[0]->community_status;

        $community = DB::select('select * from community_favorite where sub_id = ? and user_id = ? and created_at = CURDATE()', array($sub_id,$user_id));

        if(count($community) == 0) {
            DB::insert('insert into community_favorite (sub_id, user_id, created_at) values (?, ?, now())', array($sub_id,$user_id));
        }

        if($community_data == "0") {
            DB::update("UPDATE customer
            SET community = CONCAT(community, '$sub_id,')
            WHERE id = ?", array($user_id));
        }
        return response()->json(['result' =>$community_datas, "type" => "success"]);
    }


    public function getFavoriteData(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');

        $board_id = $request['dataValue'];

        $user_id = $request['user_id'];

        $users = DB:: select("SELECT * from likes_list where sent_user_id = ?", array($user_id));
        $data = [];
        $list_users_array = array($user_id);
        for($i = 0; $i < count($users); $i ++) {
            $user = $users[$i]->received_user_id;
            array_push( $list_users_array, $user );
        }
        $list_users_array_to_string = implode(",", $list_users_array);

        $list_users_string_without_comma = rtrim($list_users_array_to_string, ",");

        $string = trim($list_users_string_without_comma);

        $itemcount = count($list_users_array);

        $blocks = DB:: select("SELECT * from block_list where blocked_by = ?", array($user_id));

        $block_list = array($user_id);
        for($i = 0; $i < count($blocks); $i ++) {
            $block = $blocks[$i]->blocked_user_id;
            array_push( $block_list, $block );
        }
        $block_list_string = implode(",", $block_list);

        $combined_string = $list_users_array_to_string . ',' . $block_list_string;

        $block_list_without_comma = rtrim($block_list_string, ",");

        $block_data = trim($block_list_without_comma);

        $data = DB::select("SELECT
                community_category.id AS category_id,
                community_category.category_name,
                community_category.category_image,
                community.id AS sub_category_id,
                community.community_name,
                community.community_photo,
                COUNT(DISTINCT CASE WHEN customer.id NOT IN ($combined_string) THEN customer.id END) AS community_count,
                IF(FIND_IN_SET(community.id, (SELECT customer.community FROM customer WHERE customer.id = ?)) > 0, 1, 0) AS entry_community,
                IF(sub_count.sub_id_count is null,0,sub_count.sub_id_count) as sub_id_count
                FROM
                    community
                LEFT JOIN customer ON FIND_IN_SET(community.id, REPLACE(customer.community, ' ', '')) > 0
                LEFT JOIN likes_list ON customer.id = likes_list.sent_user_id
                LEFT JOIN community_category ON community_category.id = community.community_category
                LEFT JOIN block_list w ON w.blocked_user_id = customer.id
                LEFT JOIN (
                    SELECT
                        sub_id,
                        COUNT(*) AS sub_id_count
                    FROM
                        community_favorite
                    WHERE
                        created_at = CURDATE()
                    GROUP BY
                        sub_id
                    HAVING
                        COUNT(*) > 0
                ) AS sub_count ON community.id = sub_count.sub_id
                GROUP BY
                    community.id
                ORDER BY
                    sub_id_count DESC LIMIT 4", array($user_id));



        if($data) {
            return response()->json(['result' =>$data, "type" => "success"]);
        } else {
            return response()->json(['result' =>$data, "type" => "error"]);
        }
    }
}
