<?php

namespace Database\Seeders;

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
            // Alimentos para Perros
            'FOOD-DOG-001' => [
                'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1581888227599-779811939961?w=500&h=500&fit=crop',
            ],
            'SNACK-DOG-001' => [
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

            // Alimentos para Gatos
            'FOOD-CAT-001' => [
                'https://images.unsplash.com/photo-1589652717521-10c0d092dea9?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1591650787194-d05c1d41b96f?w=500&h=500&fit=crop',
            ],

            // Juguetes para Gatos
            'TOY-CAT-001' => [
                'https://images.unsplash.com/photo-1545249390-6bdfa286032f?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1574158622682-e40e69881006?w=500&h=500&fit=crop',
            ],
            'TOY-CAT-002' => [
                'https://images.unsplash.com/photo-1611003228941-98852ba62227?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1606214174585-fe31582dc6ee?w=500&h=500&fit=crop',
            ],

            // Rascadores
            'SCRA-CAT-001' => [
                'https://images.unsplash.com/photo-1545529468-42764ef8c85f?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?w=500&h=500&fit=crop',
            ],

            // Collares y Correas
            'COL-ACC-001' => [
                'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=500&h=500&fit=crop',
            ],
            'LEA-ACC-001' => [
                'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1522276498395-f4f68f7f8454?w=500&h=500&fit=crop',
            ],

            // Comederos y Bebederos
            'BOWL-ACC-001' => [
                'https://images.unsplash.com/photo-1591135905399-87b390a3a6e8?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1585664811087-47f65abbad64?w=500&h=500&fit=crop',
            ],

            // Transportadoras
            'CARR-ACC-001' => [
                'https://images.unsplash.com/photo-1603003573425-e4227c28e95e?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1519052537078-e6302a4968d4?w=500&h=500&fit=crop',
            ],

            // Camas
            'BED-DOG-001' => [
                'https://images.unsplash.com/photo-1563460716037-460a3ad24ba9?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1585664811087-47f65abbad64?w=500&h=500&fit=crop',
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
        $products = [
            // Alimentos para Perros (category_id: 4)
            [
                'category_id' => 4,
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
                'category_id' => 4,
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

            // Juguetes para Perros (category_id: 5)
            [
                'category_id' => 5,
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
                'category_id' => 5,
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

            // Alimentos para Gatos (category_id: 7)
            [
                'category_id' => 7,
                'name' => 'Alimento Premium para Gatos',
                'slug' => 'alimento-premium-gatos',
                'description' => 'Alimento balanceado premium para gatos adultos',
                'long_description' => 'Nutrición completa para gatos adultos con proteínas de alta calidad. Rico en taurina y omega 3 y 6.',
                'price' => 65000.00,
                'sale_price' => 58000.00,
                'sku' => 'FOOD-CAT-001',
                'stock' => 45,
                'weight' => 2.00,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Alimento Premium para Gatos - Petunia Play',
                'meta_description' => 'Alimento balanceado premium para gatos adultos',
                'meta_keywords' => 'alimento gatos, comida gatos, premium',
            ],

            // Juguetes para Gatos (category_id: 8)
            [
                'category_id' => 8,
                'name' => 'Ratón de Juguete con Catnip',
                'slug' => 'raton-juguete-catnip-gatos',
                'description' => 'Ratón de peluche con hierba gatera',
                'long_description' => 'Adorable ratón de peluche relleno con catnip natural que enloquecerá a tu gato. Material suave y seguro.',
                'price' => 12000.00,
                'sale_price' => null,
                'sku' => 'TOY-CAT-001',
                'stock' => 80,
                'weight' => 0.05,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Ratón de Juguete con Catnip',
                'meta_description' => 'Ratón de peluche con hierba gatera para gatos',
                'meta_keywords' => 'juguetes gatos, catnip, ratón',
            ],
            [
                'category_id' => 8,
                'name' => 'Varita con Plumas',
                'slug' => 'varita-plumas-gatos',
                'description' => 'Varita interactiva con plumas coloridas',
                'long_description' => 'Juguete interactivo con varita extensible y plumas coloridas. Perfecto para estimular el instinto cazador de tu gato.',
                'price' => 22000.00,
                'sale_price' => 18000.00,
                'sku' => 'TOY-CAT-002',
                'stock' => 55,
                'weight' => 0.10,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Varita con Plumas para Gatos',
                'meta_description' => 'Juguete interactivo con plumas para gatos',
                'meta_keywords' => 'juguetes gatos, varita, plumas',
            ],

            // Rascadores (category_id: 9)
            [
                'category_id' => 9,
                'name' => 'Rascador Torre con Plataformas',
                'slug' => 'rascador-torre-plataformas',
                'description' => 'Torre rascadora con múltiples niveles',
                'long_description' => 'Rascador de 120cm de altura con plataformas, cueva y juguetes colgantes. Cubierto de sisal natural.',
                'price' => 180000.00,
                'sale_price' => 150000.00,
                'sku' => 'SCRA-CAT-001',
                'stock' => 15,
                'weight' => 12.00,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Rascador Torre con Plataformas',
                'meta_description' => 'Torre rascadora de 120cm con múltiples niveles',
                'meta_keywords' => 'rascador gatos, torre, plataformas',
            ],

            // Collares y Correas (category_id: 10)
            [
                'category_id' => 10,
                'name' => 'Collar Ajustable con Cascabel',
                'slug' => 'collar-ajustable-cascabel',
                'description' => 'Collar ajustable de nylon con cascabel',
                'long_description' => 'Collar ajustable resistente de nylon con cascabel y hebilla de seguridad. Disponible en varios colores.',
                'price' => 15000.00,
                'sale_price' => null,
                'sku' => 'COL-ACC-001',
                'stock' => 90,
                'weight' => 0.05,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Collar Ajustable con Cascabel',
                'meta_description' => 'Collar de nylon ajustable con cascabel',
                'meta_keywords' => 'collar, cascabel, mascotas',
            ],
            [
                'category_id' => 10,
                'name' => 'Correa Retráctil 5 Metros',
                'slug' => 'correa-retractil-5-metros',
                'description' => 'Correa retráctil automática de 5 metros',
                'long_description' => 'Correa retráctil con sistema de freno automático. Máximo 25kg. Mango ergonómico antideslizante.',
                'price' => 45000.00,
                'sale_price' => 38000.00,
                'sku' => 'LEA-ACC-001',
                'stock' => 35,
                'weight' => 0.35,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Correa Retráctil 5 Metros',
                'meta_description' => 'Correa retráctil automática de 5 metros',
                'meta_keywords' => 'correa, retráctil, perros',
            ],

            // Comederos y Bebederos (category_id: 11)
            [
                'category_id' => 11,
                'name' => 'Comedero Doble de Acero Inoxidable',
                'slug' => 'comedero-doble-acero-inoxidable',
                'description' => 'Set de comedero y bebedero de acero',
                'long_description' => 'Set de 2 platos de acero inoxidable con base antideslizante. Fácil de limpiar y resistente.',
                'price' => 35000.00,
                'sale_price' => 30000.00,
                'sku' => 'BOWL-ACC-001',
                'stock' => 40,
                'weight' => 0.80,
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Comedero Doble de Acero Inoxidable',
                'meta_description' => 'Set de comedero y bebedero de acero inoxidable',
                'meta_keywords' => 'comedero, bebedero, acero',
            ],

            // Transportadoras (category_id: 12)
            [
                'category_id' => 12,
                'name' => 'Transportadora Rígida Mediana',
                'slug' => 'transportadora-rigida-mediana',
                'description' => 'Transportadora rígida para mascotas medianas',
                'long_description' => 'Transportadora de plástico resistente con puerta de metal y ventilación. Tamaño mediano. Aprobada para viajes.',
                'price' => 95000.00,
                'sale_price' => null,
                'sku' => 'CARR-ACC-001',
                'stock' => 20,
                'weight' => 2.50,
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Transportadora Rígida Mediana',
                'meta_description' => 'Transportadora resistente para mascotas medianas',
                'meta_keywords' => 'transportadora, viaje, mascotas',
            ],

            // Camas (category_id: 6)
            [
                'category_id' => 6,
                'name' => 'Cama Acolchada para Perros',
                'slug' => 'cama-acolchada-perros',
                'description' => 'Cama cómoda y acolchada para perros',
                'long_description' => 'Cama suave con relleno acolchado de alta densidad. Funda removible y lavable. Tamaño mediano.',
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
        ];

        $brands = ['Pedigree', 'Royal Canin', 'Pro Plan', 'Hills', 'Eukanuba', 'Chunky', 'Dog Chow', 'Cat Chow', 'Whiskas', 'Felix', null];

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
