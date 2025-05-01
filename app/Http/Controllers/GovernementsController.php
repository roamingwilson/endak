<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Governements;
use Illuminate\Http\Request;

class GovernementsController extends Controller
{
    public function create(){
        $countries = Country::all();
        return view('admin.country.add_govers',compact('countries'));
    }
    public function store(Request $request){
        $felids = $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'country_id' => 'required',
        ]);
        Governements::create($felids);
        return redirect()->back()->with('success', 'تم اضافة المحافظة');
    }
}
