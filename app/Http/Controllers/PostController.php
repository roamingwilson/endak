<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Department;
use App\Models\ProductItems;
use Illuminate\Http\Request;
use App\Services\PostServices;
use App\Models\ServiceProducts;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use App\Models\FurnitureTransportationServiceProducts;

class PostController extends Controller
{
    public $post_service;

    public function __construct(PostServices $post_service)
    {
        $this->post_service = $post_service;
    }

    public function index($id)
    {
        $posts = $this->post_service->getAllWith('department_id', $id);
        // dd($posts);
        return view('front_office.posts.all', compact('posts'));
    }
    public function create($id)
    {
        // dd($id);
        $department = Department::findOrFail($id);
        $products = $department->products;
        // dd($products);
        return view('front_office.posts.create', compact('department' , 'products'));
    }
    public function store(Request $request)
    {

        $current_url = url()->previous();
        $url = explode('/', $current_url);
        $department_id = (int) end($url);


        $quantities = $request->quantities;
        $disassembly = $request->disassembly;
        $installation = $request->installation;
        // // dd($quantities);
        // foreach($quantities as $key => $value){
        //     dd($value  ,$key);

        //     if (isset($quantities[$value]) && !is_null($quantities[$value]) ) {
        //         dd($quantities[$value] ,$key);

        //         // FurnitureTransportationServiceProducts::create([
        //         //     'service_id' => $post->id,
        //         //     'product_id' => $key,
        //         //     'quantity' => $quantities[$value],
        //         //     'installation'=> $installation[$value] ?? 0,
        //         //     'disassembly'=>  $disassembly[$value] ?? 0,
        //         // ]);
        //     }
        // }
        $user = auth()->user()->id;
        $data = $request->except(['installation' , 'quantities' , 'disassembly']);
        $data['user_id'] = $user;
        $data['department_id'] = $department_id;
        $post = $this->post_service->store($data);
        if($post){
            foreach($quantities as $key => $value){
                if (!is_null($value) ) {
                    ServiceProducts::create([
                        'service_id' => $post->id,
                        'product_id' => $key,
                        'quantity' => $value,
                        'installation'=> $installation[$key] ?? 0,
                        'disassembly'=>  $disassembly[$key] ?? 0,
                    ]);
                }
            }
        }
        // $is_created = FurnitureTransportationService::create([
        //     'from_city'                     => $request->from_city,
        //     'from_neighborhood'             => $request->from_neighborhood,
        //     'from_home'                     => $request->from_home,
        //     'to_city'                       => $request->to_city,
        //     'to_neighborhood'               => $request->to_neighborhood,
        //     'to_home'                       => $request->to_home,
        //     'notes'                         => $request->notes,
        //     'user_id'                       => auth()->id(),
        // ]);
        // if($is_created){
        //     foreach($request->selected_products as $key => $value){
        //         if (isset($quantities[$value]) && !is_null($quantities[$value]) ) {
        //             FurnitureTransportationServiceProducts::create([
        //                 'service_id' => $is_created->id,
        //                 'product_id' => $value,
        //                 'quantity' => $quantities[$value],
        //                 'installation'=> $installation[$value] ?? 0,
        //                 'disassembly'=>  $disassembly[$value] ?? 0,
        //             ]);
        //         }
        //     }
        // }
        // return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح');


        // if($request->selected_products){

        // foreach ($request->input('selected_products') as $productId) {
        //     $quantity = $request->input("quantities.$productId");

        //     if ($quantity > 0) {
        //         ProductItems::create([
        //             'product_id' => $productId,
        //             'quantity' => $quantity,
        //             'post_id' => $post->id,
        //         ]);
        //     }
        // }

        return redirect()->route('web.posts' , $department_id)->with('success','تم تقديم الخدمة بنجاح');
    }
    public function show($id)
    {
        $post = $this->post_service->show($id);
        $userUnreadNotification = auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
        return view('front_office.posts.show', compact('post'));
    }

    public function my_posts($id){
        $posts = Post::where('user_id', $id)->paginate(6);

        return view('front_office.posts.my_posts' , compact('posts'));
    }




    // public function uploadLargeFiles(Request $request)
    // {
    //     $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

    //     if (!$receiver->isUploaded()) {
    //         // file not uploaded
    //     }

    //     $fileReceived = $receiver->receive(); // receive file
    //     if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
    //         $file = $fileReceived->getFile(); // get file
    //         $extension = $file->getClientOriginalExtension();
    //         $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
    //         $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
    //         $disk = Storage::disk(config('filesystems.default'));
    //         $path = $disk->putFileAs('videos', $file, $fileName);
    //         $request->session()->put('sharedValue', $path);
    //         // delete chunked file
    //         unlink($file->getPathname());
    //         return $path;
    //     }

    //     // otherwise return percentage informatoin
    //     $handler = $fileReceived->handler();
    //     return [
    //         'done' => $handler->getPercentageDone(),
    //         'status' => true];
    // }
}
