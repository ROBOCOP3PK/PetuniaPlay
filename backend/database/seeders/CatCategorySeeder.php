<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AnimalSection;

class CatCategorySeeder extends Seeder
{
    /**
     * Run the database seeds for CAT categories ONLY
     */
    public function run(): void
    {
        // Obtener la sección de Gatos
        $gatosSection = AnimalSection::where('slug', 'gatos')->first();

        if (!$gatosSection) {
            throw new \Exception('La sección de Gatos debe existir antes de crear categorías. Ejecuta AnimalSectionSeeder primero.');
        }

        // Categorías principales para GATOS
        $alimentosGatos = DB::table('categories')->insertGetId([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Alimentos',
            'slug' => 'alimentos-gatos',
            'description' => 'Alimento balanceado y premium para gatos de todas las edades',
            'image' => 'https://via.placeholder.com/400x300.png?text=Alimentos+Gatos',
            'parent_id' => null,
            'order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $juguetesGatos = DB::table('categories')->insertGetId([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Juguetes',
            'slug' => 'juguetes-gatos',
            'description' => 'Juguetes interactivos, ratones y varitas para gatos',
            'image' => 'https://via.placeholder.com/400x300.png?text=Juguetes+Gatos',
            'parent_id' => null,
            'order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accesoriosGatos = DB::table('categories')->insertGetId([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Accesorios',
            'slug' => 'accesorios-gatos',
            'description' => 'Rascadores, camas, comederos y más accesorios para gatos',
            'image' => 'https://via.placeholder.com/400x300.png?text=Accesorios+Gatos',
            'parent_id' => null,
            'order' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $saludGatos = DB::table('categories')->insertGetId([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Salud e Higiene',
            'slug' => 'salud-higiene-gatos',
            'description' => 'Productos de salud, higiene y arena para gatos',
            'image' => 'https://via.placeholder.com/400x300.png?text=Salud+Gatos',
            'parent_id' => null,
            'order' => 4,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Subcategorías de Alimentos para Gatos
        DB::table('categories')->insert([
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Alimento Seco',
                'slug' => 'alimento-seco-gatos',
                'description' => 'Concentrado y croquetas para gatos',
                'image' => null,
                'parent_id' => $alimentosGatos,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Alimento Húmedo',
                'slug' => 'alimento-humedo-gatos',
                'description' => 'Comida húmeda en lata y pouch para gatos',
                'image' => null,
                'parent_id' => $alimentosGatos,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Snacks y Premios',
                'slug' => 'snacks-gatos',
                'description' => 'Premios, golosinas y treats para gatos',
                'image' => null,
                'parent_id' => $alimentosGatos,
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Subcategorías de Accesorios para Gatos
        DB::table('categories')->insert([
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Rascadores',
                'slug' => 'rascadores-gatos',
                'description' => 'Rascadores, postes y torres para gatos',
                'image' => null,
                'parent_id' => $accesoriosGatos,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Camas y Mantas',
                'slug' => 'camas-gatos',
                'description' => 'Camas, cuevas y mantas para el descanso',
                'image' => null,
                'parent_id' => $accesoriosGatos,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Comederos y Bebederos',
                'slug' => 'comederos-gatos',
                'description' => 'Platos, comederos automáticos y fuentes',
                'image' => null,
                'parent_id' => $accesoriosGatos,
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Transportadoras',
                'slug' => 'transportadoras-gatos',
                'description' => 'Transportadoras y mochilas para viaje',
                'image' => null,
                'parent_id' => $accesoriosGatos,
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Subcategorías de Salud para Gatos
        DB::table('categories')->insert([
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Arena Sanitaria',
                'slug' => 'arena-gatos',
                'description' => 'Arena sanitaria aglutinante y de sílica',
                'image' => null,
                'parent_id' => $saludGatos,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Areneros',
                'slug' => 'areneros-gatos',
                'description' => 'Areneros cubiertos, abiertos y automáticos',
                'image' => null,
                'parent_id' => $saludGatos,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ Categorías de GATOS creadas exitosamente');
    }
}
