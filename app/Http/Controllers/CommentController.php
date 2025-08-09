<?php

namespace App\Http\Controllers;

use App\Models\GeneralComments;
use App\Models\GeneralOrder;
use Illuminate\Http\Request;
use App\Models\CommentsFiles;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\CommentServices;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{
    public $comment_service;

    public function __construct(CommentServices $comment_service)
    {
        $this->comment_service = $comment_service;
    }
    public function store(Request $request){
        // $request->validate([
        //     "department_id" => "required",
        // ]);
        $request->validate([
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $post = Post::where('id' , $request->post_id)->first();
        $customer = User::where('id' ,$post->user_id )->first();
        $user = auth()->user();
        $data = $request->except('image');
        $data['user_id'] = $user->id;

        $comment = $this->comment_service->store($data);
        if ($request->hasFile('image')) {
            $paths = [];

            foreach ($request->file('image') as $image) {
                $path = $image->store('public/comments');
                CommentsFiles::create([
                    'comment_id' => $comment->id,
                    'file'       => $path,
                ]);
            }
        }
        if($comment){
            $customer->notify(new CommentNotification([
                'id' => $comment->id,
                'title' => "قدم $user->first_name  لك عرضا",
                'body' => "$comment->description",
                'url' => route('show_myservice' , $request->post_id)
            ]));
        }
        return redirect()->back()->with('success','Add Seccessfully');
    }
        public function comments($id){

          // جلب جميع التعليقات التي تخص مزود الخدمة
         $comments = GeneralComments::where('service_provider', $id)->latest()->paginate(5);




        return view('front_office.comments.my_comments' , compact('comments'));
    }
}
