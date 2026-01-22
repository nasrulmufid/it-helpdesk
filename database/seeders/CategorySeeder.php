<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware',
                'description' => 'Masalah terkait perangkat keras seperti komputer, printer, mouse, keyboard, dll.',
                'icon' => '💻',
            ],
            [
                'name' => 'Software',
                'description' => 'Masalah terkait aplikasi dan software yang digunakan.',
                'icon' => '📱',
            ],
            [
                'name' => 'Network',
                'description' => 'Masalah terkait koneksi internet dan jaringan.',
                'icon' => '🌐',
            ],
            [
                'name' => 'Email',
                'description' => 'Masalah terkait email dan komunikasi.',
                'icon' => '📧',
            ],
            [
                'name' => 'Database',
                'description' => 'Masalah terkait database dan penyimpanan data.',
                'icon' => '🗄️',
            ],
            [
                'name' => 'Security',
                'description' => 'Masalah terkait keamanan sistem dan data.',
                'icon' => '🔒',
            ],
            [
                'name' => 'Account',
                'description' => 'Masalah terkait akun pengguna dan hak akses.',
                'icon' => '👤',
            ],
            [
                'name' => 'Other',
                'description' => 'Masalah lainnya yang tidak termasuk kategori di atas.',
                'icon' => '📋',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'is_active' => true,
            ]);
        }
    }
}
