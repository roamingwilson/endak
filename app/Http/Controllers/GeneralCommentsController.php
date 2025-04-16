<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdsService;
use App\Models\WaterService;
use Illuminate\Http\Request;
use App\Models\BigCarService;
use App\Models\FamilyService;
use App\Models\GardenService;
use App\Models\WorkerService;
use App\Models\TeacherService;
use App\Models\CarWaterService;
use App\Models\CleaningService;
use App\Models\GeneralComments;
use App\Models\PublicGeService;
use App\Models\ContractingService;
use App\Models\FollowCameraService;
use App\Models\CounterInsectsService;
use App\Models\PartyPreparationService;
use App\Notifications\CommentNotification;
use App\Models\FurnitureTransportationService;

class GeneralCommentsController extends Controller
{
    public function store(Request $request){
        $current_url = url()->previous();
        $url = explode('/', $current_url);
        $value1 = ['furniture_transportations'];
        $value2 = ['surveillance_cameras'];
        $value3 = ['party_preparation'];
        $value4 = ['garden'];
        $value5 = ['counter_insects'];
        $value6 = ['cleaning'];
        $value7 = ['teacher'];
        $value8 = ['family'];
        $value9 = ['worker'];
        $value10 = ['public_ge'];
        $value11 = ['ads'];
        $value12 = ['water'];
        $value13 = ['car_water'];
        $value14 = ['big_car'];
        $value15 = ['contracting'];
        if (array_intersect($url, $value1)) {
            $service = FurnitureTransportationService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value2)){
            $service = FollowCameraService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value3)){
            $service = PartyPreparationService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value4)){
            $service = GardenService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value5)){
            $service = CounterInsectsService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value6)){
            $service = CleaningService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value7)){
            $service = TeacherService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value8)){
            $service = FamilyService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value9)){
            $service = WorkerService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value10)){
            $service = PublicGeService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value11)){
            $service = AdsService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value12)){
            $service = WaterService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value13)){
            $service = CarWaterService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value14)){
            $service = BigCarService::where('id' , $request->service_id)->first();
        }
        elseif(array_intersect($url, $value15)){
            $service = ContractingService::where('id' , $request->service_id)->first();
        }
        $customer = User::where('id' ,$service->user_id )->first();
        $user = auth()->user();
        $data = $request->except('image'); 
        $comment = new GeneralComments([
            'service_provider'                      => $user->id,
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

        // $type = ;
        if($comment){
            $customer->notify(new CommentNotification([
                'id' => $comment->id,
                'title' => "قدم $user->first_name  لك عرضا",
                'body' => "$comment->notes",
                'url' => '/'
            ]));
        }
        return redirect()->back()->with('success','تم تقديم العرض بنجاح');
    }
}
