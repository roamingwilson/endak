<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class governement extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities =  [

            array('id' => '3082', 'name_en' => 'Makkah', 'name_ar' => 'مكه', 'name_fr' => 'La Mecque', 'code' => '14', 'country_id' => '232'),
            array('id' => '3083', 'name_en' => 'Ar Riyad', 'name_ar' => 'الرياض', 'name_fr' => 'Ar Riyad', 'code' => '10', 'country_id' => '232'),
            array('id' => '3084', 'name_en' => 'Ha\'il', 'name_ar' => 'حائل', 'name_fr' => 'Saluer', 'code' => '13', 'country_id' => '232'),
            array('id' => '3085', 'name_en' => 'Al Hudud ash Shamaliyah', 'name_ar' => 'الحدود الشمالية', 'name_fr' => 'Al Hudud ash Shamaliyah', 'code' => '15', 'country_id' => '232'),
            array('id' => '3086', 'name_en' => 'Jizan', 'name_ar' => 'جازان', 'name_fr' => 'Jizan', 'code' => '17', 'country_id' => '232'),
            array('id' => '3087', 'name_en' => 'Ash Sharqiyah', 'name_ar' => 'الشرقية', 'name_fr' => 'Ash Sharqiyah', 'code' => '06', 'country_id' => '232'),
            array('id' => '3088', 'name_en' => 'Al Madinah', 'name_ar' => 'المدينة', 'name_fr' => 'Al Madinah', 'code' => '05', 'country_id' => '232'),
            array('id' => '3089', 'name_en' => 'Al Qasim', 'name_ar' => 'القصيم', 'name_fr' => 'Al Qasim', 'code' => '08', 'country_id' => '232'),
            array('id' => '3090', 'name_en' => 'Al Bahah', 'name_ar' => 'الباحة', 'name_fr' => 'Al Bahah', 'code' => '02', 'country_id' => '232'),
            array('id' => '3091', 'name_en' => 'Tabuk', 'name_ar' => 'تبوك', 'name_fr' => 'Tabuk', 'code' => '19', 'country_id' => '232'),
            array('id' => '3092', 'name_en' => 'Al Jawf', 'name_ar' => 'الجوف', 'name_fr' => 'Al Jawf', 'code' => '20', 'country_id' => '232'),
            array('id' => '3093', 'name_en' => 'Najran', 'name_ar' => 'نجران', 'name_fr' => 'Najran', 'code' => '12', 'country_id' => '232'),
            array('id' => '3094', 'name_en' => 'Asir', 'name_ar' => 'عسير', 'name_fr' => 'Asir', 'code' => '11', 'country_id' => '232'),

        ];


        DB::table('governements')->insert($cities);
    }
}
