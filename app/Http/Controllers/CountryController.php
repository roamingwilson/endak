<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
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
