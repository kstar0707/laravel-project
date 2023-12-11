<?php

namespace App\Http\Controllers;

use App\Models\Bodytype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function getData(Request $request)
    {
        $currentDate = "";
        if($request['year'] == null){
            $currentDate = Carbon::now()->year;
        }
        else{
            $currentDate = $request['year'];
        }
        $all_count = DB::select('select COUNT(id) as count from customer');
        $month_count = DB::select('SELECT
                                    months.month_number AS login_month,
                                    COALESCE(COUNT(customer.id), 0) AS login_number
                                FROM
                                    (SELECT 1 AS month_number
                                    UNION ALL SELECT 2
                                    UNION ALL SELECT 3
                                    UNION ALL SELECT 4
                                    UNION ALL SELECT 5
                                    UNION ALL SELECT 6
                                    UNION ALL SELECT 7
                                    UNION ALL SELECT 8
                                    UNION ALL SELECT 9
                                    UNION ALL SELECT 10
                                    UNION ALL SELECT 11
                                    UNION ALL SELECT 12) AS months
                                LEFT JOIN
                                    customer ON EXTRACT(YEAR FROM customer.created_at) = ?
                                            AND EXTRACT(MONTH FROM customer.created_at) = months.month_number
                                GROUP BY
                                    months.month_number
                                ORDER BY
                                    login_month', array($currentDate));
        $pay_user = DB::select("SELECT
                                    SUM(CASE WHEN pay_user = 1 THEN 1 ELSE 0 END) AS yuro,
                                    SUM(CASE WHEN pay_user = 0 THEN 1 ELSE 0 END) AS muro
                                FROM customer
                                WHERE YEAR(created_at) = ?", array($currentDate));

        $device = DB::select("SELECT
                                SUM(CASE WHEN login_device = 1 THEN 1 ELSE 0 END) AS android,
                                SUM(CASE WHEN login_device = 0 THEN 1 ELSE 0 END) AS ios
                                FROM customer
                                WHERE YEAR(created_at) = ?", array($currentDate));
        $residence_dt = DB::select("SELECT R.id,R.residence,
                                COALESCE(COUNT(C.address), 0) AS residence_count
                                FROM residence AS R
                                LEFT JOIN customer AS C ON R.id = C.address AND YEAR(C.created_at) = ?
                                GROUP BY R.id, R.residence", array($currentDate));
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.dashboard', ['all_count' => $all_count[0],'month_count' => $month_count, "pay_user" => $pay_user, "device" => $device, "residence_dt"=>$residence_dt, "selectedYear"=>$request['year']]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }

    }
}
