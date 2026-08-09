<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BridalPackage;

class BridalPackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'tier' => 'silver',
                'name_ar' => 'عروس فضية',
                'name_en' => 'Silver Bride',
                'price' => 5000,
                'currency' => 'ج.م',
                'description_ar' => 'باقة عروس فضية تشمل مكياج العروس، تسريحة يوم الزفاف، مانيكير وبديكير، وجلسة تجربة.',
                'description_en' => 'Includes Bridal Makeup, Wedding Hairstyle, Manicure & Pedicure and a Trial Session.',
                'features_ar' => ['ميكاج العروس', 'تسريحة يوم الزفاف', 'مانيكير وبديكير', 'جلسة تجريبية'],
                'features_en' => ['Bridal Makeup', 'Wedding Hairstyle', 'Manicure & Pedicure', 'Trial Session'],
                'is_popular' => false,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'tier' => 'gold',
                'name_ar' => 'عروس ذهبية',
                'name_en' => 'Gold Bride',
                'price' => 8500,
                'currency' => 'ج.م',
                'description_ar' => 'تشمل كل خدمات العروس الفضية بالإضافة إلى علاج شعر فاخر، جلسة تنظيف وجه ليوم الزفاف، مساج استرخائي، وهدايا مفاجئة.',
                'description_en' => 'Includes all Silver services plus Luxury Hair Treatment, Wedding Day Facial, Relax Massage and a Surprise Gift.',
                'features_ar' => ['كل ما في Silver', 'علاج شعر فاخر', 'جلسة تنظيف وجه ليوم الزفاف', 'مساج استرخائي', 'هدية مفاجئة'],
                'features_en' => ['All Silver features', 'Luxury Hair Treatment', 'Wedding Day Facial', 'Relax Massage', 'Surprise Gift'],
                'is_popular' => true,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'tier' => 'platinum',
                'name_ar' => 'عروس بلاتينية',
                'name_en' => 'Platinum Bride',
                'price' => 15000,
                'currency' => 'ج.م',
                'description_ar' => 'تشمل كل خدمات Gold بالإضافة إلى باقة سبا ليوم كامل، أخصائية موضة شخصية، فريق عرائس حصري، تصوير احترافي، وخدمة VIP.',
                'description_en' => 'Includes all Gold features plus Full Day Spa, Personal Fashion Stylist, Exclusive Bridal Team, Professional Photography and VIP Service.',
                'features_ar' => ['كل ما في Gold', 'سبا ليوم كامل', 'أخصائية موضة شخصية', 'فريق عرائس حصري', 'تصوير احترافي', 'خدمة VIP'],
                'features_en' => ['All Gold features', 'Full Day Spa', 'Personal Fashion Stylist', 'Exclusive Bridal Team', 'Professional Photography', 'VIP Service'],
                'is_popular' => false,
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($packages as $package) {
            BridalPackage::updateOrCreate(
                ['name_ar' => $package['name_ar']],
                $package
            );
        }
    }
}
