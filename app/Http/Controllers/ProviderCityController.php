<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Governements;
use App\Models\ProviderCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderCityController extends Controller
{
    /**
     * عرض صفحة إدارة المدن
     */
    public function index()
    {
        $user = Auth::user();

        // التحقق من أن المستخدم مزود خدمة
        if ($user->role_id != 3) {
            return redirect()->back()->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
        }

        $cities = Governements::where('country_id', $user->country)->get();
        $selectedCities = $user->providerCities()->with('governement')->get();

        return view('front_office.provider.cities', compact('cities', 'selectedCities'));
    }

    /**
     * تحديث المدن المختارة
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // التحقق من أن المستخدم مزود خدمة
        if ($user->role_id != 3) {
            return redirect()->back()->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
        }

        $request->validate([
            'cities' => 'array',
            'cities.*' => 'exists:governements,id'
        ]);

        // حذف المدن الحالية
        $user->providerCities()->delete();

        // إضافة المدن الجديدة
        if ($request->has('cities') && is_array($request->cities)) {
            foreach ($request->cities as $cityId) {
                ProviderCity::create([
                    'user_id' => $user->id,
                    'governement_id' => $cityId
                ]);
            }
        }

        return redirect()->back()->with('success', 'تم تحديث المدن بنجاح');
    }

    /**
     * الحصول على المدن المتاحة لمزود الخدمة (API)
     */
    public function getProviderCities($providerId)
    {
        $provider = User::where('role_id', 3)->findOrFail($providerId);
        $cities = $provider->getServiceCities();

        return response()->json([
            'success' => true,
            'cities' => $cities
        ]);
    }
}
