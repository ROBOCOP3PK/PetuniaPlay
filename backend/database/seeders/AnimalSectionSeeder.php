<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AnimalSection;
use Illuminate\Support\Str;

class AnimalSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Perros',
                'slug' => 'perros',
                'icon' => '🐕',
                'description' => 'Productos y accesorios para perros de todas las razas y tamaños',
                'order' => 1,
                'is_active' => true, // ACTIVO inicialmente
            ],
            [
                'name' => 'Gatos',
                'slug' => 'gatos',
                'icon' => '🐈',
                'description' => 'Todo lo que tu gato necesita para ser feliz',
                'order' => 2,
                'is_active' => false, // Preparado pero inactivo
            ],
            [
                'name' => 'Aves',
                'slug' => 'aves',
                'icon' => '🦜',
                'description' => 'Productos para loros, canarios y otras aves',
                'order' => 3,
                'is_active' => false,
            ],
            [
                'name' => 'Peces',
                'slug' => 'peces',
                'icon' => '🐠',
                'description' => 'Acuarios y accesorios para peces ornamentales',
                'order' => 4,
                'is_active' => false,
            ],
            [
                'name' => 'Roedores',
                'slug' => 'roedores',
                'icon' => '🐹',
                'description' => 'Para hámsters, conejos, cobayas y más',
                'order' => 5,
                'is_active' => false,
            ],
            [
                'name' => 'Reptiles',
                'slug' => 'reptiles',
                'icon' => '🦎',
                'description' => 'Productos especializados para reptiles',
                'order' => 6,
                'is_active' => false,
            ],
        ];

        foreach ($sections as $section) {
            AnimalSection::create($section);
        }
    }
}
