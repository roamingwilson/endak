<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use App\Models\UserDepartment;
use App\Models\Department;
use App\Models\SubDepartment;

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
        'status',
        'image',
        'country',
        'governement',
        'status',
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
        return $this->hasMany(GeneralOrder::class, 'service_provider_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(Services::class, 'provider_id', 'id');
    }
    public function myorders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }


    public function rates()
    {
        return round($this->rate()->avg('rate')) ?? 0;
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
    public function userDepartments()
    {
        return $this->hasMany(UserDepartment::class, 'user_id');
    }
    public function comments()
{
    return $this->hasMany(GeneralComments::class, 'service_provider');
}
public function serv(){
        return $this->hasMany(Services::class , 'user_id');
}
public function prOrder(){
        return $this->hasMany(ProductOrder::class , 'user_id');
}
public function favoriteDepartments()
{
    return $this->belongsToMany(Department::class, 'favorites')->withTimestamps();
}

/**
 * Check if the user is currently online
 *
 * @return bool
 */
public function isOnline()
{
    // If there's no last_seen_at column, we'll use a simple approach
    // You can modify this based on your needs

    // Option 1: Simple approach - consider user online if they logged in within last 5 minutes
    if (isset($this->last_seen_at)) {
        return $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    // Option 2: If you have a sessions table or cache-based approach
    // You can implement a more sophisticated online detection here

    // Option 3: Default to true for now (you can modify this logic)
    return true;
}

/**
 * Get the user's online status with a colored indicator
 *
 * @return string
 */
public function getOnlineStatusAttribute()
{
    return $this->isOnline() ? 'online' : 'offline';
}

/**
 * Get the user's online status color
 *
 * @return string
 */
public function getOnlineStatusColorAttribute()
{
    return $this->isOnline() ? 'text-success' : 'text-muted';
}

    public function governementObj()
    {
        return $this->belongsTo(\App\Models\Governements::class, 'governement');
    }
    public function countryObj()
    {
        return $this->belongsTo(\App\Models\Country::class, 'country');
    }

    /**
     * العلاقة مع المدن التي يمكن للمزود العمل فيها
     */
    public function providerCities()
    {
        return $this->hasMany(ProviderCity::class);
    }

    /**
     * الحصول على المدن التي يمكن للمزود العمل فيها
     */
    public function getServiceCities()
    {
        return $this->providerCities()->with('governement')->get()->pluck('governement');
    }

    /**
     * التحقق من إمكانية العمل في مدينة معينة
     */
    public function canWorkInCity($cityId)
    {
        return $this->providerCities()->where('governement_id', $cityId)->exists();
    }

    /**
     * الحصول على جميع المدن (المختارة + المدينة الأصلية)
     */
    public function getAllWorkCities()
    {
        $selectedCities = $this->getServiceCities();
        $originalCity = $this->governementObj;

        $allCities = $selectedCities;

        // إضافة المدينة الأصلية إذا لم تكن موجودة في المدن المختارة
        if ($originalCity && !$selectedCities->contains('id', $originalCity->id)) {
            $allCities->push($originalCity);
        }

        return $allCities;
    }

    /**
     * الحصول على أسماء جميع المدن (المختارة + المدينة الأصلية)
     */
    public function getAllWorkCityNames()
    {
        $allCities = $this->getAllWorkCities();
        $cityNames = $allCities->pluck('name_ar')->toArray();
        $cityNamesEn = $allCities->pluck('name_en')->toArray();

        return array_merge($cityNames, $cityNamesEn);
    }

    /**
     * Get all departments (main and sub) the user is subscribed to as models.
     */
    public function getAllDepartments()
    {
        $mainDeps = $this->userDepartments->where('commentable_type', \App\Models\Department::class)->pluck('commentable_id')->unique();
        $subDeps = $this->userDepartments->where('commentable_type', \App\Models\SubDepartment::class)->pluck('commentable_id')->unique();
        $mainDepartments = \App\Models\Department::whereIn('id', $mainDeps)->get();
        $subDepartments = \App\Models\SubDepartment::whereIn('id', $subDeps)->get();
        return [
            'main' => $mainDepartments,
            'sub' => $subDepartments
        ];
    }
}
