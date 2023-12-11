<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\RecommCustomers;

class RecommCustomerController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRecommCusomters(Request $request)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $currentDate = now()->toDateString();

        $userBirthday = DB::table('customer')->select(DB::raw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) as age'))->where('id', $id)->first();
        // return response()->json(['result' => $userBirthday->age, "type" => "success"]);
        // first way
        // $recommendations = DB::select("
        //     SELECT *
        //     FROM customer
        //     WHERE ((identity_state = 'verified'
        //         AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) = :userBirthday)
        //         OR last_login_date <= DATE_SUB(:currentDate, INTERVAL 1 MONTH))
        //         AND id NOT IN (
        //             SELECT DISTINCT proposed_user_id, accepted_user_id
        //             FROM matching_data
        //             WHERE proposed_user_id = :user_id OR accepted_user_id = :user_id
        //         )
        //     ORDER BY RAND()
        //     LIMIT 5",
        //     ['userBirthday' => $userBirthday->age, 'currentDate' => $currentDate, 'user_id' => $id]
        // );

        $oneMonthAgo = now()->subMonth();
        $recommendedUsers = User::where('id', '!=', $id)
            ->whereBetween('age', [$userBirthday->age - 5, $userBirthday->age + 5])
            ->where('created_at', '>=', $oneMonthAgo)
            ->orderBy('confirmed', 'desc')
            ->get();

        $matchedUserIds = Matching::where('proposed_user_id', $currentUserId)->pluck('accepted_user_id')->toArray();

        $recommendedUsers = $recommendedUsers->reject(function ($user) use ($matchedUserIds) {
            return in_array($user->id, $matchedUserIds);
        });

        $matchedUserIds = Matching::where('accepted_user_id', $currentUserId)->pluck('proposed_user_id')->toArray();
        
        $recommendedUsers = $recommendedUsers->reject(function ($user) use ($matchedUserIds) {
            return in_array($user->id, $matchedUserIds);
        });
        return response()->json(['data' => $recommendedUsers, "type" => "success"]);
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
     * @param  \App\Models\RecommCustomers  $recommCustomers
     * @return \Illuminate\Http\Response
     */
    public function show(RecommCustomers $recommCustomers)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RecommCustomers  $recommCustomers
     * @return \Illuminate\Http\Response
     */
    public function edit(RecommCustomers $recommCustomers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RecommCustomers  $recommCustomers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecommCustomers $recommCustomers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RecommCustomers  $recommCustomers
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecommCustomers $recommCustomers)
    {
        //
    }
}
