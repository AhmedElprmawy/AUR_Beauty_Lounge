<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staff = [
            [
                'name_ar' => 'سارة أحمد',
                'name_en' => 'Sara Ahmed',
                'role_ar' => 'مديرة الميكاب',
                'role_en' => 'Makeup Director',
                'bio_ar' => 'خبيرة مكياج دولية متخصصة في إطلالات العرائس والسواريه.',
                'bio_en' => 'International makeup artist specializing in bridal and evening looks.',
                'image' => 'images/staff/sara-ahmed.jpg',
                'instagram' => 'https://instagram.com/sara.aur',
                'twitter' => 'https://twitter.com/sara_aur',
                'experience_years' => 12,
                'level' => 'Expert',
                'is_active' => true,
            ],
            [
                'name_ar' => 'نورا محمود',
                'name_en' => 'Noura Mahmoud',
                'role_ar' => 'خبيرة الشعر',
                'role_en' => 'Hair Specialist',
                'bio_ar' => 'تنسق أجمل التسريحات الفاخرة لتناسب كل مناسبة وعروس.',
                'bio_en' => 'Creates luxurious hairstyles tailored to every occasion and bridal look.',
                'image' => 'images/staff/noura-mahmoud.jpg',
                'instagram' => 'https://instagram.com/noura.aur',
                'twitter' => 'https://twitter.com/noura_aur',
                'experience_years' => 10,
                'level' => 'Expert',
                'is_active' => true,
            ],
            [
                'name_ar' => 'ريم عبدالله',
                'name_en' => 'Reem Abdullah',
                'role_ar' => 'أخصائية البشرة',
                'role_en' => 'Skin Specialist',
                'bio_ar' => 'متخصصة في العناية بالبشرة والعلاجات المتقدمة لتجديد الإشراقة.',
                'bio_en' => 'Skincare specialist delivering advanced rejuvenation treatments.',
                'image' => 'images/staff/reem-abdullah.jpg',
                'instagram' => 'https://instagram.com/reem.aur',
                'twitter' => 'https://twitter.com/reem_aur',
                'experience_years' => 9,
                'level' => 'Expert',
                'is_active' => true,
            ],
            [
                'name_ar' => 'هدى حسن',
                'name_en' => 'Huda Hassan',
                'role_ar' => 'خبيرة الأظافر',
                'role_en' => 'Nail Artist',
                'bio_ar' => 'تصمم أظافر فاخرة مع لمسات فنية عصرية تناسب كل المناسبات.',
                'bio_en' => 'Designs luxury nail art with modern creative touches for all events.',
                'image' => 'images/staff/huda-hassan.jpg',
                'instagram' => 'https://instagram.com/huda.aur',
                'twitter' => 'https://twitter.com/huda_aur',
                'experience_years' => 8,
                'level' => 'Expert',
                'is_active' => true,
            ],
        ];

        foreach ($staff as $member) {
            Staff::updateOrCreate(['name_ar' => $member['name_ar']], $member);
        }
    }
}
