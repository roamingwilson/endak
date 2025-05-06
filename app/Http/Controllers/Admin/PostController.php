<?php

namespace App\Http\Controllers\Admin;

use App\Models\GeneralOrder;
use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Services;

class PostController extends Controller
{
    public function index(Request  $request){
        $this->authorize('Show_Admin_Post');


        // $ids = $request->bulk_ids;
        // $now = Carbon::now()->toDateTimeString();




        // if ($request->bulk_action_btn === 'update_status' && $request->status && is_array($ids) && count($ids)) {
        //     $data = ['admin_status' => $request->status];


        //     Post::whereIn('id', $ids)->update($data);
        //     return back()->with('success', __('general.updated_successfully'));
        // }
        // if ($request->bulk_action_btn === 'delete' &&  is_array($ids) && count($ids)) {


        //     Post::whereIn('id', $ids)->delete();
        //     return back()->with('success', __('general.deleted_successfully'));
        // }
        // $posts = Post::orderBy("created_at","desc")->paginate(10);
        $service = Services::latest()->paginate(10);
        return view("admin.posts.index", compact("service"));
    }


    // public function show($id){
    //     $service = Services::findOrFail($id);

    // }
    public function showOrdersForservice(){
        $orders = GeneralOrder::latest()->paginate(10);
        return view('admin.posts.show_service_order',compact('orders'));
    }


}
