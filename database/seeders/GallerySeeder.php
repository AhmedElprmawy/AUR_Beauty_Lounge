<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $galleries = [
            [
                'title_ar' => 'عروس فاخرة',
                'title_en' => 'Luxury Bridal',
                'category' => 'bridal',
                'image' => 'images/White_Egyptian_bride_wedding_dress_202607211948.jpeg',
                'caption' => 'إطلالة العروس الكاملة',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title_ar' => 'مكياج طبيعي',
                'title_en' => 'Natural Makeup',
                'category' => 'makeup',
                'image' => 'images/Woman_wearing_hijab_makeup_202607021118.jpeg',
                'caption' => 'لوك مكياج أنثوي ناعم',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title_ar' => 'تسريحة شعر أنيقة',
                'title_en' => 'Elegant Hairstyle',
                'category' => 'hair',
                'image' => 'images/Woman_with_styled_hair_202607202147.jpeg',
                'caption' => 'تسريحة شعر مثالية للمناسبات',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title_ar' => 'عناية بالبشرة',
                'title_en' => 'Skin Care',
                'category' => 'skin',
                'image' => 'images/Person_receiving_skincare_treatment_202607202155.jpeg',
                'caption' => 'جلسة عناية وترطيب للبشرة',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title_ar' => 'تجربة سبا',
                'title_en' => 'Spa Experience',
                'category' => 'fashion',
                'image' => 'images/Woman_having_Moroccan_bath_202607202318.jpeg',
                'caption' => 'لحظات استرخاء فاخرة',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::updateOrCreate(
                ['title_ar' => $gallery['title_ar']],
                $gallery
            );
        }
    }
}
