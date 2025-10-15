<?php
// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Laptop Gaming',
                'description' => 'Laptop potente para gaming con RTX 4060',
                'price' => 1299.99,
                'stock' => 15,
            ],
            [
                'name' => 'Smartphone Flagship',
                'description' => 'Último modelo con cámara de 108MP',
                'price' => 899.99,
                'stock' => 25,
            ],
            [
                'name' => 'Auriculares Inalámbricos',
                'description' => 'Cancelación de ruido activa',
                'price' => 199.99,
                'stock' => 30,
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Monitor de salud y fitness',
                'price' => 299.99,
                'stock' => 20,
            ],
            [
                'name' => 'Tablet Pro',
                'description' => 'Tablet para trabajo y creatividad',
                'price' => 599.99,
                'stock' => 12,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}