<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $testimonials = [
            [
                'rating' => 5,
                'client_name' => 'منة أحمد',
                'client_role_ar' => '',
                'content_ar' => 'أفضل مركز تجميل زرته في حياتي، الخدمة ممتازة جداً.',
                'is_active' => true,
            ],
            [
                'rating' => 5,
                'client_name' => 'سارة علي',
                'client_role_ar' => '',
                'content_ar' => 'الميكاب كان أكثر من رائع، شكراً لفريق AUR.',
                'is_active' => true,
            ],
            [
                'rating' => 5,
                'client_name' => 'ندى محمد',
                'client_role_ar' => '',
                'content_ar' => 'الهيدرافيشل فرق مع بشرتي بشكل كبير.',
                'is_active' => true,
            ],
            [
                'rating' => 5,
                'client_name' => 'إسراء حسن',
                'client_role_ar' => '',
                'content_ar' => 'أفضل تسريحة شعر عملتها قبل فرحي.',
                'is_active' => true,
            ],
            [
                'rating' => 5,
                'client_name' => 'هبة خالد',
                'client_role_ar' => '',
                'content_ar' => 'السبا والحمام المغربي كانوا رائعين.',
                'is_active' => true,
            ],
            [
                'rating' => 5,
                'client_name' => 'مريم سمير',
                'client_role_ar' => '',
                'content_ar' => 'طاقم محترف جداً والتعامل راقي.',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate([
                'client_name' => $t['client_name'],
                'content_ar' => $t['content_ar'],
            ], $t);
        }
    }
}
