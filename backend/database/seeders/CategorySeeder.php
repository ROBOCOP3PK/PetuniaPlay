<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AnimalSection;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la sección de Perros (debe existir del AnimalSectionSeeder)
        $perrosSection = AnimalSection::where('slug', 'perros')->first();

        if (!$perrosSection) {
            throw new \Exception('La sección de Perros debe existir antes de crear categorías. Ejecuta AnimalSectionSeeder primero.');
        }

        // Categorías principales para PERROS
        $alimentosPerros = Category::create([
            'animal_section_id' => $perrosSection->id,
            'name' => 'Alimentos',
            'slug' => 'alimentos-perros',
            'description' => 'Alimento balanceado y premium para perros de todas las edades',
            'image' => 'https://via.placeholder.com/400x300.png?text=Alimentos+Perros',
            'parent_id' => null,
            'order' => 1,
            'is_active' => true,
        ]);

        $juguetesPerros = Category::create([
            'animal_section_id' => $perrosSection->id,
            'name' => 'Juguetes',
            'slug' => 'juguetes-perros',
            'description' => 'Juguetes interactivos y de entretenimiento para perros',
            'image' => 'https://via.placeholder.com/400x300.png?text=Juguetes+Perros',
            'parent_id' => null,
            'order' => 2,
            'is_active' => true,
        ]);

        $accesoriosPerros = Category::create([
            'animal_section_id' => $perrosSection->id,
            'name' => 'Accesorios',
            'slug' => 'accesorios-perros',
            'description' => 'Collares, correas, camas y más accesorios para perros',
            'image' => 'https://via.placeholder.com/400x300.png?text=Accesorios+Perros',
            'parent_id' => null,
            'order' => 3,
            'is_active' => true,
        ]);

        $saludPerros = Category::create([
            'animal_section_id' => $perrosSection->id,
            'name' => 'Salud e Higiene',
            'slug' => 'salud-higiene-perros',
            'description' => 'Productos de salud, higiene y cuidado para perros',
            'image' => 'https://via.placeholder.com/400x300.png?text=Salud+Perros',
            'parent_id' => null,
            'order' => 4,
            'is_active' => true,
        ]);

        // Subcategorías de Alimentos para Perros - Usando relaciones Eloquent
        $alimentosPerros->children()->createMany([
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Alimento Seco',
                'slug' => 'alimento-seco-perros',
                'description' => 'Concentrado y croquetas para perros',
                'image' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Alimento Húmedo',
                'slug' => 'alimento-humedo-perros',
                'description' => 'Comida húmeda en lata y pouch',
                'image' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Snacks y Premios',
                'slug' => 'snacks-perros',
                'description' => 'Premios, golosinas y huesos',
                'image' => null,
                'order' => 3,
                'is_active' => true,
            ],
        ]);

        // Subcategorías de Accesorios para Perros - Usando relaciones Eloquent
        $accesoriosPerros->children()->createMany([
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Collares y Correas',
                'slug' => 'collares-correas-perros',
                'description' => 'Collares, correas y arneses para paseo',
                'image' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Camas y Mantas',
                'slug' => 'camas-perros',
                'description' => 'Camas, colchonetas y mantas para el descanso',
                'image' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Comederos y Bebederos',
                'slug' => 'comederos-perros',
                'description' => 'Platos, comederos y bebederos',
                'image' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $perrosSection->id,
                'name' => 'Transportadoras',
                'slug' => 'transportadoras-perros',
                'description' => 'Transportadoras y mochilas para viaje',
                'image' => null,
                'order' => 4,
                'is_active' => true,
            ],
        ]);

        // ===================================================
        // CATEGORÍAS PARA GATOS
        // ===================================================

        // Obtener la sección de Gatos
        $gatosSection = AnimalSection::where('slug', 'gatos')->first();

        if (!$gatosSection) {
            throw new \Exception('La sección de Gatos debe existir antes de crear categorías. Ejecuta AnimalSectionSeeder primero.');
        }

        // Categorías principales para GATOS
        $alimentosGatos = Category::create([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Alimentos',
            'slug' => 'alimentos-gatos',
            'description' => 'Alimento balanceado y premium para gatos de todas las edades',
            'image' => 'https://via.placeholder.com/400x300.png?text=Alimentos+Gatos',
            'parent_id' => null,
            'order' => 1,
            'is_active' => true,
        ]);

        $juguetesGatos = Category::create([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Juguetes',
            'slug' => 'juguetes-gatos',
            'description' => 'Juguetes interactivos, ratones y varitas para gatos',
            'image' => 'https://via.placeholder.com/400x300.png?text=Juguetes+Gatos',
            'parent_id' => null,
            'order' => 2,
            'is_active' => true,
        ]);

        $accesoriosGatos = Category::create([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Accesorios',
            'slug' => 'accesorios-gatos',
            'description' => 'Rascadores, camas, comederos y más accesorios para gatos',
            'image' => 'https://via.placeholder.com/400x300.png?text=Accesorios+Gatos',
            'parent_id' => null,
            'order' => 3,
            'is_active' => true,
        ]);

        $saludGatos = Category::create([
            'animal_section_id' => $gatosSection->id,
            'name' => 'Salud e Higiene',
            'slug' => 'salud-higiene-gatos',
            'description' => 'Productos de salud, higiene y arena para gatos',
            'image' => 'https://via.placeholder.com/400x300.png?text=Salud+Gatos',
            'parent_id' => null,
            'order' => 4,
            'is_active' => true,
        ]);

        // Subcategorías de Alimentos para Gatos - Usando relaciones Eloquent
        $alimentosGatos->children()->createMany([
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Alimento Seco',
                'slug' => 'alimento-seco-gatos',
                'description' => 'Concentrado y croquetas para gatos',
                'image' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Alimento Húmedo',
                'slug' => 'alimento-humedo-gatos',
                'description' => 'Comida húmeda en lata y pouch para gatos',
                'image' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Snacks y Premios',
                'slug' => 'snacks-gatos',
                'description' => 'Premios, golosinas y treats para gatos',
                'image' => null,
                'order' => 3,
                'is_active' => true,
            ],
        ]);

        // Subcategorías de Accesorios para Gatos - Usando relaciones Eloquent
        $accesoriosGatos->children()->createMany([
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Rascadores',
                'slug' => 'rascadores-gatos',
                'description' => 'Rascadores, postes y torres para gatos',
                'image' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Camas y Mantas',
                'slug' => 'camas-gatos',
                'description' => 'Camas, cuevas y mantas para el descanso',
                'image' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Comederos y Bebederos',
                'slug' => 'comederos-gatos',
                'description' => 'Platos, comederos automáticos y fuentes',
                'image' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Transportadoras',
                'slug' => 'transportadoras-gatos',
                'description' => 'Transportadoras y mochilas para viaje',
                'image' => null,
                'order' => 4,
                'is_active' => true,
            ],
        ]);

        // Subcategorías de Salud para Gatos - Usando relaciones Eloquent
        $saludGatos->children()->createMany([
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Arena Sanitaria',
                'slug' => 'arena-gatos',
                'description' => 'Arena sanitaria aglutinante y de sílica',
                'image' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'animal_section_id' => $gatosSection->id,
                'name' => 'Areneros',
                'slug' => 'areneros-gatos',
                'description' => 'Areneros cubiertos, abiertos y automáticos',
                'image' => null,
                'order' => 2,
                'is_active' => true,
            ],
        ]);
    }
}
