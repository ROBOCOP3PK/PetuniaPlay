<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de blog usando Eloquent
        $cuidado = BlogCategory::create([
            'name' => 'Cuidado de Mascotas',
            'slug' => 'cuidado-mascotas',
            'description' => 'Consejos y guías para el cuidado de tus mascotas',
            'is_active' => true,
        ]);

        $nutricion = BlogCategory::create([
            'name' => 'Nutrición',
            'slug' => 'nutricion',
            'description' => 'Todo sobre alimentación balanceada para mascotas',
            'is_active' => true,
        ]);

        $entrenamiento = BlogCategory::create([
            'name' => 'Entrenamiento',
            'slug' => 'entrenamiento',
            'description' => 'Tips y técnicas de entrenamiento para perros y gatos',
            'is_active' => true,
        ]);

        $salud = BlogCategory::create([
            'name' => 'Salud',
            'slug' => 'salud',
            'description' => 'Información sobre salud y bienestar de mascotas',
            'is_active' => true,
        ]);

        BlogCategory::create([
            'name' => 'Historias',
            'slug' => 'historias',
            'description' => 'Historias inspiradoras sobre mascotas',
            'is_active' => true,
        ]);

        // Obtener el primer usuario (admin) para asignar como autor
        $adminUser = User::first();

        if (!$adminUser) {
            $this->command->warn('⚠️  No se encontró un usuario para asignar como autor de los posts');
            return;
        }

        // Crear algunos posts del blog usando relaciones Eloquent
        $cuidado->posts()->create([
            'user_id' => $adminUser->id,
            'title' => '10 Consejos Esenciales para el Cuidado de tu Perro',
            'slug' => '10-consejos-cuidado-perro',
            'excerpt' => 'Aprende los fundamentos del cuidado canino con estos consejos prácticos.',
            'content' => '<h2>Introducción</h2><p>Cuidar de un perro requiere dedicación y conocimiento. Aquí te presentamos 10 consejos esenciales...</p>',
            'featured_image' => 'https://via.placeholder.com/800x400.png?text=Cuidado+de+Perros',
            'is_published' => true,
            'published_at' => now()->subDays(10),
            'meta_title' => '10 Consejos Esenciales para el Cuidado de tu Perro',
            'meta_description' => 'Guía completa con consejos prácticos para cuidar de tu perro',
            'meta_keywords' => 'cuidado perros, consejos, mascotas',
            'views_count' => 125,
        ]);

        $nutricion->posts()->create([
            'user_id' => $adminUser->id,
            'title' => 'Guía Completa de Alimentación para Gatos',
            'slug' => 'guia-alimentacion-gatos',
            'excerpt' => 'Todo lo que necesitas saber sobre la nutrición felina.',
            'content' => '<h2>Nutrición Felina</h2><p>Los gatos tienen necesidades nutricionales específicas. En esta guía completa aprenderás...</p>',
            'featured_image' => 'https://via.placeholder.com/800x400.png?text=Nutricion+Gatos',
            'is_published' => true,
            'published_at' => now()->subDays(7),
            'meta_title' => 'Guía Completa de Alimentación para Gatos',
            'meta_description' => 'Aprende sobre la nutrición adecuada para tu gato',
            'meta_keywords' => 'alimentación gatos, nutrición felina',
            'views_count' => 98,
        ]);

        $entrenamiento->posts()->create([
            'user_id' => $adminUser->id,
            'title' => 'Cómo Entrenar a tu Cachorro: Primeros Pasos',
            'slug' => 'entrenar-cachorro-primeros-pasos',
            'excerpt' => 'Los fundamentos del entrenamiento canino desde temprana edad.',
            'content' => '<h2>Entrenamiento Básico</h2><p>El entrenamiento debe comenzar desde que tu cachorro llega a casa. Los primeros meses son cruciales...</p>',
            'featured_image' => 'https://via.placeholder.com/800x400.png?text=Entrenamiento+Cachorros',
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'meta_title' => 'Cómo Entrenar a tu Cachorro: Primeros Pasos',
            'meta_description' => 'Guía de entrenamiento básico para cachorros',
            'meta_keywords' => 'entrenamiento cachorros, adiestramiento',
            'views_count' => 210,
        ]);

        $salud->posts()->create([
            'user_id' => $adminUser->id,
            'title' => 'Señales de Salud: Cuándo Llevar a tu Mascota al Veterinario',
            'slug' => 'senales-salud-veterinario',
            'excerpt' => 'Identifica las señales de alerta que requieren atención veterinaria.',
            'content' => '<h2>Salud de tu Mascota</h2><p>Es importante conocer las señales que indican que tu mascota necesita atención médica...</p>',
            'featured_image' => 'https://via.placeholder.com/800x400.png?text=Salud+Mascotas',
            'is_published' => true,
            'published_at' => now()->subDays(3),
            'meta_title' => 'Señales de Salud en Mascotas',
            'meta_description' => 'Aprende a identificar cuándo tu mascota necesita ir al veterinario',
            'meta_keywords' => 'salud mascotas, veterinario, señales',
            'views_count' => 156,
        ]);

        $this->command->info('✅ Categorías y posts de blog creados exitosamente');
    }
}
