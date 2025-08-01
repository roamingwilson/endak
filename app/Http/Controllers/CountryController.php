<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function dashboard(){
        return view('admin.country.dashboard');
    }

    public function index(){
        $countries = Country::with('governements')->get();
        return view('admin.country.index', compact('countries'));
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
        return redirect()->back()->with('success', 'تم اضافة الدولة بنجاح');
    }

    public function edit($id){
        $country = Country::findOrFail($id);
        return view('admin.country.edit', compact('country'));
    }

    public function update(Request $request, $id){
        $country = Country::findOrFail($id);
        $felids = $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string'
        ]);
        $country->update($felids);
        return redirect()->route('countries.index')->with('success', 'تم تحديث الدولة بنجاح');
    }

    public function destroy($id){
        $country = Country::findOrFail($id);
        $country->delete();
        return redirect()->back()->with('success', 'تم حذف الدولة بنجاح');
    }
}
