<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Get demo images for products based on SKU
     */
    private function getProductImages(string $sku): array
    {
        // Mapeo de SKUs a imágenes demo de Unsplash/Picsum
        $imageMap = [
            // Alimentos para Perros - Seco
            'FOOD-DOG-001' => [
                'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1581888227599-779811939961?w=500&h=500&fit=crop',
            ],
            'FOOD-DOG-002' => [
                'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1581888227599-779811939961?w=500&h=500&fit=crop',
            ],
            'FOOD-DOG-003' => [
                'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1581888227599-779811939961?w=500&h=500&fit=crop',
            ],

            // Alimentos para Perros - Húmedo
            'FOOD-DOG-WET-001' => [
                'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1581888227599-779811939961?w=500&h=500&fit=crop',
            ],
            'FOOD-DOG-WET-002' => [
                'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1581888227599-779811939961?w=500&h=500&fit=crop',
            ],

            // Snacks para Perros
            'SNACK-DOG-001' => [
                'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500&h=500&fit=crop',
            ],
            'SNACK-DOG-002' => [
                'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500&h=500&fit=crop',
            ],
            'SNACK-DOG-003' => [
                'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500&h=500&fit=crop',
            ],

            // Juguetes para Perros
            'TOY-DOG-001' => [
                'https://images.unsplash.com/photo-1535294435445-d7249524ef2e?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1591768575338-74dd8e50f5f1?w=500&h=500&fit=crop',
            ],
            'TOY-DOG-002' => [
                'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=500&h=500&fit=crop',
            ],
            'TOY-DOG-003' => [
                'https://images.unsplash.com/photo-1535294435445-d7249524ef2e?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1591768575338-74dd8e50f5f1?w=500&h=500&fit=crop',
            ],

            // Collares y Correas
            'COL-DOG-001' => [
                'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=500&h=500&fit=crop',
            ],
            'LEA-DOG-001' => [
                'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1522276498395-f4f68f7f8454?w=500&h=500&fit=crop',
            ],
            'COL-DOG-002' => [
                'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=500&h=500&fit=crop',
            ],

            // Camas
            'BED-DOG-001' => [
                'https://images.unsplash.com/photo-1563460716037-460a3ad24ba9?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1585664811087-47f65abbad64?w=500&h=500&fit=crop',
            ],
            'BED-DOG-002' => [
                'https://images.unsplash.com/photo-1563460716037-460a3ad24ba9?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1585664811087-47f65abbad64?w=500&h=500&fit=crop',
            ],

            // Comederos y Bebederos
            'BOWL-DOG-001' => [
                'https://images.unsplash.com/photo-1591135905399-87b390a3a6e8?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1585664811087-47f65abbad64?w=500&h=500&fit=crop',
            ],
            'BOWL-DOG-002' => [
                'https://images.unsplash.com/photo-1591135905399-87b390a3a6e8?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1585664811087-47f65abbad64?w=500&h=500&fit=crop',
            ],

            // Transportadoras
            'CARR-DOG-001' => [
                'https://images.unsplash.com/photo-1603003573425-e4227c28e95e?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1519052537078-e6302a4968d4?w=500&h=500&fit=crop',
            ],
            'CARR-DOG-002' => [
                'https://images.unsplash.com/photo-1603003573425-e4227c28e95e?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1519052537078-e6302a4968d4?w=500&h=500&fit=crop',
            ],

            // Salud e Higiene
            'HEALTH-DOG-001' => [
                'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500&h=500&fit=crop',
            ],
            'HEALTH-DOG-002' => [
                'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500&h=500&fit=crop',
            ],
        ];

        return $imageMap[$sku] ?? [
            'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=500&h=500&fit=crop',
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las categorías usando sus slugs
        $catAlimentoSecoPerros = Category::where('slug', 'alimento-seco-perros')->first()->id;
        $catAlimentoHumedoPerros = Category::where('slug', 'alimento-humedo-perros')->first()->id;
        $catSnacksPerros = Category::where('slug', 'snacks-perros')->first()->id;
        $catJuguetesPerros = Category::where('slug', 'juguetes-perros')->first()->id;
        $catCollaresCorreasPerros = Category::where('slug', 'collares-correas-perros')->first()->id;
        $catCamasPerros = Category::where('slug', 'camas-perros')->first()->id;
        $catComederosPerros = Category::where('slug', 'comederos-perros')->first()->id;
        $catTransportadorasPerros = Category::where('slug', 'transportadoras-perros')->first()->id;
        $catSaludHigienePerros = Category::where('slug', 'salud-higiene-perros')->first()->id;

        $products = [
            // ALIMENTO SECO PARA PERROS (3 productos)
            [
                'category_id' => $catAlimentoSecoPerros,
                'name' => 'Alimento Premium para Perros Adultos',
                'slug' => 'alimento-premium-perros-adultos',
                'description' => 'Alimento balanceado premium para perros adultos de todas las razas',
                'long_description' => 'Nutrición completa y balanceada para perros adultos. Contiene proteínas de alta calidad, vitaminas y minerales esenciales. Sin colorantes artificiales.',
                'price' => 85000.00,
                'sale_price' => 75000.00,
                'sku' => 'FOOD-DOG-001',
                'stock' => 50,
                'weight' => 3.00,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Alimento Premium para Perros - Petunia Play',
                'meta_description' => 'Alimento balanceado premium para perros adultos con nutrición completa',
                'meta_keywords' => 'alimento perros, comida perros, premium',
            ],
            [
                'category_id' => $catAlimentoSecoPerros,
                'name' => 'Alimento para Cachorros',
                'slug' => 'alimento-cachorros-premium',
                'description' => 'Nutrición especial para cachorros en crecimiento',
                'long_description' => 'Fórmula especialmente diseñada para cachorros con DHA para desarrollo cerebral y visual. Rico en proteínas y calcio para crecimiento saludable.',
                'price' => 95000.00,
                'sale_price' => 85000.00,
                'sku' => 'FOOD-DOG-002',
                'stock' => 40,
                'weight' => 3.00,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Alimento Premium para Cachorros',
                'meta_description' => 'Nutrición completa para cachorros en crecimiento',
                'meta_keywords' => 'alimento cachorros, comida cachorros, premium',
            ],
            [
                'category_id' => $catAlimentoSecoPerros,
                'name' => 'Alimento para Perros Senior',
                'slug' => 'alimento-perros-senior',
                'description' => 'Fórmula especial para perros mayores de 7 años',
                'long_description' => 'Alimento formulado para las necesidades de perros senior. Bajo en calorías, alto en fibra, con glucosamina para articulaciones saludables.',
                'price' => 90000.00,
                'sale_price' => null,
                'sku' => 'FOOD-DOG-003',
                'stock' => 35,
                'weight' => 3.00,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Alimento para Perros Senior',
                'meta_description' => 'Nutrición especial para perros mayores',
                'meta_keywords' => 'alimento senior, perros viejos, tercera edad',
            ],

            // ALIMENTO HÚMEDO PARA PERROS (2 productos)
            [
                'category_id' => $catAlimentoHumedoPerros,
                'name' => 'Paté de Pollo para Perros',
                'slug' => 'pate-pollo-perros',
                'description' => 'Delicioso paté de pollo natural',
                'long_description' => 'Paté premium elaborado con pollo real y vegetales. Alto contenido de proteína, sin conservantes artificiales. Lata de 375g.',
                'price' => 12000.00,
                'sale_price' => 10000.00,
                'sku' => 'FOOD-DOG-WET-001',
                'stock' => 80,
                'weight' => 0.375,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Paté de Pollo para Perros',
                'meta_description' => 'Delicioso paté de pollo natural en lata',
                'meta_keywords' => 'pate perros, comida humeda, pollo',
            ],
            [
                'category_id' => $catAlimentoHumedoPerros,
                'name' => 'Paté de Carne para Perros',
                'slug' => 'pate-carne-perros',
                'description' => 'Nutritivo paté de carne de res',
                'long_description' => 'Paté gourmet con carne de res, arroz y vegetales. Ideal para complementar la dieta de tu perro. Lata de 375g.',
                'price' => 13000.00,
                'sale_price' => null,
                'sku' => 'FOOD-DOG-WET-002',
                'stock' => 70,
                'weight' => 0.375,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Paté de Carne para Perros',
                'meta_description' => 'Nutritivo paté de carne de res',
                'meta_keywords' => 'pate perros, comida humeda, carne',
            ],

            // SNACKS PARA PERROS (3 productos)
            [
                'category_id' => $catSnacksPerros,
                'name' => 'Snacks Dentales para Perros',
                'slug' => 'snacks-dentales-perros',
                'description' => 'Snacks que ayudan a limpiar los dientes de tu perro',
                'long_description' => 'Deliciosos snacks con textura especial que ayuda a reducir el sarro y mantener los dientes limpios. Sabor a pollo.',
                'price' => 25000.00,
                'sale_price' => null,
                'sku' => 'SNACK-DOG-001',
                'stock' => 100,
                'weight' => 0.30,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Snacks Dentales para Perros',
                'meta_description' => 'Snacks que ayudan a limpiar los dientes de tu perro',
                'meta_keywords' => 'snacks perros, dental, higiene',
            ],
            [
                'category_id' => $catSnacksPerros,
                'name' => 'Huesos de Cuero Natural',
                'slug' => 'huesos-cuero-natural-perros',
                'description' => 'Huesos masticables de cuero 100% natural',
                'long_description' => 'Huesos de cuero natural que ayudan a mantener los dientes limpios y entretienen a tu perro. Pack de 3 unidades.',
                'price' => 18000.00,
                'sale_price' => 15000.00,
                'sku' => 'SNACK-DOG-002',
                'stock' => 65,
                'weight' => 0.25,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Huesos de Cuero Natural',
                'meta_description' => 'Huesos masticables 100% naturales',
                'meta_keywords' => 'huesos perros, cuero natural, snacks',
            ],
            [
                'category_id' => $catSnacksPerros,
                'name' => 'Galletas Sabor Carne',
                'slug' => 'galletas-sabor-carne-perros',
                'description' => 'Galletas crujientes con delicioso sabor a carne',
                'long_description' => 'Galletas horneadas con ingredientes naturales. Ideales como premio durante el entrenamiento. Bolsa de 500g.',
                'price' => 20000.00,
                'sale_price' => null,
                'sku' => 'SNACK-DOG-003',
                'stock' => 85,
                'weight' => 0.50,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Galletas Sabor Carne para Perros',
                'meta_description' => 'Galletas crujientes ideales como premio',
                'meta_keywords' => 'galletas perros, premio, entrenamiento',
            ],

            // JUGUETES PARA PERROS (3 productos)
            [
                'category_id' => $catJuguetesPerros,
                'name' => 'Pelota de Goma Resistente',
                'slug' => 'pelota-goma-resistente-perros',
                'description' => 'Pelota de goma ultra resistente para perros',
                'long_description' => 'Pelota de goma natural, perfecta para juegos de lanzar y traer. Resistente a mordidas. Disponible en varios colores.',
                'price' => 15000.00,
                'sale_price' => 12000.00,
                'sku' => 'TOY-DOG-001',
                'stock' => 75,
                'weight' => 0.20,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Pelota de Goma Resistente para Perros',
                'meta_description' => 'Pelota de goma ultra resistente, perfecta para juegos',
                'meta_keywords' => 'juguetes perros, pelota, goma',
            ],
            [
                'category_id' => $catJuguetesPerros,
                'name' => 'Cuerda Dental Juguete',
                'slug' => 'cuerda-dental-juguete-perros',
                'description' => 'Cuerda de algodón para jugar y limpiar dientes',
                'long_description' => 'Juguete de cuerda de algodón natural que ayuda a limpiar los dientes mientras juega. Ideal para perros de todos los tamaños.',
                'price' => 18000.00,
                'sale_price' => null,
                'sku' => 'TOY-DOG-002',
                'stock' => 60,
                'weight' => 0.25,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Cuerda Dental Juguete para Perros',
                'meta_description' => 'Cuerda de algodón para jugar y limpiar dientes',
                'meta_keywords' => 'juguetes perros, cuerda, dental',
            ],
            [
                'category_id' => $catJuguetesPerros,
                'name' => 'Peluche Chirriador',
                'slug' => 'peluche-chirriador-perros',
                'description' => 'Peluche suave con sonido chirriador',
                'long_description' => 'Adorable peluche de felpa con sonido chirriador interno. Perfecto para perros que aman juguetes suaves. Varios diseños disponibles.',
                'price' => 22000.00,
                'sale_price' => 18000.00,
                'sku' => 'TOY-DOG-003',
                'stock' => 55,
                'weight' => 0.15,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Peluche Chirriador para Perros',
                'meta_description' => 'Peluche suave con sonido chirriador',
                'meta_keywords' => 'juguetes perros, peluche, chirriador',
            ],

            // COLLARES Y CORREAS PARA PERROS (3 productos)
            [
                'category_id' => $catCollaresCorreasPerros,
                'name' => 'Collar Ajustable de Nylon',
                'slug' => 'collar-ajustable-nylon-perros',
                'description' => 'Collar ajustable resistente de nylon',
                'long_description' => 'Collar ajustable de nylon resistente con hebilla de seguridad. Reflectante para mayor visibilidad nocturna. Disponible en varios colores y tallas.',
                'price' => 15000.00,
                'sale_price' => null,
                'sku' => 'COL-DOG-001',
                'stock' => 90,
                'weight' => 0.10,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Collar Ajustable de Nylon',
                'meta_description' => 'Collar de nylon resistente y reflectante',
                'meta_keywords' => 'collar perros, nylon, reflectante',
            ],
            [
                'category_id' => $catCollaresCorreasPerros,
                'name' => 'Correa Retráctil 5 Metros',
                'slug' => 'correa-retractil-5-metros-perros',
                'description' => 'Correa retráctil automática de 5 metros',
                'long_description' => 'Correa retráctil con sistema de freno automático. Máximo 25kg. Mango ergonómico antideslizante. Perfecta para paseos.',
                'price' => 45000.00,
                'sale_price' => 38000.00,
                'sku' => 'LEA-DOG-001',
                'stock' => 35,
                'weight' => 0.35,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Correa Retráctil 5 Metros',
                'meta_description' => 'Correa retráctil automática de 5 metros',
                'meta_keywords' => 'correa, retráctil, perros',
            ],
            [
                'category_id' => $catCollaresCorreasPerros,
                'name' => 'Arnés Acolchado Anti-tirones',
                'slug' => 'arnes-acolchado-anti-tirones',
                'description' => 'Arnés ergonómico que evita tirones',
                'long_description' => 'Arnés acolchado con diseño anti-tirones. Distribuye la presión uniformemente. Ideal para paseos cómodos y seguros.',
                'price' => 35000.00,
                'sale_price' => 30000.00,
                'sku' => 'COL-DOG-002',
                'stock' => 45,
                'weight' => 0.25,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Arnés Acolchado Anti-tirones',
                'meta_description' => 'Arnés ergonómico para paseos seguros',
                'meta_keywords' => 'arnes perros, anti-tirones, acolchado',
            ],

            // CAMAS PARA PERROS (2 productos)
            [
                'category_id' => $catCamasPerros,
                'name' => 'Cama Acolchada para Perros',
                'slug' => 'cama-acolchada-perros',
                'description' => 'Cama cómoda y acolchada para perros',
                'long_description' => 'Cama suave con relleno acolchado de alta densidad. Funda removible y lavable. Tamaño mediano. Perfecta para el descanso de tu mascota.',
                'price' => 75000.00,
                'sale_price' => 65000.00,
                'sku' => 'BED-DOG-001',
                'stock' => 25,
                'weight' => 1.50,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Cama Acolchada para Perros',
                'meta_description' => 'Cama cómoda y acolchada con funda removible',
                'meta_keywords' => 'cama perros, acolchada, lavable',
            ],
            [
                'category_id' => $catCamasPerros,
                'name' => 'Cama Ortopédica Memory Foam',
                'slug' => 'cama-ortopedica-memory-foam-perros',
                'description' => 'Cama ortopédica con espuma viscoelástica',
                'long_description' => 'Cama premium con espuma memory foam que se adapta al cuerpo de tu perro. Ideal para perros senior o con problemas articulares. Funda lavable.',
                'price' => 120000.00,
                'sale_price' => 105000.00,
                'sku' => 'BED-DOG-002',
                'stock' => 15,
                'weight' => 2.00,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Cama Ortopédica Memory Foam',
                'meta_description' => 'Cama ortopédica con espuma viscoelástica',
                'meta_keywords' => 'cama ortopedica, memory foam, perros senior',
            ],

            // COMEDEROS PARA PERROS (2 productos)
            [
                'category_id' => $catComederosPerros,
                'name' => 'Comedero Doble de Acero Inoxidable',
                'slug' => 'comedero-doble-acero-inoxidable-perros',
                'description' => 'Set de comedero y bebedero de acero',
                'long_description' => 'Set de 2 platos de acero inoxidable con base antideslizante. Fácil de limpiar y resistente. Capacidad 500ml cada uno.',
                'price' => 35000.00,
                'sale_price' => 30000.00,
                'sku' => 'BOWL-DOG-001',
                'stock' => 40,
                'weight' => 0.80,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Comedero Doble de Acero Inoxidable',
                'meta_description' => 'Set de comedero y bebedero de acero inoxidable',
                'meta_keywords' => 'comedero, bebedero, acero',
            ],
            [
                'category_id' => $catComederosPerros,
                'name' => 'Comedero Elevado Ajustable',
                'slug' => 'comedero-elevado-ajustable-perros',
                'description' => 'Comedero elevado con altura ajustable',
                'long_description' => 'Comedero elevado con 3 alturas ajustables. Reduce estrés en cuello y articulaciones. Incluye 2 tazones de acero inoxidable.',
                'price' => 65000.00,
                'sale_price' => null,
                'sku' => 'BOWL-DOG-002',
                'stock' => 20,
                'weight' => 1.20,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Comedero Elevado Ajustable',
                'meta_description' => 'Comedero elevado ergonómico con altura ajustable',
                'meta_keywords' => 'comedero elevado, ajustable, ergonomico',
            ],

            // TRANSPORTADORAS PARA PERROS (2 productos)
            [
                'category_id' => $catTransportadorasPerros,
                'name' => 'Transportadora Rígida Mediana',
                'slug' => 'transportadora-rigida-mediana-perros',
                'description' => 'Transportadora rígida para perros medianos',
                'long_description' => 'Transportadora de plástico resistente con puerta de metal y ventilación. Tamaño mediano. Aprobada para viajes en avión.',
                'price' => 95000.00,
                'sale_price' => null,
                'sku' => 'CARR-DOG-001',
                'stock' => 20,
                'weight' => 2.50,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Transportadora Rígida Mediana',
                'meta_description' => 'Transportadora resistente para perros medianos',
                'meta_keywords' => 'transportadora, viaje, perros',
            ],
            [
                'category_id' => $catTransportadorasPerros,
                'name' => 'Mochila Transportadora Pequeña',
                'slug' => 'mochila-transportadora-pequena-perros',
                'description' => 'Mochila para transportar perros pequeños',
                'long_description' => 'Mochila transportadora con ventanas de malla. Perfecta para perros pequeños y cachorros. Correas acolchadas para mayor comodidad.',
                'price' => 85000.00,
                'sale_price' => 75000.00,
                'sku' => 'CARR-DOG-002',
                'stock' => 30,
                'weight' => 1.00,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Mochila Transportadora Pequeña',
                'meta_description' => 'Mochila para transportar perros pequeños',
                'meta_keywords' => 'mochila, transportadora, perros pequeños',
            ],

            // SALUD E HIGIENE PARA PERROS (2 productos)
            [
                'category_id' => $catSaludHigienePerros,
                'name' => 'Shampoo Antipulgas Natural',
                'slug' => 'shampoo-antipulgas-natural-perros',
                'description' => 'Shampoo con ingredientes naturales antipulgas',
                'long_description' => 'Shampoo formulado con aceites esenciales naturales que repelen pulgas y garrapatas. Limpia y perfuma el pelaje. 500ml.',
                'price' => 28000.00,
                'sale_price' => 25000.00,
                'sku' => 'HEALTH-DOG-001',
                'stock' => 60,
                'weight' => 0.50,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Shampoo Antipulgas Natural',
                'meta_description' => 'Shampoo natural que repele pulgas y garrapatas',
                'meta_keywords' => 'shampoo perros, antipulgas, natural',
            ],
            [
                'category_id' => $catSaludHigienePerros,
                'name' => 'Kit de Cepillado Dental',
                'slug' => 'kit-cepillado-dental-perros',
                'description' => 'Kit completo para higiene dental',
                'long_description' => 'Kit que incluye cepillo de dientes, pasta dental con sabor a carne y dedal de silicona. Mantén los dientes de tu perro limpios y saludables.',
                'price' => 32000.00,
                'sale_price' => null,
                'sku' => 'HEALTH-DOG-002',
                'stock' => 45,
                'weight' => 0.20,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Kit de Cepillado Dental',
                'meta_description' => 'Kit completo para higiene dental canina',
                'meta_keywords' => 'cepillo dental, higiene dental, perros',
            ],
        ];

        $brands = ['Pedigree', 'Royal Canin', 'Pro Plan', 'Hills', 'Eukanuba', 'Chunky', 'Dog Chow', null];

        foreach ($products as $index => $product) {
            // Agregar brand y low_stock_threshold
            $product['brand'] = $brands[array_rand($brands)];
            $product['low_stock_threshold'] = rand(5, 15);

            $productId = DB::table('products')->insertGetId(array_merge($product, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Agregar imágenes demo para cada producto
            $imageUrls = $this->getProductImages($product['sku']);

            foreach ($imageUrls as $order => $imageUrl) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_url' => $imageUrl,
                    'is_primary' => $order === 0,
                    'order' => $order + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
