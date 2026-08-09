<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transformation;

class TransformationSeeder extends Seeder
{
    public function run()
    {
        $transformations = [
            [
                'category' => 'hair',
                'title_ar' => 'تسريحة فاخرة',
                'title_en' => 'Luxury Hairstyle',
                'description_ar' => 'تغيير كامل للشعر مع قص وتصفيف يصلح لجميع المناسبات الخاصة.',
                'description_en' => 'A full hair transformation with styling for your special events.',
                'before_image' => 'images/Woman_with_styled_hair_202607202147.jpeg',
                'after_image' => 'images/Woman_wearing_hijab_makeup_202607021118.jpeg',
                'is_featured' => true,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'makeup',
                'title_ar' => 'ميكاب عروس',
                'title_en' => 'Bridal Makeup',
                'description_ar' => 'إطلالة عروس كاملة تناسب أجواء الزفاف والمناسبات الرسمية.',
                'description_en' => 'A complete bridal look designed for weddings and grand events.',
                'before_image' => 'images/White_Egyptian_bride_getting_ready_202607202318.jpeg',
                'after_image' => 'images/White_Egyptian_bride_wedding_dress_202607211944.jpeg',
                'is_featured' => false,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'skin',
                'title_ar' => 'عناية بالبشرة',
                'title_en' => 'Skin Care Treatment',
                'description_ar' => 'جلسة علاجية لبشرة نضرة وصحية مع نتائج ملحوظة بعد الجلسة.',
                'description_en' => 'A skincare session for a radiant and healthy complexion.',
                'before_image' => 'images/Person_receiving_skincare_treatment_202607202155.jpeg',
                'after_image' => 'images/Woman_having_Moroccan_bath_202607202318.jpeg',
                'is_featured' => false,
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($transformations as $transformation) {
            Transformation::updateOrCreate(
                ['title_ar' => $transformation['title_ar']],
                $transformation
            );
        }
    }
}
