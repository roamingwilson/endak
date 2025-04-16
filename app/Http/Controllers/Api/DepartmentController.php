<?php

namespace App\Http\Controllers\Api;

use App\Models\BigCar;
use App\Models\Garden;
use App\Models\Teacher;
use App\Models\CarWater;
use App\Models\Cleaning;
use App\Models\FollowCamera;
use App\Models\CounterInsects;
use App\Models\PartyPreparation;
use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Family;
use App\Services\DepartmentServices;
use App\Models\FurnitureTransportation;
use App\Models\PublicGe;
use App\Models\Water;
use App\Models\Worker;

class DepartmentController extends Controller
{
    public $department_service;

    public function __construct(DepartmentServices $department_service)
    {
        $this->department_service = $department_service;
    }

    public function index()
    {
        $data = $this->department_service->getAllWith('department_id', 0);
        
        $other1 = FurnitureTransportation::first();
        $other2 = FollowCamera::first();
        $other3 = PartyPreparation::first();
        $other4 = Garden::first();
        $other5 = CounterInsects::first();
        $other6 = Cleaning::first();
        $other7 = Teacher::first();
        $other8 = CarWater::first();
        $other9 = BigCar::first();
        $other10 = Family::first();
        $other11 = Water::first();
        $other12 = Worker::first();
        $other13 = PublicGe::first();
        $other14 = Ads::first();
        
        $collection = collect();
        
        if ($other1) $collection = $collection->merge(collect([$other1]));
        if ($other2) $collection = $collection->merge(collect([$other2]));
        if ($other3) $collection = $collection->merge(collect([$other3]));
        if ($other4) $collection = $collection->merge(collect([$other4]));
        if ($other5) $collection = $collection->merge(collect([$other5]));
        if ($other6) $collection = $collection->merge(collect([$other6]));
        if ($other7) $collection = $collection->merge(collect([$other7]));
        if ($other8) $collection = $collection->merge(collect([$other8]));
        if ($other9) $collection = $collection->merge(collect([$other9]));
        if ($other10) $collection = $collection->merge(collect([$other10]));
        if ($other11) $collection = $collection->merge(collect([$other11]));
        if ($other12) $collection = $collection->merge(collect([$other12]));
        if ($other13) $collection = $collection->merge(collect([$other13]));
        if ($other14) $collection = $collection->merge(collect([$other14]));
        $sortedCollection = $collection->sortBy('id');

        $data['main_departments'] = $sortedCollection->values();        
        if($data){

            return response()->apiSuccess($data);
        }else{
            return response()->apiFail("There is no departments");
        }
    }
    public function childern($id)
    {
        return response()->apiSuccess($this->department_service->getAllWith('department_id', $id));
    }
    public function showDepartment($id)
    {
        $inputs = $this->department_service->show($id)->inputs;
        $data['inputs'] = $inputs;
        $data['products'] = $this->department_service->show($id)->products;
        if ($data['inputs']->count() > 0) {

            return response()->apiSuccess($data);
        } else {
            return response()->apiFail("There is No inputs the department is parent", 400);
        }
    }
}
