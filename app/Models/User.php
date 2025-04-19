<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_name',
        'role_id',
        'about_me',
        'phone',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function scopeFilter(Builder $builder , $filters){

        // $builder->when($filters['name'] ?? false , function($builder , $value){
        //     $builder->where('categories.name','like','%'.$value.'%');
        // });

        $builder->when($filters['status'] ?? false , function($builder , $value){
            $builder->where('users.status', $value);
        });
        // if($filters['name'] ?? false){
        //     $builder->where('name','like','%'.$filters['name'].'%');
        // };
        // if($filters['status'] ?? false){
        //     $builder->where('status','=',$filters['status']);
        // };
    }
    public function hasPermission($section_name)
    {
        if (!isset($this->permissions)) {
            $sections_id = Permission::where('role_id', '=', $this->role_id)->where('allow', true)->pluck('section_id')->toArray();
            $this->permissions = Section::whereIn('id', $sections_id)->pluck('name')->toArray();
        }

        return in_array($section_name, $this->permissions);
    }
    public function scopeActive(Builder $builder){
        $builder->where('status' , 'active');
    }

    public function rate(){
        return $this->hasMany(Rating::class , 'user_id' , 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_provider_id', 'id');
            // ->orWhere('teacher_id', $this->id);
    }
    public function myorders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
    public function rates()
    {
        $orders = $this->orders()
            ->where('status', 'complete')
            ->where('admin_status', 'active')
            ->get();

        $otherOrder1 = FurnitureTransportationOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder2 = FollowCameraOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder3 = PartyPreparationOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder4 = GardenOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder5 = CounterInsectsOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder6 = CleaningOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder7 = TeacherOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder8 = FamilyOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder9 = WorkerOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder10 = PublicGeOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder11 = AdsOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder12 = WaterOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder13 = CarWaterOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder14 = BigCarOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder15 = ContractingOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder16= HeavyEquipmentOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder17= AirConditionOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder18= SparePartOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();
        $otherOrder19= VanTruckOrder::where('service_provider_id' , $this->id)->where('status', 'completed')->get();

        $mergedOrders = $orders->merge($otherOrder1)
        ->merge($otherOrder2)
        ->merge($otherOrder3)
        ->merge($otherOrder4)
        ->merge($otherOrder5)
        ->merge($otherOrder6)
        ->merge($otherOrder7)
        ->merge($otherOrder8)
        ->merge($otherOrder9)
        ->merge($otherOrder10)
        ->merge($otherOrder11)
        ->merge($otherOrder12)
        ->merge($otherOrder13)
        ->merge($otherOrder14)
        ->merge($otherOrder15)
        ->merge($otherOrder16)
        ->merge($otherOrder17)
        ->merge($otherOrder18)
        ->merge($otherOrder19)
        ;
        $rate = 0;


        if (!empty($mergedOrders)) {
            $rates = 0;
            $count = 0;

            foreach ($mergedOrders as $order) {
                $orderRate = Rating::where('order_id' , $order->id)->first();
                if(isset($orderRate->rate)){
                    if (!empty($orderRate->rate) and $orderRate->rate > 0) {
                        $count += 1;
                        $rates += $orderRate->rate;
                    }
                }

            }

            if ($rates > 0) {
                if ($count < 1) {
                    $count = 1;
                }

                $rate = number_format($rates / $count, 2);
            }
        }

        return $rate;
    }


    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
    public function departments()
    {
        return $this->hasMany(UserDepartment::class, 'user_id');
    }

}
