<?php

namespace App\Http\Controllers;

use App\Models\Identify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use File;

class IdentityController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$identifydatas = Identify::select("*")->leftjoin("customer", "identify.user_id", "=", "customer.id")->get();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.identity', ['identifydatas' => $identifydatas]);
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
	 * @param  \App\Models\Identify  $identify
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Identify $identify)
	{
		$token = request()->header('X-CSRF-TOKEN');

		$id = $request['id'];

		$data = DB::table('identify')->leftjoin("customer", "identify.user_id", "=", "customer.id")
		->leftJoin("residence", "residence.id", "=", "customer.address")->where('identify.user_id', $id)
		->get();

        // $data = DB::select('select a.*,   from identify as a left join customer as b on a.user_id = b.id left join residence as c on c.id = b.address where a.id = ?', array($id) );

		for ($i=0; $i < count($data); $i++) {
			$temp = DB::table('community')->whereIn("id", explode(",", $data[$i]->community))->get();
			$data[$i]->community = "";
			for ($j=0; $j < count($temp); $j++) {
				$str = count($temp) - 1 != $j ? "、" : "";
				$data[$i]->community .= $temp[$j]->community_name . $str;
			}
			$temp = DB::table('intro_badge')->whereIn("id", explode(",", $data[$i]->intro_badge))->get();
			$data[$i]->intro_badge = "";
			for ($j=0; $j < count($temp); $j++) {
				$str = count($temp) - 1 != $j ? "、" : "";
				$data[$i]->intro_badge .= $temp[$j]->tag_text . $str;
			}
			$temp = DB::table('body_type')->where("id", "=", $data[$i]->body_type)->first();
			$data[$i]->body_type = $temp->type_name;
			$temp = DB::table('use_purpose')->where("id", "=", $data[$i]->use_purpose)->first();
			$data[$i]->use_purpose = $temp->use_purpose;
			$temp = DB::table('paid_plan_type')->where("id", "=", $data[$i]->plan_type)->first();
			if (!is_null($temp)) {
				$data[$i]->plan_type = $temp->paid_type;
			} else {
				$data[$i]->plan_type = "無給";
			}
		}
		return response()->json(['data' => $data]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Identify  $identify
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Identify $identify)
	{
		//
	}

	// !-----> id card upload

	public function uploadIdentifyImage(Request $request) {

        $input = $request->all();

        $user_id = $request->input('user_id');

        $nickName = DB::select('select * from customer where id = ?', array($user_id));

        $user_name = $request->input('user_name');

        $nick_name = $nickName[0]->user_nickname;

        $newDate = Carbon::now();
        $request_date = $newDate;
        $identity_type = $request->input('identity_type');

		if( !file_exists($request->file('image'))) {
			return response()->json(['result' =>"写真をアップロードしてください", "type" => "warning"]);
		}

		$request->validate([
			'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);

		$imageName = rand() . '.' . $request->file('image')->getClientOriginalName();
		$request->image->move(public_path('uploads'), $imageName);

        $data = DB::select("select * from identify where user_id = ?", array($user_id));

        if(count($data) > 0)
        {
            DB::update('update identify set identity_photo = ?, request_date = now() where user_id = ?', array($imageName, $user_id));
            DB::update('update customer set identity_state = ? where id = ?', array(0, $user_id));
            return response()->json(['result' =>"写真をアップロードしてください", "type" => "success"]);
        }
        DB::update('update customer set remember_token = ? where id = ?', array(0, $user_id));
        $identify = new Identify();

		$identify->user_id = $user_id;
		$identify->user_name = $user_name;
		$identify->nick_name = $nick_name;
		$identify->request_date = $request_date;
		$identify->identity_type = $identity_type;
		$identify->identity_photo = $imageName;
		$identify->created_at = $request_date;
		$identify->updated_at = $request_date;

		$identify->save();
		return response()->json(['result' =>"写真をアップロードしてください", "type" => "success"]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Identify  $identify
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Identify $identify)
	{
		$token = request()->header('X-CSRF-TOKEN');
		$id = $request['id'];
		$type = $request['type'];

		$newDate = Carbon::now();
		if($type == 'allow') {
			DB::table('identify')->where('user_id', $id)->delete();

			DB::table('customer')->where('id', $id)->update([
				'identity_state' => "1",
                'identity_dialog' => "1",
                'remember_token' => "1",
				'updated_at' => $newDate,
			]);

			return response()->json(['result' =>"許可された", "type" => "success"]);
		} else {
			DB::table('identify')->where('user_id', $id)->update([
				'identity_type' => $type,
				'updated_at' => $newDate,
			]);

			DB::table('customer')->where('id', $id)->update([
				'identity_state' => "2",
                'remember_token' => "1",
                'identity_dialog' => "0",
				'updated_at' => $newDate,
			]);
            DB::table('identify')->where('user_id', $id)->delete();
			return response()->json(['result' =>"ブロックされました", "type" => "success"]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Identify  $identify
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Identify $identify)
	{
		//
	}
}
