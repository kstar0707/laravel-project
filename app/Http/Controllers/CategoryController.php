<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        if(session()->has('userInfo')) {
            // If the session information is valid, return the dashboard view
            return view('admin.content.category', ['categories' => $categories]);
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
        $token = request()->header('X-CSRF-TOKEN');
        $newData =  $request->input('category_name');
        $image = $request->input("image");

        $newDate = Carbon::now();

        $data = DB::table('community_category')->where('category_name', $newData)->get();

        if(count($data) > 0) {
            return response()->json(['result' =>"同じデータが存在します", "type" => "warning"]);
        } else {
            if(file_exists($request->file('image'))) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $imageName = now()->format('YmdHis') . $request->file('image')->getClientOriginalName();
                $request->image->move(public_path('uploads/category'), $imageName);

                $category = new Category();
                $category->category_name = $newData;
                $category->created_at = $newDate;
                $category->updated_at = $newDate;
                $category->category_image = $imageName;
                $category->save();
                return response()->json(['result' =>"データ保存成功", "type" => "success"]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $name =  $request->input('category_name');
        $id = $request->input('edtCategoryId');
        $newDate = Carbon::now();
        $data = DB::table('community_category')->where('id', $id)->get();

        if(count($data) > 0) {
            if(file_exists($request->file('image'))) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $imageName = now()->format('YmdHis') . $request->file('image')->getClientOriginalName();
                $request->image->move(public_path('uploads/category'), $imageName);
                DB::table('community_category')->where('id', $id)->update([
                    'category_name' => $name,
                    'updated_at' => $newDate,
                    'category_image' => $imageName,
                ]);
                return response()->json(['result' =>"データが更新されました", "type" => "success"]);
            } else {
                DB::table('community_category')->where('id', $id)->update([
                    'category_name' => $name,
                    'updated_at' => $newDate,
                ]);
                return response()->json(['result' =>"データが更新されました", "type" => "success"]);
            }
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        $token = request()->header('X-CSRF-TOKEN');
        $id = $request['id'];

        $data = DB::table('community_category')->where('id', $id)->get();

        if(count($data) > 0) {
            DB::table('community_category')->where('id', $id)->delete();
            return response()->json(['result' =>"データ削除成功", "type" => "success"]);
        } else {
            return response()->json(['result' =>"そういうデータは存在しません", "type" => "error"]);
        }
    }
}
