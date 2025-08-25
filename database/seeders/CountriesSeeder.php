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
                'name_fr' => 'Arabie Saoudite',
                'code' => '+966'
            ],
            [
                'name_ar' => 'مصر',
                'name_en' => 'Egypt',
                'name_fr' => 'Egypte',
                'code' => '+20'
            ],
            [
                'name_ar' => 'الإمارات',
                'name_en' => 'UAE',
                'name_fr' => 'Emirats Arabes Unis',
                'code' => '+971'
            ],
            [
                'name_ar' => 'الكويت',
                'name_en' => 'Kuwait',
                'name_fr' => 'Koweit',
                'code' => '+965'
            ],
            [
                'name_ar' => 'البحرين',
                'name_en' => 'Bahrain',
                'name_fr' => 'Bahreïn',
                'code' => '+973'
            ],
            [
                'name_ar' => 'عمان',
                'name_en' => 'Oman',
                'name_fr' => 'Oman',
                'code' => '+968'
            ],
            [
                'name_ar' => 'قطر',
                'name_en' => 'Qatar',
                'name_fr' => 'Qatar',
                'code' => '+974'
            ],
            [
                'name_ar' => 'الأردن',
                'name_en' => 'Jordan',
                'name_fr' => 'Jordanie',
                'code' => '+962'
            ],
            [
                'name_ar' => 'لبنان',
                'name_en' => 'Lebanon',
                'name_fr' => 'Liban',
                'code' => '+961'
            ],
            [
                'name_ar' => 'المغرب',
                'name_en' => 'Morocco',
                'name_fr' => 'Maroc',
                'code' => '+212'
            ],
            [
                'name_ar' => 'الجزائر',
                'name_en' => 'Algeria',
                'name_fr' => 'Algérie',
                'code' => '+213'
            ],
            [
                'name_ar' => 'تونس',
                'name_en' => 'Tunisia',
                'name_fr' => 'Tunisie',
                'code' => '+216'
            ],
            [
                'name_ar' => 'ليبيا',
                'name_en' => 'Libya',
                'name_fr' => 'Libye',
                'code' => '+218'
            ],
            [
                'name_ar' => 'السودان',
                'name_en' => 'Sudan',
                'name_fr' => 'Soudan',
                'code' => '+249'
            ],
            [
                'name_ar' => 'العراق',
                'name_en' => 'Iraq',
                'name_fr' => 'Irak',
                'code' => '+964'
            ],
            [
                'name_ar' => 'سوريا',
                'name_en' => 'Syria',
                'name_fr' => 'Syrie',
                'code' => '+963'
            ],
            [
                'name_ar' => 'فلسطين',
                'name_en' => 'Palestine',
                'name_fr' => 'Palestine',
                'code' => '+970'
            ],
            [
                'name_ar' => 'اليمن',
                'name_en' => 'Yemen',
                'name_fr' => 'Yémen',
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
