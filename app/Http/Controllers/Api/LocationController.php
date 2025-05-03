<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCountriesWithGovernements()
    {
        $lang = app()->getLocale(); // يحدد اللغة الحالية

        $countries = Country::with('governements')->get()->map(function ($country) use ($lang) {
            return [
                'id' => $country->id,
                'name' => $lang == 'ar' ? $country->name_ar : $country->name_en,
                'governements' => $country->governements->map(function ($gov) use ($lang) {
                    return [
                        'id' => $gov->id,
                        'name' => $lang == 'ar' ? $gov->name_ar : $gov->name_en,
                    ];
                }),
            ];
        });

        return response()->json($countries);
    }
}
