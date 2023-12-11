<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Residence;
use App\Models\Bodytype;
use App\Models\UsePurpose;
use App\Models\IntroBadge;
use App\Models\Community;
use App\Models\PaidPlanType;
use Validator,Redirect,Response,File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::select("customer.user_name","customer.id",
                                    "customer.user_nickname", "customer.address as residenceid","customer.photo1","customer.photo2","customer.photo3","customer.photo4","customer.photo5","customer.photo6",
                                    "residence.residence as residence", "customer.birthday", "customer.community", "customer.height",
                                    "customer.body_type as bodytypeId", "body_type.type_name as bodytype", "use_purpose.use_purpose", "customer.intro_badge", "customer.introduce", "customer.phone_number", "customer.phone_token",
                                    "customer.plan_type", "customer.likes_rate", "customer.coin", "customer.identity_state", "customer.blood_type", "customer.alcohol", "customer.cigarette","customer.private_age", "customer.private_matching","customer.pay_user","customer.education", "customer.holiday", "customer.annual_income", "customer.remember_token")
                                ->join("residence", "customer.address", "=", "residence.id")
                                ->join("body_type", "customer.body_type", "=", "body_type.id")
                                ->join("use_purpose", "customer.use_purpose", "=", "use_purpose.id")
                                ->orderBy('customer.id', 'desc')
                                ->get();

        foreach($customers as $customer) {
            $communityArr = explode(',', $customer->community);
            $customer->community = DB::table('community')->leftjoin("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

            $introbadgeArr = explode(',', $customer->intro_badge);
            $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

            if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                $customer->plan_type = "無料プラン";
            } else {
                $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
            }

            if($customer->identity_state == '0') {
                if($customer->remember_token == '1') {
                    $customer->identity_state = 'waiting';
                }
                else {
                    $customer->identity_state = 'ブロック';
                }
            } else if($customer->identity_state == '1'){
                $customer->identity_state = '承認';
            } else if($customer->identity_state == '2'){
                $customer->identity_state = 'block';
            }
        }
        $residences = Residence::all();
        $bodytypes = Bodytype::all();
        $usepurposes = UsePurpose::all();
        $introbadges = IntroBadge::all();
        $communities = Community::all();
        $paidplantypes = PaidPlanType::all();

        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.customer', ['customers' => $customers,
                                                'residences' => $residences,
                                                'bodytypes' => $bodytypes,
                                                'usepurposes' => $usepurposes,
                                                'introbadges' => $introbadges,
                                                'communities' => $communities,
                                                'paidplantypes' => $paidplantypes
                                                ]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }


    }

    /**
     * Send result of nickname validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nickNameValidation(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $req = $request['nickname'];

        $res = Customer::select("*")->where("user_nickname", $req)->get();
        if(count($res) > 0) {
            return response()->json(['result' => false, "type" => "error"]);
        } else {
            return response()->json(['result' => true, "type" => "success"]);
        }
    }

    /**
     * Send result of introduce validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function introduceValidation(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $req = $request['id'];

        $res = Customer::select("*")->where("id", $req)->get();
        if(count($res) > 0) {
            foreach($res as $data) {
                if($data->introduce == '' || $data->introduce == null) {
                    return response()->json(['result' => false, "type" => "error"]);
                }
            }
            return response()->json(['result' => true, "type" => "success"]);
        } else {
            return response()->json(['result' => false, "type" => "error"]);
        }
    }

    /**
     * Send result of introduce validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isIdentityVerifed(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $req = $request['id'];

        $res = Customer::select("*")->where("id", $req)->get();
        if(count($res) > 0) {
            foreach($res as $data) {
                if($data->identity_state == 'block' || $data->identity_state == null || $data->identity_state == '') {
                    return response()->json(['result' => false, "type" => "error"]);
                }
            }
            return response()->json(['result' => true, "type" => "success"]);
        } else {
            return response()->json(['result' => false, "type" => "error"]);
        }
    }

    /**
     * Send result of introduce Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function introduceUpdate(Request $request) {

        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'introduce' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Nice Name Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nickNameUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'user_nickname' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Residence Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function residenceUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'address' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Height Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function heightUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'height' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Body Type Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bodyTypeUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'body_type' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    public function uploadLikesRate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];
        $like_count = "";
        if($data == "10"){
            $like_count = "20";
        } else if($data == "30"){
            $like_count = "60";
        } else{
            $like_count = "100";
        }
        $coins = DB::table('customer')->where('id', $id)->get();

        $coining = $coins->first();

        if($coining->coin < $data)
        {
            return response()->json(['result' => false, "type" => "error"]);
        }

        $res = DB::table('customer')->where('id', $id)->update([
            'likes_rate' => $coining->likes_rate + $like_count,
            'coin' => $coining->coin - $data
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    public function phoneValidation(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');

        $phone = $request['phone'];

        $phone_list = DB::table('customer')->where('phone_number', $phone)->get();

        if(count($phone_list) > 0)
        {

            $phones = $phone_list->first();

            if($phones->phone_number == $phone)
            {
                return response()->json(['result' => "Connected", "type" => "success"]);
            }
            else{
                return response()->json(['result' => "Wrong", "type" => "error"]);
            }

        }
        return response()->json(['result' => "No data", "type" => "error"]);
    }

    /**
     * Send result of Blood Type Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bloodTypeUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'blood_type' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Education Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function educationUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'education' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    public function userPurpose(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'use_purpose' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Annual Income Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function annualIncomeUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'annual_income' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Smoking Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function smokingUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'cigarette' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }


    /**
     * Send result of Alcohol Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function alcoholUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'alcohol' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Holiday Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function holidayUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'holiday' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Send result of Intro badge Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function introBadgeUpdate(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'intro_badge' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Setting show for unverified age.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showUnverifiedAge(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'is_show_unverified_age' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Setting show for Unmatched person.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showUnmatchedPerson(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];
        $data = $request['data'];

        $res = DB::table('customer')->where('id', $id)->update([
            'is_show_unmatched_person' => $data,
        ]);

        if($res)
            return response()->json(['result' => true, "type" => "success"]);
        else
            return response()->json(['result' => false, "type" => "error"]);
    }

    /**
     * Setting show for Unmatched person.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadPhoto(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $input = $request->all();

        $id = $request->input('id');
        $position = $request->input('position');
        $image = $request->input("image");

        $data = DB::table('customer')->where('id', $id)->first()->photo . "" . $position;

        if($data == '') {
            if( !file_exists($request->file('image'))) {
                return response()->json(['result' =>"写真をアップロードしてください", "type" => "warning"]);
            }

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = rand() . '.' .  $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('uploads'), $imageName);

            DB::table('customer')->where('id', $id)->update([
                'photo' . $position => $imageName,
            ]);
        } else {
            if( !file_exists($request->file('image'))) {
                return response()->json(['result' =>"写真をアップロードしてください", "type" => "warning"]);
            }

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = rand() . '.' .  $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('uploads'), $imageName);

            DB::table('customer')->where('id', $id)->update([
                'photo' . $position => $imageName,
            ]);

            if (File::exists(public_path('uploads/' . $data))) {
                File::delete(public_path('uploads/' . $data));
            }
        }
    }

    /**
     * Send result of user info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo(Request $request) {
        // $token = request()->header('X-CSRF-TOKEN');
        $req = $request['id'];

        $res_count = DB::table('response_board')
        ->where('status', '0')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        $newDate = Carbon::now();

        $todayRecoms = DB::select("SELECT COUNT(sent_user_id) AS today_count
            FROM today_recomm
            WHERE DATE(created_at) = CURDATE() AND sent_user_id = ?", array($req));

        $validations = DB::select("SELECT available_date AS pay_date
        FROM users
        WHERE user_id = ?", array($req));

        $valid = $validations[0];
        if($valid->pay_date < $newDate) {
            DB::table('customer')->where('id', $req)->update([
                "pay_user" => "0",
                "private_age" => "0",
                "private_matching" => "0"
            ]);
        }

        $avail_date = DB::select("SELECT available_date AS pay_date
            FROM users
            WHERE user_id = ?", array($req));

        $review_type = "0";
        $m_count = DB::select('SELECT COUNT(id) as matching_count FROM likes_list where (sent_user_id = ?) and status <> 0', array($req,$req));

        if($m_count[0]->matching_count >= 5 && $m_count[0]->matching_count < 10)
        {
            $review_type = "1";
        }
        else if($m_count[0]->matching_count >= 10 && $m_count[0]->matching_count < 20){
            $review_type = "2";
        }
        else if($m_count[0]->matching_count >= 20){
            $review_type = "3";
        }
        $review = "";
        if($review_type != "0") {
            $reviews = DB::select('select * from review where user_id = ? and type = ?', array($req, $review_type));
            if(count($reviews) > 0) {
                $review = "exist";
            }
            else {
                $review = "notdata";
            }
        }


        $owner = DB::select('SELECT *
            FROM
                customer AS a
                LEFT JOIN identify AS b ON a.id = b.user_id
            WHERE
                a.identity_state = 0
                AND a.id = ?
                AND b.user_id IS NOT NULL', array($req));

        if(count($owner) == "0") {
            DB::update('update customer set remember_token = ? where id = ?', array('1', $req));
        }
        else {
            DB::update('update customer set remember_token = ? where id = ?', array('0', $req));
        }


        $customers = Customer::select("customer.user_name","customer.id",
                                        "customer.user_nickname", "customer.address as residenceid","customer.photo1","customer.photo2","customer.photo3","customer.photo4","customer.photo5","customer.photo6",
                                        "residence.residence as residence", "customer.birthday", "customer.community", "customer.height",
                                        "customer.body_type as bodytypeId", "body_type.type_name as bodytype", "use_purpose.use_purpose", "customer.intro_badge", "customer.introduce", "customer.phone_number", "customer.phone_token",
                                        "customer.plan_type", "customer.intro_dialog", "customer.identity_dialog", "customer.likes_rate", "customer.coin", "customer.identity_state", "customer.blood_type", "customer.alcohol", "customer.cigarette","customer.private_age", "customer.private_matching","customer.pay_user","customer.education", "customer.holiday", "customer.annual_income", "customer.remember_token")
                                ->join("residence", "customer.address", "=", "residence.id")
                                ->join("body_type", "customer.body_type", "=", "body_type.id")
                                ->join("use_purpose", "customer.use_purpose", "=", "use_purpose.id")
                                ->where("customer.id", $req)->get();
        foreach($customers as $customer) {
            $communityArr = explode(',', $customer->community);
            $customer->community = DB::table('community')->select('community.*')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

            $introbadgeArr = explode(',', $customer->intro_badge);
            $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

            if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                $customer->plan_type = "無料プラン";
            } else {
                $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
            }

            if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                $customer->identity_state = 'ブロック';
            } else if($customer->identity_state == '1'){
                $customer->identity_state = '承認';
            } else{
                $customer->identity_state = 'block';
            }
            return response()->json(['data' => $customer, 'count' => $res_count, 'today_recom' => $todayRecoms[0], 'avail_date' => $avail_date[0], 'review_data' => $review, "type" => "success"]);
        }
    }

    public function getUserPhoneInfo(Request $request) {
        $token = request()->header('X-CSRF-TOKEN');
        $phone_id = "+".$request['phone_id'];

        $users = DB::table('customer')
        ->where('phone_number', $phone_id)
        ->get();

        $user = $users->first();

        $req = $user->id;

        $res_count = DB::table('response_board')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        $newDate = Carbon::now();

        $todayRecoms = DB::select("SELECT COUNT(sent_user_id) AS today_count
            FROM today_recomm
            WHERE DATE(created_at) = CURDATE() AND sent_user_id = ?", array($req));

        $avail_date = DB::select("SELECT available_date AS pay_date
            FROM users
            WHERE user_id = ?", array($req));

        $customers = Customer::select("customer.user_name","customer.id",
                                        "customer.user_nickname", "customer.address as residenceid","customer.photo1","customer.photo2","customer.photo3","customer.photo4","customer.photo5","customer.photo6",
                                        "residence.residence as residence", "customer.birthday", "customer.community", "customer.height",
                                        "customer.body_type as bodytypeId", "body_type.type_name as bodytype", "use_purpose.use_purpose", "customer.intro_badge", "customer.introduce", "customer.phone_number", "customer.phone_token",
                                        "customer.plan_type", "customer.likes_rate", "customer.coin", "customer.identity_state", "customer.blood_type", "customer.alcohol", "customer.cigarette","customer.private_age", "customer.private_matching","customer.pay_user","customer.education", "customer.holiday", "customer.annual_income")
                                ->join("residence", "customer.address", "=", "residence.id")
                                ->join("body_type", "customer.body_type", "=", "body_type.id")
                                ->join("use_purpose", "customer.use_purpose", "=", "use_purpose.id")
                                ->where("customer.id", $req)->get();

        foreach($customers as $customer) {
            $communityArr = explode(',', $customer->community);
            $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

            $introbadgeArr = explode(',', $customer->intro_badge);
            $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

            if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                $customer->plan_type = "無料プラン";
            } else {
                $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
            }

            if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                $customer->identity_state = 'ブロック';
            } else if($customer->identity_state == '1'){
                $customer->identity_state = '承認';
            } else{
                $customer->identity_state = 'block';
            }

            return response()->json(['data' => $customer, 'count' => $res_count, 'today_recom' => $todayRecoms[0], 'avail_date' => $avail_date[0], "type" => "success"]);
        }
    }

    public function getUserInfo1(Request $request) {
        // $token = request()->header('X-CSRF-TOKEN');
        $req = $request['id'];
        $res_count = DB::table('response_board')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        $newDate = Carbon::now();
        $customers = Customer::select("customer.user_name",
                                        "customer.user_nickname", "customer.address as residenceid","customer.photo1","customer.photo2","customer.photo3","customer.photo4","customer.photo5","customer.photo6",
                                        "residence.residence as residence", "customer.birthday", "customer.community", "customer.height",
                                        "customer.body_type as bodytypeId", "body_type.type_name as bodytype", "use_purpose.use_purpose", "customer.intro_badge", "customer.introduce", "customer.phone_number", "customer.phone_token",
                                        "customer.plan_type", "customer.likes_rate", "customer.coin", "customer.identity_state", "customer.blood_type", "customer.alcohol", "customer.cigarette","customer.private_age", "customer.private_matching","customer.pay_user",
                                        "customer.education", "customer.holiday", "customer.annual_income")
                                ->join("residence", "customer.address", "=", "residence.id")
                                ->join("body_type", "customer.body_type", "=", "body_type.id")
                                ->join("use_purpose", "customer.use_purpose", "=", "use_purpose.id")
                                ->where("customer.id", $req)->get();
        foreach($customers as $customer) {
            $communityArr = explode(',', $customer->community);
            $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

            $introbadgeArr = explode(',', $customer->intro_badge);
            $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

            if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                $customer->plan_type = "無料プラン";
            } else {
                $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
            }

            if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                $customer->identity_state = 'ブロック';
            } else if($customer->identity_state == '1'){
                $customer->identity_state = '承認';
            } else{
                $customer->identity_state = 'block';
            }
            return response()->json(['data' => $customer,'count' => $res_count,  "type" => "success"]);
        }
    }


    public function getLikeData1(Request $request) {
        // $token = request()->header('X-CSRF-TOKEN');
        $req = $request['UserId'];

        $myUserId = $request['myUserId'];

        $res_count = DB::table('response_board')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        $customers = DB::select("SELECT c.id as user_id, c.user_name, c.user_nickname, c.address as residenceid, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, a.residence,c.birthday, c.community, c.height, c.body_type as bodytypeId, f.type_name as bodytype, c.use_purpose as purposeId, w.use_purpose, c.intro_badge, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.identity_state, c.blood_type, c.alcohol, c.cigarette, c.education, c.holiday, c.annual_income, c.private_age, c.private_matching, c.pay_user
            FROM customer c
            JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
            JOIN residence as a on c.address = a.id
            JOIN body_type as f on f.id = c.body_type
            JOIN use_purpose as w on c.use_purpose = w.id
            LEFT JOIN today_recomm l ON (c.id = l.received_user_id AND l.sent_user_id = ?)
            LEFT JOIN likes_list m ON (c.id = m.received_user_id AND m.sent_user_id = ?)
            WHERE c.id <> ? AND c.identity_state <>  2
            AND l.sent_user_id IS NULL
            AND m.sent_user_id IS NULL
            GROUP BY c.id, c.user_name, c.user_nickname, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.community, c.body_type, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.blood_type, c.education, c.annual_income, c.private_age, c.private_matching, c.pay_user
            ORDER BY RAND()*(10-5+1)+5
            LIMIT 1", array($req, $req, $req));
        if(count($customers) > 0)
        {
            foreach($customers as $customer) {
                $communityArr = explode(',', $customer->community);
                $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

                $introbadgeArr = explode(',', $customer->intro_badge);
                $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

                if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                    $customer->plan_type = "無料プラン";
                } else {
                    $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
                }

                if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                    $customer->identity_state = 'ブロック';
                } else if($customer->identity_state == '1'){
                    $customer->identity_state = '承認';
                } else{
                    $customer->identity_state = 'block';
                }
                return response()->json(['data' => $customer, 'count' => $res_count, "type" => "success"]);
            }
        }
        return response()->json(['data' => $customers, 'count' => $res_count, "type" => "success"]);
    }


    public function getLikeData2(Request $request) {
        // $token = request()->header('X-CSRF-TOKEN');
        $req = $request['UserId'];

        $sub_id = $request['sub_id'];

        $myUserId = $request['myUserId'];

        $res_count = DB::table('response_board')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        if($sub_id == "0") {
            $customers = DB::select("SELECT c.id as user_id, c.user_name, c.user_nickname, c.address as residenceid, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, a.residence,c.birthday, c.community, c.height, c.body_type as bodytypeId, f.type_name as bodytype, c.use_purpose as purposeId, w.use_purpose, c.intro_badge, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.identity_state, c.blood_type, c.alcohol, c.cigarette, c.education, c.holiday, c.annual_income, c.private_age, c.private_matching, c.pay_user
                FROM customer c
                JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
                JOIN residence as a on c.address = a.id
                JOIN body_type as f on f.id = c.body_type
                JOIN use_purpose as w on c.use_purpose = w.id
                LEFT JOIN likes_list m ON (c.id = m.received_user_id AND m.sent_user_id = ?)
                WHERE c.id <> ? AND c.identity_state  <>  2
                AND m.sent_user_id IS NULL
                GROUP BY c.id, c.user_name, c.user_nickname, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.community, c.body_type, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.blood_type, c.education, c.annual_income, c.private_age, c.private_matching, c.pay_user
                ORDER BY RAND()*(10-5+1)+5
                LIMIT 1", array($req, $req));
            if(count($customers) > 0)
            {
                foreach($customers as $customer) {
                    $communityArr = explode(',', $customer->community);
                    $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

                    $introbadgeArr = explode(',', $customer->intro_badge);
                    $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

                    if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                        $customer->plan_type = "無料プラン";
                    } else {
                        $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
                    }

                    if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                        $customer->identity_state = 'ブロック';
                    } else if($customer->identity_state == '1'){
                        $customer->identity_state = '承認';
                    } else{
                        $customer->identity_state = 'block';
                    }
                    return response()->json(['data' => $customer, 'count' => $res_count, "type" => "success"]);
                }
                return response()->json(['data' => $customers, 'count' => $res_count, "type" => "success"]);
            }
        }
        else {
            $customers = DB::select("SELECT c.id as user_id, c.user_name, c.user_nickname, c.address as residenceid, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, a.residence,c.birthday, c.community, c.height, c.body_type as bodytypeId, f.type_name as bodytype, c.use_purpose as purposeId, w.use_purpose, c.intro_badge, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.identity_state, c.blood_type, c.alcohol, c.cigarette, c.education, c.holiday, c.annual_income, c.private_age, c.private_matching, c.pay_user
                FROM customer c
                JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
                JOIN residence as a on c.address = a.id
                JOIN body_type as f on f.id = c.body_type
                JOIN use_purpose as w on c.use_purpose = w.id
                LEFT JOIN likes_list m ON (c.id = m.received_user_id AND m.sent_user_id = ?)
                WHERE c.id <> ? AND c.identity_state  <>  2 AND FIND_IN_SET(?, c.community)
                AND m.sent_user_id IS NULL
                GROUP BY c.id, c.user_name, c.user_nickname, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.community, c.body_type, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.blood_type, c.education, c.annual_income, c.private_age, c.private_matching, c.pay_user
                ORDER BY RAND()*(10-5+1)+5
                LIMIT 1", array($req, $req, $sub_id));
            if(count($customers) > 0)
            {
                foreach($customers as $customer) {
                    $communityArr = explode(',', $customer->community);
                    $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

                    $introbadgeArr = explode(',', $customer->intro_badge);
                    $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

                    if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                        $customer->plan_type = "無料プラン";
                    } else {
                        $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
                    }

                    if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                        $customer->identity_state = 'ブロック';
                    } else if($customer->identity_state == '1'){
                        $customer->identity_state = '承認';
                    } else{
                        $customer->identity_state = 'block';
                    }
                    return response()->json(['data' => $customer, 'count' => $res_count, "type" => "success"]);
                }
                return response()->json(['data' => $customers, 'count' => $res_count, "type" => "success"]);
            }
        }

        return response()->json(['data' => $customers, 'count' => $res_count, "type" => "success"]);
    }

    public function getLikeData3(Request $request) {
        // $token = request()->header('X-CSRF-TOKEN');
        $req = $request['UserId'];

        $myUserId = $request['myUserId'];

        $res_count = DB::table('response_board')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        $customers = DB::select("SELECT c.id as user_id, c.user_name, c.user_nickname, c.address as residenceid, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, a.residence,c.birthday, c.community, c.height, c.body_type as bodytypeId, f.type_name as bodytype, c.use_purpose as purposeId, w.use_purpose, c.intro_badge, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.identity_state, c.blood_type, c.alcohol, c.cigarette, c.education, c.holiday, c.annual_income, c.private_age, c.private_matching, c.pay_user
            FROM customer c
                LEFT JOIN residence as a on c.address = a.id
                LEFT JOIN body_type as f on f.id = c.body_type
                LEFT JOIN use_purpose as w on c.use_purpose = w.id
                Left JOIN likes_list l ON (l.sent_user_id = c.id AND l.status = 0 AND l.received_user_id = ?)
            JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
            WHERE l.received_user_id = ? AND c.identity_state  <>  2
            GROUP BY c.id, c.user_name, c.user_nickname, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.community, c.body_type, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.blood_type, c.education, c.annual_income, c.private_age, c.private_matching, c.pay_user
            ORDER BY RAND()*(10-5+1)+5
            LIMIT 1", array($req, $req));
        if(count($customers) > 0)
        {
            foreach($customers as $customer) {
                $communityArr = explode(',', $customer->community);
                $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

                $introbadgeArr = explode(',', $customer->intro_badge);
                $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

                if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                    $customer->plan_type = "無料プラン";
                } else {
                    $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
                }

                if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                    $customer->identity_state = 'ブロック';
                } else if($customer->identity_state == '1'){
                    $customer->identity_state = '承認';
                } else{
                    $customer->identity_state = 'block';
                }
                return response()->json(['data' => $customer, 'count' => $res_count, "type" => "success"]);
            }
        }
        return response()->json(['data' => $customers, 'count' => $res_count, "type" => "success"]);
    }


    public function getLikeData4(Request $request) {
        // $token = request()->header('X-CSRF-TOKEN');
        $req = $request['UserId'];

        $myUserId = $request['myUserId'];

        $res_count = DB::table('response_board')
        ->select(
            DB::raw('(SELECT COUNT(active_user_id)) AS res_count'),

        )
        ->where('active_user_id', $req)
        ->get()->first();

        $customers = DB::select("SELECT c.id as user_id, c.user_name, c.user_nickname, c.address as residenceid, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, a.residence,c.birthday, c.community, c.height, c.body_type as bodytypeId, f.type_name as bodytype, c.use_purpose as purposeId, w.use_purpose, c.intro_badge, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.identity_state, c.blood_type, c.alcohol, c.cigarette, c.education, c.holiday, c.annual_income, c.private_age, c.private_matching, c.pay_user
        FROM customer c
        JOIN intro_badge r ON FIND_IN_SET(r.id, c.intro_badge)
        JOIN residence as a on c.address = a.id
        JOIN body_type as f on f.id = c.body_type
        JOIN use_purpose as w on c.use_purpose = w.id
        LEFT JOIN matching_data m ON (c.id = m.see_id AND m.user_id = ?)
        WHERE c.identity_state <> 2 AND m.user_id = ? AND m.status = 0
        GROUP BY c.id, c.user_name, c.user_nickname, c.photo1, c.photo2, c.photo3, c.photo4, c.photo5, c.photo6, c.intro_badge, c.birthday, a.residence, c.height, c.identity_state, f.type_name, c.holiday, c.use_purpose, c.cigarette, c.alcohol, w.use_purpose,c.address, c.community, c.body_type, c.introduce, c.phone_number, c.phone_token, c.plan_type, c.likes_rate, c.coin, c.blood_type, c.education, c.annual_income, c.private_age, c.private_matching, c.pay_user
            ORDER BY RAND()*(10-5+1)+5
            LIMIT 1", array($req, $req));
        if(count($customers) > 0)
        {
            foreach($customers as $customer) {
                $communityArr = explode(',', $customer->community);
                $customer->community = DB::table('community')->join("community_category", "community.community_category", "=", "community_category.id")->whereIn('community.id', $communityArr)->orderBy("community.community_category", "asc")->get();

                $introbadgeArr = explode(',', $customer->intro_badge);
                $customer->intro_badge = IntroBadge::select("id", "tag_text", "tag_color")->orderBy("tag_color", "asc")->whereIn('id', $introbadgeArr)->get();

                if($customer->plan_type == '0' || $customer->plan_type == null || $customer->plan_type == '') {
                    $customer->plan_type = "無料プラン";
                } else {
                    $customer->plan_type = PaidPlanType::select("paid_type")->where("id", $customer->plan_type)->first();
                }

                if($customer->identity_state == '0' || $customer->identity_state == null || $customer->identity_state == '') {
                    $customer->identity_state = 'ブロック';
                } else if($customer->identity_state == '1'){
                    $customer->identity_state = '承認';
                } else{
                    $customer->identity_state = 'block';
                }
                return response()->json(['data' => $customer, 'count' => $res_count, "type" => "success"]);
            }
        }
        return response()->json(['data' => $customers, 'count' => $res_count, "type" => "success"]);
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
        // $token = request()->header('X-CSRF-TOKEN');
        $input = $request->all();

        $user_nickname = $request->input('edtNickName');
        $birthday = $request->input('edtBirthday');
        $address = $request->input('edtAddress');
        $community = $request->input('edtCommunity');
        $height = $request->input('edtHeight');
        $heights = explode(".", $height);
        $body_type = $request->input('edtBodyType');
        $use_purpose = $request->input('edtUsePurpose');
        $intro_badge = $request->input('edtIntroBadge');
        $phone_number = $request->input('phone_number');
        $phone_token = $request->input('phone_token');
        $image = $request->input("image");
        $appleId = $request->input('appleId');
        $login_device = $request->input('login_device');
        if($user_nickname == '') {
            return response()->json(['result' =>"あなたのニックネームを入力してください", "type" => "warning"]);
        } else if($birthday == '') {
            return response()->json(['result' =>"あなたの誕生日を入力してください", "type" => "warning"]);
        } else if($address == '') {
            return response()->json(['result' =>"あなたの居住地を選択してください", "type" => "warning"]);
        } else if($community == '') {
            return response()->json(['result' =>"コミュニティを選択してください", "type" => "warning"]);
        } else if($height == '') {
            return response()->json(['result' =>"あなたの身長を入力してください", "type" => "warning"]);
        } else if($body_type == '') {
            return response()->json(['result' =>"あなたの体型を入力してください", "type" => "warning"]);
        } else if($use_purpose == '') {
            return response()->json(['result' =>"利用目的を入力してください", "type" => "warning"]);
        } else if($intro_badge == '') {
            return response()->json(['result' =>"あなたのイントロバッジを選択してください", "type" => "warning"]);
        } else if( !file_exists($request->file('image'))) {
            return response()->json(['result' =>"写真をアップロードしてください", "type" => "warning"]);
        }

        $newDate = Carbon::now();

        $data = DB::table('customer')->where('user_nickname', $user_nickname)->get();



        if(count($data) > 0) {
            return response()->json(['result' =>"すでに登録ニックネームです。", "type" => "warning"]);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = rand() . '.' .  $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('uploads'), $imageName);

            $customer = new Customer();
            $customer->user_nickname = $user_nickname;
            $customer->address = $address;
            $customer->birthday = $birthday;
            $customer->community = $community;
            $customer->height = $heights[0];
            $customer->body_type = $body_type;
            $customer->use_purpose = $use_purpose;
            $customer->intro_badge = $intro_badge;
            $customer->photo1 = $imageName;
            $customer->introduce = '';
            $customer->plan_type = '0';
            $customer->likes_rate = '0';
            $customer->coin = '0';
            $customer->identity_state = '0';
            $customer->created_at = $newDate;
            $customer->updated_at = $newDate;
            $customer->phone_number = $phone_number;
            $customer->phone_token = $phone_token;
            $customer->online_status = '1';
            $customer->private_age = '0';
            $customer->private_matching = '0';
            $customer->pay_user = '0';
            $customer->user_name = $appleId;
            $customer->login_device = $login_device;
            $customer->intro_dialog = "0";
            $customer->identity_dialog = "0";
            $customer->remember_token = "0";
            $customer->save();

            $uuu = DB::select("SELECT MAX(id) AS user_id FROM customer");

            DB::table('users')->insert([
                'user_id' => $uuu[0]->user_id,
                'month' => null,
                'available_date' => DB::raw('now()'),
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

            return response()->json(['result' =>"データ保存成功", "data" => $customer, "type" => "success"]);
        }
    }

    public function uploadAvatar(Request $request)
    {

        $token = request()->header('X-CSRF-TOKEN');
        $input = $request->all();

        $item_id = $request->input('item_id');
        $id = $request->input('id');
        $imageName = rand() . '.' . $request->file('image')->getClientOriginalName();
		$request->image->move(public_path('uploads'), $imageName);

        $data = DB::table('customer')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('customer')->where('id', $id)->update([
                "photo".$item_id => $imageName,
            ]);
            $data1 = DB::table('customer')->where('id', $id)->get();
            return response()->json(['result' =>"データが更新されました", "data" =>$data1[0], "type" => "success"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Customer $customer)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        // $data = DB::table('customer')->where('id', $id)->get();
        $data = DB::select('select a.*,b.available_date as pay_date from customer as a left join users as b on a.id = b.user_id where a.id = ?', array($id));
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('customer')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('customer')->where('id', $id)->delete();

            if (File::exists(public_path('uploads/' . $data[0]->community_photo))) {
                File::delete(public_path('uploads/' . $data[0]->community_photo));
            }
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }

    //Get IntroData
    public function getIntroInfo($id) {

        $intros = DB::table('customer')
            ->where('id', $id)
            ->get();

        if(count($intros) > 0)
        {
            $intro = $intros->first();

             return response()->json(['result' => $intro]);
        }
        return response()->json(['result' => "No Internet"]);
    }

    public function logout(Request $request) {

        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['id'];

        $users = DB::table('customer')
        ->where('id', $user_id)
        ->update([
            'online_status' => "0",
        ]);

        return response()->json(['result' =>"success", "type" => "success"]);
    }

    public function closeAccount(Request $request)
    {
        $user_id = $request['user_id'];

        DB::table('customer')->where('id', $user_id)->delete();

        DB::table('active_board')->where('user_id', $user_id)->delete();

        DB::table('identify')->where('user_id', $user_id)->delete();

        DB::table('likes_list')->where('sent_user_id', $user_id)->delete();

        DB::table('response_board')->where('res_user_id', $user_id)->delete();

        DB::table('response_board')->where('active_user_id', $user_id)->delete();

        DB::table('today_recomm')->where('sent_user_id', $user_id)->delete();

        DB::table('today_recomm')->where('received_user_id', $user_id)->delete();

        DB::table('violation_report')->where('violation_id', $user_id)->delete();

        DB::table('violation_report')->where('user_id', $user_id)->delete();

        DB::table('users')->where('user_id', $user_id)->delete();

        return response()->json(['result' =>"success", "type" => "success"]);
    }

    public function changePrivate(Request $request) {

        $token = request()->header('X-CSRF-TOKEN');

        $user_id = $request['user_id'];

        $index = $request['index'];

        $isVal = $request['isVal'];

        if($index == 0){

            $users = DB::table('customer')
            ->where('id', $user_id)
            ->update([
                'private_age' => $isVal,
            ]);
            return response()->json(['result' =>"success", "type" => "success"]);
        }
        if($index == "1"){
            $users = DB::table('customer')
            ->where('id', $user_id)
            ->update([
                'private_matching' => $isVal,
            ]);
            return response()->json(['result' =>"success", "type" => "success"]);
        }
        return response()->json(['result' =>"error", "type" => "error"]);
    }

    public function changePreview(Request $request) {

        $token = request()->header('X-CSRF-TOKEN');

        $see_id = $request['see_id'];

        $user_id = $request['user_id'];

        $newDate = Carbon::now();

        $data = DB::table('matching_data')->where('see_id', $see_id)->where('user_id',$user_id)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"success", "type" => "error"]);
        }
        else {

            $data = DB::insert("INSERT INTO matching_data (see_id, user_id, status, created_at, updated_at) VALUES (?,?,?,now(),now())", array($see_id,$user_id,'0'));
            return response()->json(['result' =>"success", "type" => "error"]);

        }
    }

    function addMonthsToDate($dateString, $numMonths) {
        $date = Carbon::parse($dateString);
        $newDate = $date->copy()->addMonthsNoOverflow($numMonths);

        // Check if the day of the new date exceeds the maximum days in the resulting month
        if ($date->day > $newDate->daysInMonth) {
            $newDate->day = $newDate->daysInMonth;
        }

        return $newDate->format('Y-m-d');
    }

    public function doPayment(Request $request) {

        $token = request()->header('X-CSRF-TOKEN');

        $month_val = $request['month'];

        $user_id = $request['user_id'];

        $resultDate = $request['resultDate'];

        $newDate = Carbon::now();

        $users = DB::table('users')
        ->where('user_id', $user_id)
        ->update([
            'available_date' => $resultDate,
            'created_at' => $newDate,
            'updated_at' => $newDate,

        ]);

        $res = DB::table('customer')->where('id', $user_id)->update([
            'pay_user' => '1',
        ]);
        if($res){
            return response()->json(['result' =>"success", "type" => "success"]);
        }
        else {
            return response()->json(['result' =>"error", "type" => "error"]);
        }
    }

    public function removeBoardData(Request $request) {

        $token = request()->header('X-CSRF-TOKEN');

        $board_id = $request['board_id'];

        $resData = DB::delete('delete from response_board where board_id = ?', array($board_id));

        $actData = DB::delete('delete from active_board where id = ?', array($board_id));

        if($actData){
            return response()->json(['result' =>"success", "type" => "success"]);
        }
        else {
            return response()->json(['result' =>"error", "type" => "error"]);
        }
    }

    public function appleLogin(Request $request) {

        $appleEmail = $request['appleEmail'];

        $res = DB::select("select * from customer where user_name = ?", array($appleEmail));

        if(count($res) > 0) {
            return response()->json(['result' => "success", "type" => "success", "msg" => "exist", "user_id" => $res[0]->id]);
        } else {
            return response()->json(['result' => "fail", "type" => "success", "msg" => "register"]);

            // DB::table('customer')->insert([
            //     'user_name' => $appleEmail,
            //     'user_nickname' => "",
            //     'address' => 0,
            //     'height' => "130.0",
            //     'body_type' => 0,
            //     'use_purpose' => 0,
            //     'community' => "",
            //     'intro_badge' => "",
            //     'introduce' => "",
            //     'online_status' => "1",
            //     'private_age' => "0",
            //     'private_matching' => "0",
            //     'pay_user' => "0",
            //     'created_at' => DB::raw('now()'),
            //     'updated_at' => DB::raw('now()')
            // ]);

            // $uuu = DB::select("SELECT MAX(id) AS user_id FROM customer");

            // return response()->json(['result' => true, "type" => "success", "msg" => "register", "user_id" => $uuu[0]->user_id]);
        }
    }

    //web customer register
    public function customerStore(Request $request) {
        $data = $request->all();

        $nickName = $request['nickName'];
        $birthday = $request['birthday'];
        $address = $request['address'];
        $height = $request['height'];
        $bodytype = $request['bodytype'];
        $use_purpose = $request['use_purpose'];
        $pay_user = $request['pay_user'];
        $pay_date = $request['pay_date'];
        $like_rate = $request['like_rate'] == null ? "0" : $request['like_rate'];
        $coin = $request['coin'] == null ? "0" : $request['coin'];
        $blood_type = $request['blood_type']== "null" ? null : $request['blood_type'];
        $education = $request['education']== "null" ? null : $request['education'];
        $alchol = $request['alchol']== "null" ? null : $request['alchol'];
        $ciga = $request['ciga']== "null" ? null : $request['ciga'];
        $annual_income = $request['annual_income']== "null" ? null : $request['annual_income'];
        $identity = $request['identity'] ?? "0";
        $remember_token = $request['remember_token'] ?? "1";
        $community = $request['community'];
        $introbadge = $request['introbadge'];
        $edittype = $request['edittype'];
        $login_id = $request['login_id'];
        // $login_info = $request['login_info'];
        $edittype = $request['edittype'];
        $photo_img = $request['image'];
        $uid = $request['uid'];

        if($edittype == "1") {
            if($photo_img == "undefined"){
                if($identity == "-1")
                {
                    $data = DB::update('update customer set  user_nickname= ?, address= ?, birthday= ?, community= ?, height= ?, body_type= ?, use_purpose= ?, intro_badge= ?, pay_user= ?, likes_rate= ?, coin= ?, identity_state= ?, updated_at =now(), blood_type =?, education=?, cigarette = ?, alcohol= ?, annual_income = ?, remember_token = ?  where id = ?', array($nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,"0",$blood_type,$education,$ciga,$alchol,$annual_income,"1",$uid));
                }
                else if($identity == "0") {
                    $data = DB::update('update customer set  user_nickname= ?, address= ?, birthday= ?, community= ?, height= ?, body_type= ?, use_purpose= ?, intro_badge= ?, pay_user= ?, likes_rate= ?, coin= ?, identity_state= ?, updated_at =now(), blood_type =?, education=?, cigarette = ?, alcohol= ?, annual_income = ?, remember_token = ?  where id = ?', array($nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,"0",$blood_type,$education,$ciga,$alchol,$annual_income,"0",$uid));
                }
                else {
                    $data = DB::update('update customer set  user_nickname= ?, address= ?, birthday= ?, community= ?, height= ?, body_type= ?, use_purpose= ?, intro_badge= ?, pay_user= ?, likes_rate= ?, coin= ?, identity_state= ?, updated_at =now(), blood_type =?, education=?, cigarette = ?, alcohol= ?, annual_income = ? where id = ?', array($nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,$identity,$blood_type,$education,$ciga,$alchol,$annual_income,$uid));
                }


                if($pay_user == "1")
                {
                    DB::update('update users set available_date = ? where user_id = ?', array($pay_date,$uid));
                }
                else{
                    DB::update('update users set available_date = now() where user_id = ?', array($uid));
                }

                if($data) {
                    return response()->json(['result' => "success", "msg" => "正確に保管されました。"]);
                }
                else{
                    return response()->json(['result' => "error", "msg" => "一部の問題でアーカイブが失敗しました。"]);
                }
            }
            else {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $imageName = rand() . '.' .  $request->file('image')->getClientOriginalName();

                $request->image->move(public_path('uploads'), $imageName);

                if($identity == "-1")
                {
                    $data = DB::update('update customer set  user_nickname= ?, address= ?, birthday= ?, community= ?, height= ?, body_type= ?, use_purpose= ?, intro_badge= ?, pay_user= ?, likes_rate= ?, coin= ?, identity_state= ?, updated_at =now(), blood_type =?, education=?, cigarette = ?, alcohol= ?, annual_income = ?, photo1 = ?, remember_token = ?  where id = ?', array($nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,"0",$blood_type,$education,$ciga,$alchol,$annual_income,$imageName,"1",$uid));
                }
                else if($identity == "0") {
                    $data = DB::update('update customer set  user_nickname= ?, address= ?, birthday= ?, community= ?, height= ?, body_type= ?, use_purpose= ?, intro_badge= ?, pay_user= ?, likes_rate= ?, coin= ?, identity_state= ?, updated_at =now(), blood_type =?, education=?, cigarette = ?, alcohol= ?, annual_income = ?, photo1 = ?, remember_token = ?  where id = ?', array($nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,"0",$blood_type,$education,$ciga,$alchol,$annual_income,$imageName,"0",$uid));
                }
                else {
                    $data = DB::update('update customer set  user_nickname= ?, address= ?, birthday= ?, community= ?, height= ?, body_type= ?, use_purpose= ?, intro_badge= ?, pay_user= ?, likes_rate= ?, coin= ?, identity_state= ?, updated_at =now(), blood_type =?, education=?, cigarette = ?, alcohol= ?, annual_income = ?, photo1 = ?, remember_token = ?  where id = ?', array($nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,$identity,$blood_type,$education,$ciga,$alchol,$annual_income,$imageName,"0",$uid));
                }


                if($pay_user == "1")
                {
                    DB::update('update users set available_date = ? where user_id = ?', array($pay_date,$uid));
                }
                else{
                    DB::update('update users set available_date = now() where user_id = ?', array($uid));
                }

                if($data) {
                    return response()->json(['result' => "success", "msg" => "正確に保管されました。"]);
                }
                else{
                    return response()->json(['result' => "error", "msg" => "一部の問題でアーカイブが失敗しました。"]);
                }
            }

        }
        else {
            $validation = DB::select('select * from customer where phone_number = ?', array($request['login_id']));

            if(count($validation) > 0)
            {
                return response()->json(['result' => "error", "msg" => "電話番号が重複しています。"]);
            }
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = rand() . '.' .  $request->file('image')->getClientOriginalName();

            $request->image->move(public_path('uploads'), $imageName);

            if($identity == "-1")
            {
                $data = DB::insert("insert into customer (phone_number, user_nickname, address, birthday, community, height, body_type, use_purpose, intro_badge, pay_user, likes_rate, coin, identity_state, created_at, updated_at, blood_type,education, cigarette, alcohol, annual_income, photo1, plan_type,online_status,private_age,private_matching, introduce, remember_token) values (?,?,?,?,?,?,?,?,?,?,?,?,?,now(),now(),?,?,?,?,?,?,?,?,?,?,?,?)",array($login_id, $nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,"0",$blood_type,$education,$ciga,$alchol,$annual_income,$imageName,"0","0","0","0","","1"));
            }
            else if($identity == "0") {
                $data = DB::insert("insert into customer (phone_number, user_nickname, address, birthday, community, height, body_type, use_purpose, intro_badge, pay_user, likes_rate, coin, identity_state, created_at, updated_at, blood_type,education, cigarette, alcohol, annual_income, photo1, plan_type,online_status,private_age,private_matching, introduce, remember_token) values (?,?,?,?,?,?,?,?,?,?,?,?,?,now(),now(),?,?,?,?,?,?,?,?,?,?,?,?)",array($login_id, $nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,"0",$blood_type,$education,$ciga,$alchol,$annual_income,$imageName,"0","0","0","0","","0"));
            }
            else {
                $data = DB::insert("insert into customer (phone_number, user_nickname, address, birthday, community, height, body_type, use_purpose, intro_badge, pay_user, likes_rate, coin, identity_state, created_at, updated_at, blood_type,education, cigarette, alcohol, annual_income, photo1, plan_type,online_status,private_age,private_matching, introduce, remember_token) values (?,?,?,?,?,?,?,?,?,?,?,?,?,now(),now(),?,?,?,?,?,?,?,?,?,?,?,?)",array($login_id, $nickName,$address,$birthday,$community,$height,$bodytype,$use_purpose,$introbadge,$pay_user,$like_rate,$coin,$identity,$blood_type,$education,$ciga,$alchol,$annual_income,$imageName,"0","0","0","0","","0"));
            }

            $uuu = DB::select("SELECT MAX(id) AS user_id FROM customer");

            if($pay_user == "1")
            {
                DB::insert('insert into users (user_id, available_date, created_at, updated_at) values (?, ?, now(), now())', array($uuu[0]->user_id,$pay_date));
            }
            else{
                DB::insert('insert into users (user_id, available_date, created_at, updated_at) values (?, now(), now(), now())', array($uuu[0]->user_id));
            }

            if($data) {
                return response()->json(['result' => "success", "msg" => "正確に保管されました。"]);
            }
            else{
                return response()->json(['result' => "error", "msg" => "一部の問題でアーカイブが失敗しました。"]);
            }
        }

    }

    public function removeCustomer(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('customer')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('customer')->where('id', $id)->delete();
            DB::table('users')->where('user_id', $id)->delete();
            DB::table('active_board')->where('user_id', $id)->delete();
            DB::table('block_list')->where('blocked_by', $id)->delete();
            DB::table('block_list')->where('blocked_user_id', $id)->delete();
            DB::table('identify')->where('user_id', $id)->delete();
            DB::table('likes_list')->where('sent_user_id', $id)->delete();
            DB::table('likes_list')->where('received_user_id', $id)->delete();
            DB::table('matching_data')->where('see_id', $id)->delete();
            DB::table('matching_data')->where('user_id', $id)->delete();
            DB::table('response_board')->where('board_id', $id)->delete();
            DB::table('response_board')->where('res_user_id', $id)->delete();
            DB::table('today_recomm')->where('sent_user_id', $id)->delete();
            DB::table('today_recomm')->where('received_user_id', $id)->delete();
            DB::table('violation_report')->where('violation_id', $id)->delete();
            DB::table('violation_report')->where('user_id', $id)->delete();
            if (File::exists(public_path('uploads/' . $data[0]->photo1))) {
                File::delete(public_path('uploads/' . $data[0]->photo1));
            }
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }

    public function admin_manager()
    {
        // $data = DB::select("select * from admin_users where id <> ?", array(session('userInfo')->id));
        $data = DB::select("select * from admin_users");
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.admin',["manager" => $data]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }

    public function get_admin_info(Request $request)
    {
        $data = DB::select("select * from admin_users where id = ?", array($request['id']));

        return response()->json(['data' => $data]);
    }

    public function admin_save_data(Request $request)
    {
        $pass = $request['password'];

        $token = request()->header('X-CSRF-TOKEN');

        $currentTime = Carbon::now();

        if($request['password'] == "123456789")
        {
            $pass = "123456789";
        }
        if($request['id'] == "")
        {
            $users = DB::table('admin_users')->insert([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $pass,
            ]);
            if($users)
            {
                return response()->json(['result' => "成功しました。", 'type'=>"success"]);

            }
            return response()->json(['result' => "失敗しました。", 'type'=>"error"]);
        }
        else{
            $users = DB::table('admin_users')->where('id',$request['id'])->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $pass,
            ]);
            return response()->json(['result' => "成功しました。", 'type'=>"success"]);
        }
    }

    public function remove_admin_data(Request $request)
    {
        $users = DB::table('admin_users')->where('id',$request['id'])->delete();

        if($users)
        {
            return response()->json(['result' => "成功しました。", 'type'=>"success"]);
        }
        return response()->json(['result' => "失敗しました。", 'type'=>"error"]);
    }

    public function getPhoneValidation(Request $request)
    {
        $phone = $request['phone_number'];
        $token = $request['token'];

        $users = DB::select('select * from customer where phone_number = ?', array($phone));

        if(count($users) > 0){

            $data = DB::update("update customer set phone_token = ? where phone_number = ?", array($token, $phone));

            return response()->json(['result'=>"success", "type" => "success"]);

        }

        return response()->json(['result' => "error", 'type'=>"error"]);
    }

    public function updateProfileAdsData(Request $request)
    {

        $user_id = $request['uid'];

        $dialog_type = $request['type'];

        if($dialog_type == "identity"){
            $data = DB::update('update customer set identity_dialog = ? where id = ?', array('2', $user_id));
            if($data) {
                return response()->json(['result' => "success", 'type'=>"success"]);
            }
            return response()->json(['result' => "error", 'type'=>"error"]);
        }
        else{
            $data = DB::update('update customer set intro_dialog = ? where id = ?', array('2', $user_id));
            if($data){
                return response()->json(['result' => "success", 'type'=>"success"]);
            }
            return response()->json(['result' => "error", 'type'=>"error"]);
        }

    }

    public function validPhoneNumber(Request $request)
    {

        $phone_number = $request['phone_number'];

        $data = DB::select("select id from customer where phone_number = ?", array($phone_number));

        if(count($data) > 0) {
            return response()->json(['result' => $data[0]->id, 'type'=>"success"]);
        }

        else {
            return response()->json(['result' => "empty", 'type'=>"error"]);
        }

    }

    public function payLog(Request $request)
    {

        $amount = $request['amount'];

        $user_id = $request['user_id'];

        $newDate = Carbon::now()->month;

        $available_month = "0";

        $like_count = "0";

        if($amount == "6000") {
            $available_month = "12";
            $like_count = "100";
        }
        else if($amount == "4800") {
            $available_month = "6";
            $like_count = "100";
        }
        else if($amount == "3000"){
            $available_month = "3";
            $like_count = "100";
        }
        else {
            $available_month = "1";
            $like_count = "100";
        }


        $futureDateFormatted = Carbon::now()->addMonths($available_month)->isoFormat('YYYY-MM-DD');

        $data = DB::select("select * from customer where id = ?", array($user_id));

        if(count($data) > 0) {
            $ownerCoin = $data[0]->likes_rate;
            DB::update('update customer set pay_user = ?, likes_rate = ? where id = ?', array('1', $ownerCoin + $like_count, $user_id));
            DB::update('update users set available_date = ?, month = ? where user_id = ?', array($futureDateFormatted,$available_month,$user_id));
            DB::insert('insert into pay_log (user_id, amount, pay_type, created_at) values (?, ?, ?, now())', array($user_id,$amount,'0'));
            return response()->json(['result' => "success", 'type' => "success"]);
        }

        else {
            return response()->json(['result' => "empty", 'type'=>"error"]);
        }

    }

    public function payCoin(Request $request)
    {

        $amount = $request['amount'];

        $coin = $request['coin'];

        $user_id = $request['user_id'];

        $data = DB::select("select * from customer where id = ?", array($user_id));

        if(count($data) > 0) {
            DB::update('update customer set coin = ? where id = ?', array($data[0]->coin + $coin, $user_id));
            DB::insert('insert into pay_log (user_id, amount, pay_type, created_at) values (?, ?, ?, now())', array($user_id,$amount,'1'));
            return response()->json(['result' => "success", 'type' => "success"]);
        }

        else {
            return response()->json(['result' => "empty", 'type'=>"error"]);
        }

    }

    public function reviewSaveData(Request $request)
    {

        $starRating = $request['starRating'];

        $matchingCount = $request['matchingCount'];

        $user_id = $request['user_id'];

        $data = DB::select("select * from review where user_id = ? and type = ?", array($user_id, $matchingCount));

        if(count($data) > 0) {
            DB::update('update review set rating = ? where user_id = ?', array($starRating, $user_id));
            return response()->json(['result' => "success", 'type' => "success"]);
        }

        else {
            DB::insert('insert into review (user_id, rating, type, created_at, updated_at) values (?, ?, ?, now(), now())', array($user_id,$starRating,$matchingCount));
            return response()->json(['result' => "success", 'type'=>"success"]);
        }
        return response()->json(['result' => "error", 'type' => "error"]);
    }
}
