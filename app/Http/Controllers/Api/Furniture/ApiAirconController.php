<?php

namespace App\Http\Controllers\Api\furniture;

use App\Http\Controllers\Controller;
use App\Models\AirConditionOrder;
use App\Models\AirConditionService;
use App\Models\GeneralComments;
use App\Models\GeneralImage;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;

class ApiAirconController extends Controller
{
    public function index()
    {
        $inputs_name = [
       'user_id' => "auth user id ",
            'spilt' => "1 if selected else 0",
            'window' => "1 if selected else 0",
            'clean' => "1 if selected else 0",
            'feryoun' => "1 if selected else 0",
            'maintance' => "1 if selected else 0",
            'quantity' => "quantity",
            'notes' => 'notes',
            'model' => 'model',
            'city' => 'city',
            'neighborhood' => 'neighborhood',
            'time' => 'time',
            'date' => 'date',
        ];
        $data = ['inputs_name' => $inputs_name];
        return response()->apiSuccess($data, 'success', 200);
    }

    public function storeService(Request $request)
    {

        $is_created = AirConditionService::create([
            'user_id' => $request->user_id ?? auth('sanctum')->id(),
            'split' => $request->split,
            'window' => $request->window,
            'feryoun' => $request->feryoun,
            'maintance' => $request->maintance,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'model' => $request->model,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'time' => $request->time,
            'date' => $request->date,

        ]);
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('air_con', [
                        'disk' => 'public',
                    ]);
                        $image = new GeneralImage([
                        'path' => $path,
                    ]);
                    $is_created->images()->save($image);
                }
            }
        }
        $data = ['service' => $is_created ];
        return response()->apiSuccess($data, 'success', 200);

    }
    public function accept_offer(Request $request){

        $is_create = AirConditionOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            $service = AirConditionService::find($request->service_id);
            $service->update(['status' => "pending"]);
        }

        $data = ['order_details' => $is_create , 'service_details' => $service ];
        return response()->apiSuccess($data);

    }

    public function storeRate(Request $request){
        $request->validate([
            'order_id' => 'required',
            'professionalism_in_dealing' => "required",
            'communication_and_follow_up' => "required",
            'quality_of_work_delivered' => 'required',
            'experience_in_the_project_field' => 'required',
            'delivery_on_time' => 'required',
            'deal_with_him_again' => 'required',
        ]);

        $department_name = 'air_con';
        $order = AirConditionOrder::where('id' , $request->order_id)->first();
        $order->update([
            'status'    => 'completed',
        ]);
        // dd($order );
        $data = $request->all();
        $data['creator_id'] = $order->customer_id;
        $data['user_id'] = $order->service_provider_id;
        $data = $request->all();
        $rates = 0;
        $rates += (int)$data['professionalism_in_dealing'];
        $rates += (int)$data['communication_and_follow_up'];
        $rates += (int)$data['quality_of_work_delivered'];
        $rates += (int)$data['experience_in_the_project_field'];
        $rates += (int)$data['delivery_on_time'];
        $rates += (int)$data['deal_with_him_again'];

        $rate = Rating::create([
            'order_id' => $request->order_id,
            'department_name' => $department_name ?? 'general',
            'creator_id' => auth('sanctum')->user()->id ??  $order->customer_id,
            'user_id' => $order->service_provider_id,
            'professionalism_in_dealing' => (int)$data['professionalism_in_dealing'],
            'communication_and_follow_up' => (int)$data['communication_and_follow_up'],
            'quality_of_work_delivered' => (int)$data['quality_of_work_delivered'],
            'experience_in_the_project_field' => (int)$data['experience_in_the_project_field'],
            'deal_with_him_again' => (int)$data['deal_with_him_again'],
            'delivery_on_time' => (int)$data['delivery_on_time'],
            'rate' => $rates > 0 ? number_format($rates, 2) / 6 : 0,
            'comment' => $data['comment'] ?? null,
            'created_at' => time(),
        ]);
        return response()->apiSuccess($rate);
    }
    // service_provider

    public function service_provider_index(){
        $services = AirConditionService::get();
        return response()->apiSuccess($services);
    }


    public function service_provider_add_offer(Request $request){
        $service = AirConditionService::where('id' , $request->service_id)->first();
        $customer = User::where('id' ,$service->user_id )->first();
        $user = auth('sanctum')->user();
        $data = $request->except('image');
         $comment = new GeneralComments([
            'service_provider'                      => $user->id ?? 2,
            'body'                                  => $request->body  ?? null,
            'price'                                 => $request->price  ?? null,
            'date'                                  => $request->date  ?? null,
            'time'                                  => $request->time  ?? null,
            'notes'                                 => $request->notes  ?? null,
            'city'                                  => $request->city  ?? null,
            'location'                              => $request->location  ?? null,
            'day'                                   => $request->day  ?? null,
            'number_of_days_of_warranty'            => $request->number_of_days_of_warranty  ?? null,
        ]);
        $service->comments()->save($comment);


        if($comment){
            $customer->notify(new CommentNotification([
                'id' => $comment->id,
                'title' => "قدم $user->first_name  لك عرضا",
                'body' => "$comment->notes",
                'url' => route('web.posts.show' , $request->service_id)
            ]));
        }
        return response()->apiSuccess($comment);
    }
    public function showService($id)
    {

        $is_created = AirConditionService::find($id);
        $offers = GeneralComments::where('commentable_id',$id)->where('commentable_type' ,'App\Models\CarWaterService')->get();
        $data = ['service' => $is_created , 'offers' => $offers];
        return response()->apiSuccess($data, 'success', 200);

    }
}
