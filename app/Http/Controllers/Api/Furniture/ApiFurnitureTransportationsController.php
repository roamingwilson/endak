<?php

namespace App\Http\Controllers\Api\Furniture;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Models\GeneralComments;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use App\Models\FurnitureTransportationOrder;
use App\Models\FurnitureTransportationProduct;
use App\Models\FurnitureTransportationService;
use App\Models\FurnitureTransportationServiceProducts;

class ApiFurnitureTransportationsController extends Controller
{
    public function index()
    {
        $products = FurnitureTransportationProduct::get();
        $inputs_name = [
            'select_product' => "product-product_id",
            'product_quantity' => "quantities-[product_id]",
            'select_disassembly' => "disassembly[product_id]",
            'select_installation' => "installation[product_id]",
            'from_city' => "from_city",
            'from_neighborhood' => "from_neighborhood",
            'from_home' => "from_home",
            'to_city' => "to_city",
            'to_neighborhood' => "to_neighborhood",
            'to_home' => "to_home",
            'notes' => 'notes',
        ];
        $data = ['products' => $products, 'inputs_name' => $inputs_name];
        return response()->apiSuccess($data, 'success', 200);
    }

    public function storeService(Request $request)
    {
        $quantities = json_decode($request->quantities, true); // تحويل القيم إلى مصفوفات
        $disassembly = json_decode($request->disassembly, true);
        $installation = json_decode($request->installation, true);

        $request->validate([
            'selected_products' => 'required',
            'quantities' => 'required',
            'from_city' => 'required',
            'from_neighborhood' => 'required',
            'from_home' => 'required',
            'to_city' => 'required',
            'to_neighborhood' => 'required',
            'to_home' => 'required',
        ]);
        eval('$selected_products = ' . $request->selected_products . ';');
        eval('$quantities = ' . $request->quantities . ';');
        eval('$installation = ' . $request->installation . ';');
        eval('$disassembly = ' . $request->disassembly . ';');
        $is_created = FurnitureTransportationService::create([
            'from_city' => $request->from_city,
            'from_neighborhood' => $request->from_neighborhood,
            'from_home' => $request->from_home,
            'to_city' => $request->to_city,
            'to_neighborhood' => $request->to_neighborhood,
            'to_home' => $request->to_home,
            'notes' => $request->notes,
            'user_id' => auth('sanctum')->id() ?? 2,
        ]);


        if ($is_created) {
            $products = []; 
            foreach ($selected_products as $key => $value) {
                if (isset($quantities[$value]) && !is_null($quantities[$value])) {
                    
                    $products[] = FurnitureTransportationServiceProducts::create([
                        'service_id' => $is_created->id,
                        'product_id' => $value,
                        'quantity' => $quantities[$value] ?? 0,
                        'installation' => $installation[$value] ?? 0,
                        'disassembly' => $disassembly[$value] ?? 0,
                    ]);
                }
            }
        }

        $data = ['service' => $is_created, 'products' => $products];
        return response()->apiSuccess($data, 'success', 200);

    }



    // service_provider

    public function service_provider_index(){
        $services = FurnitureTransportationService::get();
        return response()->apiSuccess($services);
    }

    
    public function service_provider_add_offer(Request $request){
        $service = FurnitureTransportationService::where('id' , $request->service_id)->first();
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

    public function accept_offer(Request $request){
     
        $is_create = FurnitureTransportationOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            $service = FurnitureTransportationService::find($request->service_id);
            $service->update(['status' => "pending"]);
        }
        $main_products =[];
        $products = [];
        foreach($service->products as $product){
            $products[] = $product;
            $main_products[] = FurnitureTransportationProduct::find($product->product_id);
        }

        $data = ['order_details' => $is_create , 'service_details' => $service , 'products' => $products , 'products->details' => $main_products];
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

        $department_name = 'furniture_transportations';
        $order = FurnitureTransportationOrder::where('id' , $request->order_id)->first();
        $order->update([
            'status'    => 'completed',
        ]);
        $data = $request->all();
        $data['creator_id'] = auth()->user()->id;
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
            'creator_id' => auth('sanctum')->user()->id,
            'user_id' => $order->service_provider_id,
            'professionalism_in_dealing' => (int)$data['professionalism_in_dealing'],
            'communication_and_follow_up' => (int)$data['communication_and_follow_up'],
            'quality_of_work_delivered' => (int)$data['quality_of_work_delivered'],
            'experience_in_the_project_field' => (int)$data['experience_in_the_project_field'],
            'deal_with_him_again' => (int)$data['deal_with_him_again'],
            'delivery_on_time' => (int)$data['delivery_on_time'],
            'rate' => $rates > 0 ? number_format($rates, 2) / 6 : 0,
            'comment' => $data['comment'],
            'created_at' => time(),
        ]);
        return response()->apiSuccess($rate);
    }
    public function showService($id)
    {

        $service = FurnitureTransportationService::find($id);
    
        $main_products =[];
        $products = [];
        foreach($service->products as $product){
            $products[] = $product;
            $main_products[] = FurnitureTransportationProduct::find($product->product_id);
        }
        $offers = GeneralComments::where('commentable_id',$id)->where('commentable_type' ,'App\Models\GardenService')->get();
        $data = ['service' => $service , 'products' => $products , 'products->details' => $main_products , 'offers' => $offers];

        return response()->apiSuccess($data, 'success', 200);

    }
}
