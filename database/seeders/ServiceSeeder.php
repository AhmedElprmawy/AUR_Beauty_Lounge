<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            // Hair
            [
                'title_ar' => 'قص وتسريح',
                'title_en' => 'Hair Cut & Styling',
                'label' => 'Hair',
                'description_ar' => 'قص وتسريح احترافي ليمنحك مظهرًا متجددًا يتناسب مع ذوقك.',
                'description_en' => 'Professional hair cutting and styling for every occasion.',
                'features_ar' => ['قص دقيق', 'تسريحات عصرية', 'تصفيف مناسب للمناسبة'],
                'features_en' => ['Precision cut', 'Modern styling', 'Event-ready look'],
                'icon' => '✂️',
                'order' => 10,
                'is_active' => true,
            ],
            [
                'title_ar' => 'صبغة احترافية',
                'title_en' => 'Professional Hair Color',
                'label' => 'Hair',
                'description_ar' => 'ألوان احترافية بتكنيكات حديثة مع رعاية للشعر تمنح لونًا ثابتًا وصحيًا.',
                'description_en' => 'Professional coloring with modern techniques and hair care.',
                'features_ar' => ['صبغات آمنة', 'تدرجات لونية متعددة', 'عناية بعد الصبغة'],
                'features_en' => ['Safe coloring', 'Multiple shades', 'Post-color care'],
                'icon' => '🎨',
                'order' => 11,
                'is_active' => true,
            ],
            [
                'title_ar' => 'بروتين وكيراتين',
                'title_en' => 'Protein & Keratin Treatment',
                'label' => 'Hair',
                'description_ar' => 'علاج بالبروتين والكيراتين لتنعيم الشعر وتقوية الألياف وحماية اللون.',
                'description_en' => 'Protein and keratin treatments to strengthen and smooth hair.',
                'features_ar' => ['تغذية الشعر', 'تنعيم طويل الأمد', 'حماية من التلف'],
                'features_en' => ['Deep nourishment', 'Long-lasting smoothness', 'Damage protection'],
                'icon' => '🧴',
                'order' => 12,
                'is_active' => true,
            ],

            // Makeup
            [
                'title_ar' => 'سواريه',
                'title_en' => 'Evening Makeup',
                'label' => 'Makeup',
                'description_ar' => 'إطلالات سواريه فاخرة تناسب الحفلات والسهرات بأدوات احترافية.',
                'description_en' => 'Glam evening makeup tailored for parties and special nights.',
                'features_ar' => ['مكياج سواريه فخم', 'ثبات عالي', 'لمسات خاصة للكاميرا'],
                'features_en' => ['Lux evening makeup', 'Long-lasting', 'Photo-ready finish'],
                'icon' => '🌙',
                'order' => 20,
                'is_active' => true,
            ],
            [
                'title_ar' => 'خطوبة وزفاف',
                'title_en' => 'Engagement & Bridal Makeup',
                'label' => 'Makeup',
                'description_ar' => 'مكياج مخصص للخطوبة والزفاف مع جلسات تجريبية لضمان الإطلالة المثالية.',
                'description_en' => 'Custom engagement and bridal makeup with trial sessions.',
                'features_ar' => ['جلسة تجريبية', 'مكياج عروس', 'مظهر متكامل ليوم الزفاف'],
                'features_en' => ['Trial session', 'Bridal makeup', 'Wedding day look'],
                'icon' => '👰',
                'order' => 21,
                'is_active' => true,
            ],
            [
                'title_ar' => 'لوك يومي',
                'title_en' => 'Daily Makeup Look',
                'label' => 'Makeup',
                'description_ar' => 'لوك يومي ناعم وسريع يبرز جمالك الطبيعي مع لمسات خفيفة.',
                'description_en' => 'Soft daily makeup look that enhances natural beauty.',
                'features_ar' => ['لوك خفيف', 'سهل التكرار', 'خامات ناعمة'],
                'features_en' => ['Light look', 'Easy to repeat', 'Soft finish'],
                'icon' => '💄',
                'order' => 22,
                'is_active' => true,
            ],

            // Skin Care
            [
                'title_ar' => 'تنظيف عميق',
                'title_en' => 'Deep Facial Cleansing',
                'label' => 'Skin Care',
                'description_ar' => 'تنظيف عميق للمسام وإزالة الشوائب لتحضير البشرة للعلاجات اللاحقة.',
                'description_en' => 'Deep pore cleansing to prepare skin for advanced treatments.',
                'features_ar' => ['تنظيف مسام', 'إزالة شوائب', 'تحضير للبشرة'],
                'features_en' => ['Pore cleansing', 'Impurity removal', 'Skin prep'],
                'icon' => '🧖‍♀️',
                'order' => 30,
                'is_active' => true,
            ],
            [
                'title_ar' => 'Hydrafacial',
                'title_en' => 'Hydrafacial',
                'label' => 'Skin Care',
                'description_ar' => 'علاج هيدرافيشل لتجديد البشرة، ترطيب عميق وتحسين الملمس.',
                'description_en' => 'Hydrafacial for deep hydration, renewal and texture improvement.',
                'features_ar' => ['تجديد الخلايا', 'ترطيب عميق', 'نتائج فورية'],
                'features_en' => ['Cell renewal', 'Deep hydration', 'Immediate results'],
                'icon' => '💧',
                'order' => 31,
                'is_active' => true,
            ],
            [
                'title_ar' => 'علاج حب الشباب',
                'title_en' => 'Acne Treatment',
                'label' => 'Skin Care',
                'description_ar' => 'بروتوكولات علاجية لمقاومة وحل مشكلات حب الشباب بطرق طبية وتجميلية.',
                'description_en' => 'Therapeutic protocols for managing acne with clinical and cosmetic care.',
                'features_ar' => ['تقيم حالة البشرة', 'علاجات مخصصة', 'متابعة دورية'],
                'features_en' => ['Skin assessment', 'Personalized treatments', 'Follow-up care'],
                'icon' => '🩺',
                'order' => 32,
                'is_active' => true,
            ],

            // Nails
            [
                'title_ar' => 'Gel & Acrylic',
                'title_en' => 'Gel & Acrylic',
                'label' => 'Nails',
                'description_ar' => 'خدمات Gel وAcrylic مع لمسات فنية للحفاظ على جمال وقوة الأظافر.',
                'description_en' => 'Gel and acrylic services with professional finish and durability.',
                'features_ar' => ['تقوية أظافر', 'لمسات فنية', 'ثبات طويل'],
                'features_en' => ['Nail strengthening', 'Artistry', 'Long-lasting'],
                'icon' => '💅',
                'order' => 40,
                'is_active' => true,
            ],
            [
                'title_ar' => 'Nail Art',
                'title_en' => 'Nail Art',
                'label' => 'Nails',
                'description_ar' => 'تصاميم نيل آرت فاخرة تناسب المناسبات والعرائس.',
                'description_en' => 'Luxury nail art designs for events and bridal styling.',
                'features_ar' => ['رسومات فنية', 'تصاميم مخصصة', 'مواد عالية الجودة'],
                'features_en' => ['Art designs', 'Custom concepts', 'High-quality materials'],
                'icon' => '🎨',
                'order' => 41,
                'is_active' => true,
            ],
            [
                'title_ar' => 'Bridal Nails',
                'title_en' => 'Bridal Nails',
                'label' => 'Nails',
                'description_ar' => 'تجهيز أظافر العروس بتصاميم خاصة لتكمل إطلالتها في يوم الزفاف.',
                'description_en' => 'Bridal nail styling to complement the wedding look.',
                'features_ar' => ['تصاميم عروس', 'لمسات فاخرة', 'ثبات للصور'],
                'features_en' => ['Bridal designs', 'Luxury touches', 'Photo-ready'],
                'icon' => '💍',
                'order' => 42,
                'is_active' => true,
            ],

            // Spa
            [
                'title_ar' => 'مساج استرخائي',
                'title_en' => 'Relax Massage',
                'label' => 'Spa',
                'description_ar' => 'جلسات مساج مريحة تساعد على استرخاء العضلات وتجديد الطاقة.',
                'description_en' => 'Soothing massage sessions to relax muscles and restore energy.',
                'features_ar' => ['جلسة مريحة', 'تخفيف توتر', 'عناية مميزة'],
                'features_en' => ['Relaxing session', 'Tension relief', 'Premium care'],
                'icon' => '💆‍♀️',
                'order' => 50,
                'is_active' => true,
            ],
            [
                'title_ar' => 'حمام مغربي',
                'title_en' => 'Moroccan Bath',
                'label' => 'Spa',
                'description_ar' => 'تجربة الحمام المغربي التقليدي مع تقشير وتغذية للبشرة.',
                'description_en' => 'Traditional Moroccan bath experience with exfoliation and nourishment.',
                'features_ar' => ['تقشير', 'تدفئة', 'تغذية البشرة'],
                'features_en' => ['Exfoliation', 'Steam ritual', 'Skin nourishment'],
                'icon' => '🛁',
                'order' => 51,
                'is_active' => true,
            ],
            [
                'title_ar' => 'علاجات فاخرة',
                'title_en' => 'Luxury Treatments',
                'label' => 'Spa',
                'description_ar' => 'باقات علاجات فاخرة مخصصة لتجديد الجسم والبشرة بمواد متميزة.',
                'description_en' => 'Tailored luxury treatments using premium products for full rejuvenation.',
                'features_ar' => ['باقات مخصصة', 'منتجات فاخرة', 'نتائج ملحوظة'],
                'features_en' => ['Custom packages', 'Premium products', 'Notable results'],
                'icon' => '✨',
                'order' => 52,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['title_ar' => $service['title_ar']],
                $service
            );
        }
    }
}
