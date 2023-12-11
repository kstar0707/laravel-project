<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function boot()
    {
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.user', ['users' => $users]);
        } else {
            // If the session information is not valid, redirect to the login page
            return view('login'); // Replace 'login' with your actual login route name
        }


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input data
        $this->validate($request, [
            'login_id' => 'required',
            'password' => 'required',
        ]);
        // Attempt to log in the user
        $credentials = $request->only('login_id', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('dashboard');
        } else {
            // Authentication failed...
            return back()->withErrors(['login_id' => 'Invalid login credentials.']);
        }

    }

    /**
     * login for mobile
     */

     public function loginAction(Request $request)
    {

        // token
        $token = request()->header('X-CSRF-TOKEN');

        $loginId = $request['loginId'];

        $password = $request['password'];


        $users = DB::table('admin_users')
            ->where('name', $loginId)
            ->orWhere('email', $loginId)
            ->get();
        if(count($users) > 0)
        {
            $user = $users->first();

            if ($password == $user->password) {
                // Store the information in the session

                $request->session()->put('userInfo', $user);
                return response()->json([
                    "result" => "Login Successfully",
                    "type" => "success"
                ]);

            } else {
               // Password is incorrect
               return response()->json(['result' =>"Invalid Password", "type" => "warning"]);
            }
        }
        else{

            return response()->json(['result' =>"Invalid Login", "type" => "error"]);
        }
        return response()->json(['result' =>"No Internet", "type" => "error"]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        $this->index();
    }
}
