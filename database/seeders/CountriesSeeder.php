<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name_ar' => 'السعودية',
                'name_en' => 'Saudi Arabia',
                'code' => '+966'
            ],
            [
                'name_ar' => 'مصر',
                'name_en' => 'Egypt',
                'code' => '+20'
            ],
            [
                'name_ar' => 'الإمارات',
                'name_en' => 'UAE',
                'code' => '+971'
            ],
            [
                'name_ar' => 'الكويت',
                'name_en' => 'Kuwait',
                'code' => '+965'
            ],
            [
                'name_ar' => 'البحرين',
                'name_en' => 'Bahrain',
                'code' => '+973'
            ],
            [
                'name_ar' => 'عمان',
                'name_en' => 'Oman',
                'code' => '+968'
            ],
            [
                'name_ar' => 'قطر',
                'name_en' => 'Qatar',
                'code' => '+974'
            ],
            [
                'name_ar' => 'الأردن',
                'name_en' => 'Jordan',
                'code' => '+962'
            ],
            [
                'name_ar' => 'لبنان',
                'name_en' => 'Lebanon',
                'code' => '+961'
            ],
            [
                'name_ar' => 'المغرب',
                'name_en' => 'Morocco',
                'code' => '+212'
            ],
            [
                'name_ar' => 'الجزائر',
                'name_en' => 'Algeria',
                'code' => '+213'
            ],
            [
                'name_ar' => 'تونس',
                'name_en' => 'Tunisia',
                'code' => '+216'
            ],
            [
                'name_ar' => 'ليبيا',
                'name_en' => 'Libya',
                'code' => '+218'
            ],
            [
                'name_ar' => 'السودان',
                'name_en' => 'Sudan',
                'code' => '+249'
            ],
            [
                'name_ar' => 'العراق',
                'name_en' => 'Iraq',
                'code' => '+964'
            ],
            [
                'name_ar' => 'سوريا',
                'name_en' => 'Syria',
                'code' => '+963'
            ],
            [
                'name_ar' => 'فلسطين',
                'name_en' => 'Palestine',
                'code' => '+970'
            ],
            [
                'name_ar' => 'اليمن',
                'name_en' => 'Yemen',
                'code' => '+967'
            ]
        ];

        foreach ($countries as $country) {
            DB::table('countries')->updateOrInsert(
                ['name_ar' => $country['name_ar']],
                $country
            );
        }
    }
}
