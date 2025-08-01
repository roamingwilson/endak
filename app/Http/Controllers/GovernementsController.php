<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Governements;
use Illuminate\Http\Request;

class GovernementsController extends Controller
{
    public function index(){
        $governorates = Governements::with('country')->get();
        return view('admin.country.governorates_index', compact('governorates'));
    }

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
        return redirect()->back()->with('success', 'تم اضافة المحافظة بنجاح');
    }

    public function edit($id){
        $governorate = Governements::findOrFail($id);
        $countries = Country::all();
        return view('admin.country.edit_governorate', compact('governorate', 'countries'));
    }

    public function update(Request $request, $id){
        $governorate = Governements::findOrFail($id);
        $felids = $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'country_id' => 'required',
        ]);
        $governorate->update($felids);
        return redirect()->route('governorates.index')->with('success', 'تم تحديث المحافظة بنجاح');
    }

    public function destroy($id){
        $governorate = Governements::findOrFail($id);
        $governorate->delete();
        return redirect()->back()->with('success', 'تم حذف المحافظة بنجاح');
    }

    public function getGovernorates(Request $request)
    {
        $countryId = $request->country_id;
        $governorates = Governements::where('country_id', $countryId)->get();
        return response()->json($governorates);
    }

    public function getByCountry(Request $request)
    {
        $countryId = $request->country_id;
        $governorates = Governements::where('country_id', $countryId)->get();
        return response()->json($governorates);
    }
}
