<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
{
    $lang = app()->getLocale(); // أو config('app.locale')

    // تحميل الدول والمحافظات المرتبطة بها
    $countries = Country::with('governorates')->get();

    // تجهيز المحافظات لكل دولة بصيغة مناسبة لل JavaScript
    $governoratesByCountry = $countries->mapWithKeys(function($country) {
        return [
            $country->id => $country->governorates->map(function($gov) {
                return [
                    'id' => $gov->id,
                    'name_ar' => $gov->name_ar,
                    'name_en' => $gov->name_en,
                ];
            })->values()
        ];
    });

    return view('frontend.locations', compact('countries', 'governoratesByCountry', 'lang'));
}
    public function create(){
        return view('admin.country.add_country');
    }
    public function store(Request $request){
        $felids = $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string'
        ]);
        Country::create($felids);
        return redirect()->back()->with('success', 'تم اضافة المحافظة');
    }
}
