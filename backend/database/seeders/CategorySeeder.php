<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías principales
        $perros = DB::table('categories')->insertGetId([
            'name' => 'Perros',
            'slug' => 'perros',
            'description' => 'Productos para perros de todas las razas y tamaños',
            'image' => 'https://via.placeholder.com/400x300.png?text=Perros',
            'parent_id' => null,
            'order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $gatos = DB::table('categories')->insertGetId([
            'name' => 'Gatos',
            'slug' => 'gatos',
            'description' => 'Todo para el cuidado y entretenimiento de tu gato',
            'image' => 'https://via.placeholder.com/400x300.png?text=Gatos',
            'parent_id' => null,
            'order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accesorios = DB::table('categories')->insertGetId([
            'name' => 'Accesorios',
            'slug' => 'accesorios',
            'description' => 'Accesorios y complementos para mascotas',
            'image' => 'https://via.placeholder.com/400x300.png?text=Accesorios',
            'parent_id' => null,
            'order' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Subcategorías para Perros
        DB::table('categories')->insert([
            [
                'name' => 'Alimentos',
                'slug' => 'perros-alimentos',
                'description' => 'Alimento balanceado para perros',
                'image' => null,
                'parent_id' => $perros,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juguetes',
                'slug' => 'perros-juguetes',
                'description' => 'Juguetes para perros',
                'image' => null,
                'parent_id' => $perros,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Camas y Casas',
                'slug' => 'perros-camas',
                'description' => 'Camas y casas para perros',
                'image' => null,
                'parent_id' => $perros,
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Subcategorías para Gatos
        DB::table('categories')->insert([
            [
                'name' => 'Alimentos',
                'slug' => 'gatos-alimentos',
                'description' => 'Alimento balanceado para gatos',
                'image' => null,
                'parent_id' => $gatos,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juguetes',
                'slug' => 'gatos-juguetes',
                'description' => 'Juguetes para gatos',
                'image' => null,
                'parent_id' => $gatos,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rascadores',
                'slug' => 'gatos-rascadores',
                'description' => 'Rascadores y árboles para gatos',
                'image' => null,
                'parent_id' => $gatos,
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Subcategorías para Accesorios
        DB::table('categories')->insert([
            [
                'name' => 'Collares y Correas',
                'slug' => 'accesorios-collares',
                'description' => 'Collares, correas y arneses',
                'image' => null,
                'parent_id' => $accesorios,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Comederos y Bebederos',
                'slug' => 'accesorios-comederos',
                'description' => 'Comederos y bebederos',
                'image' => null,
                'parent_id' => $accesorios,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transportadoras',
                'slug' => 'accesorios-transportadoras',
                'description' => 'Transportadoras y mochilas',
                'image' => null,
                'parent_id' => $accesorios,
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
