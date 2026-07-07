<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Categorías de producto ──────────────────────────────────────
        $categories = [
            ['name' => 'Herramientas Eléctricas'],
            ['name' => 'Herramientas Manuales'],
            ['name' => 'Fijaciones y Tornillería'],
            ['name' => 'Pinturas y Acabados'],
            ['name' => 'Plomería'],
        ];

        foreach ($categories as $cat) {
            DB::table('product_categories')->insertOrIgnore(array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $catIds = DB::table('product_categories')->pluck('id', 'name');

        // ── 2. Productos ───────────────────────────────────────────────────
        $products = [
            // Herramientas Eléctricas
            [
                'product_category_id' => $catIds['Herramientas Eléctricas'],
                'name' => 'Taladro Percutor 750W',
                'description' => 'Taladro percutor de 750W con mandril de 13mm, 2 velocidades y función martillo.',
                'sale_price' => 189.90,
                'image_path' => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Herramientas Eléctricas'],
                'name' => 'Amoladora Angular 4.5"',
                'description' => 'Amoladora angular de 900W con disco de 115mm y protección de seguridad.',
                'sale_price' => 129.50,
                'image_path' => 'https://images.unsplash.com/photo-1572981779307-38b8cabb2407?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Herramientas Eléctricas'],
                'name' => 'Sierra Circular 1200W',
                'description' => 'Sierra circular de 1200W con profundidad de corte de 65mm y guía de corte.',
                'sale_price' => 249.00,
                'image_path' => 'https://ventasyofertas.com.pe/wp-content/uploads/2023/10/JDCW1512001-1.jpg'
            ],
            [
                'product_category_id' => $catIds['Herramientas Eléctricas'],
                'name' => 'Lijadora Orbital 300W',
                'description' => 'Lijadora orbital de 300W con plato de 125mm y bolsa de polvo.',
                'sale_price' => 89.90,
                'image_path' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=400&q=80'
            ],
            // Herramientas Manuales
            [
                'product_category_id' => $catIds['Herramientas Manuales'],
                'name' => 'Juego de Desarmadores x12',
                'description' => 'Set de 12 desarmadores con mango ergonómico: 6 planos y 6 Phillips.',
                'sale_price' => 45.00,
                'image_path' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Herramientas Manuales'],
                'name' => 'Martillo Carpintero 20oz',
                'description' => 'Martillo de carpintero con cabeza de acero forjado y mango de fibra de vidrio.',
                'sale_price' => 38.50,
                'image_path' => 'https://images.unsplash.com/photo-1586864387967-d02ef85d93e8?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Herramientas Manuales'],
                'name' => 'Llave Francesa 12"',
                'description' => 'Llave francesa de 12 pulgadas con apertura ajustable hasta 35mm.',
                'sale_price' => 29.90,
                'image_path' => 'https://images.unsplash.com/photo-1608613304899-ea8098577e38?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Herramientas Manuales'],
                'name' => 'Nivel de Burbuja 60cm',
                'description' => 'Nivel de aluminio de 60cm con 3 burbujas y precisión de 0.5mm/m.',
                'sale_price' => 35.00,
                'image_path' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=400&q=80'
            ],
            // Fijaciones y Tornillería
            [
                'product_category_id' => $catIds['Fijaciones y Tornillería'],
                'name' => 'Tornillos Autoperforantes x100',
                'description' => 'Caja de 100 tornillos autoperforantes punta fina 4x25mm zincados.',
                'sale_price' => 12.50,
                'image_path' => 'https://images.unsplash.com/photo-1590233649079-c709e3940176?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Fijaciones y Tornillería'],
                'name' => 'Tacos Fischer S10 x50',
                'description' => 'Caja de 50 tacos de expansión Fischer S10 para paredes de concreto.',
                'sale_price' => 18.00,
                'image_path' => 'https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?auto=format&fit=crop&w=400&q=80'
            ],
            // Pinturas y Acabados
            [
                'product_category_id' => $catIds['Pinturas y Acabados'],
                'name' => 'Pintura Látex Blanco 4L',
                'description' => 'Pintura látex lavable interior/exterior, rendimiento 10m²/L, secado 2h.',
                'sale_price' => 65.00,
                'image_path' => 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Pinturas y Acabados'],
                'name' => 'Sellador Acrílico 1L',
                'description' => 'Sellador acrílico para interiores, secado rápido y de fácil lijado.',
                'sale_price' => 28.00,
                'image_path' => 'https://images.unsplash.com/photo-1621905251918-48416bd8575a?auto=format&fit=crop&w=400&q=80'
            ],
            // Plomería
            [
                'product_category_id' => $catIds['Plomería'],
                'name' => 'Tubo PVC 1/2" x 3m',
                'description' => 'Tubo PVC presión clase 10 para instalaciones de agua fría.',
                'sale_price' => 8.50,
                'image_path' => 'https://images.unsplash.com/photo-1604147706283-d7119b5b822c?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'product_category_id' => $catIds['Plomería'],
                'name' => 'Codo PVC 1/2" x10',
                'description' => 'Bolsa de 10 codos PVC 90° para instalaciones de agua.',
                'sale_price' => 5.00,
                'image_path' => 'https://images.unsplash.com/photo-1581092334860-93c6f809930f?auto=format&fit=crop&w=400&q=80'
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                ['name' => $product['name']],
                array_merge($product, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $productIds = DB::table('products')->pluck('id', 'name');

        // ── 3. Proveedores ─────────────────────────────────────────────────
        $suppliers = [
            ['name' => 'Distribuidora Herramax SAC',    'email' => 'ventas@herramax.pe',      'phone' => '994123456', 'document_type' => 'RUC', 'document_number' => '20501234567'],
            ['name' => 'Ferretería Industrial Norte',   'email' => 'pedidos@finorte.pe',      'phone' => '987654321', 'document_type' => 'RUC', 'document_number' => '20509876543'],
            ['name' => 'Importaciones TecnoFerr EIRL',  'email' => 'contacto@tecnoferr.pe',   'phone' => '965432100', 'document_type' => 'RUC', 'document_number' => '20112345678'],
            ['name' => 'Pinturas y Materiales Lima SA', 'email' => 'ventas@pmlima.pe',        'phone' => '978901234', 'document_type' => 'RUC', 'document_number' => '20198765432'],
            ['name' => 'Carlos Mendoza Quispe',         'email' => 'cmendoza@gmail.com',      'phone' => '956789012', 'document_type' => 'DNI', 'document_number' => '43210987'],
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->insertOrIgnore(array_merge($supplier, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $supplierIds = DB::table('suppliers')->pluck('id', 'name');

        // ── 4. Lotes (Batches) ─────────────────────────────────────────────
        $batches = [
            // Taladro Percutor — stock alto (ok)
            [
                'product_id'     => $productIds['Taladro Percutor 750W'],
                'supplier_id'    => $supplierIds['Distribuidora Herramax SAC'],
                'initial_stock'  => 50,
                'current_stock'  => 38,
                'purchase_price' => 145.00,
            ],
            // Amoladora — stock medio (low)
            [
                'product_id'     => $productIds["Amoladora Angular 4.5\""],
                'supplier_id'    => $supplierIds['Distribuidora Herramax SAC'],
                'initial_stock'  => 30,
                'current_stock'  => 7,
                'purchase_price' => 95.00,
            ],
            // Sierra Circular — stock ok
            [
                'product_id'     => $productIds['Sierra Circular 1200W'],
                'supplier_id'    => $supplierIds['Ferretería Industrial Norte'],
                'initial_stock'  => 20,
                'current_stock'  => 15,
                'purchase_price' => 190.00,
            ],
            // Lijadora — sin stock (out)
            [
                'product_id'     => $productIds['Lijadora Orbital 300W'],
                'supplier_id'    => $supplierIds['Ferretería Industrial Norte'],
                'initial_stock'  => 25,
                'current_stock'  => 0,
                'purchase_price' => 68.00,
            ],
            // Desarmadores — stock ok
            [
                'product_id'     => $productIds['Juego de Desarmadores x12'],
                'supplier_id'    => $supplierIds['Importaciones TecnoFerr EIRL'],
                'initial_stock'  => 80,
                'current_stock'  => 54,
                'purchase_price' => 30.00,
            ],
            // Martillo — stock low
            [
                'product_id'     => $productIds['Martillo Carpintero 20oz'],
                'supplier_id'    => $supplierIds['Importaciones TecnoFerr EIRL'],
                'initial_stock'  => 40,
                'current_stock'  => 5,
                'purchase_price' => 28.00,
            ],
            // Llave Francesa — stock ok
            [
                'product_id'     => $productIds["Llave Francesa 12\""],
                'supplier_id'    => $supplierIds['Carlos Mendoza Quispe'],
                'initial_stock'  => 35,
                'current_stock'  => 22,
                'purchase_price' => 20.00,
            ],
            // Nivel — stock ok
            [
                'product_id'     => $productIds['Nivel de Burbuja 60cm'],
                'supplier_id'    => $supplierIds['Carlos Mendoza Quispe'],
                'initial_stock'  => 30,
                'current_stock'  => 28,
                'purchase_price' => 24.00,
            ],
            // Tornillos — stock ok
            [
                'product_id'     => $productIds['Tornillos Autoperforantes x100'],
                'supplier_id'    => $supplierIds['Ferretería Industrial Norte'],
                'initial_stock'  => 200,
                'current_stock'  => 143,
                'purchase_price' => 8.50,
            ],
            // Tacos — stock low
            [
                'product_id'     => $productIds['Tacos Fischer S10 x50'],
                'supplier_id'    => $supplierIds['Ferretería Industrial Norte'],
                'initial_stock'  => 100,
                'current_stock'  => 9,
                'purchase_price' => 12.00,
            ],
            // Pintura — stock ok
            [
                'product_id'     => $productIds['Pintura Látex Blanco 4L'],
                'supplier_id'    => $supplierIds['Pinturas y Materiales Lima SA'],
                'initial_stock'  => 60,
                'current_stock'  => 41,
                'purchase_price' => 48.00,
            ],
            // Sellador — out
            [
                'product_id'     => $productIds['Sellador Acrílico 1L'],
                'supplier_id'    => $supplierIds['Pinturas y Materiales Lima SA'],
                'initial_stock'  => 50,
                'current_stock'  => 0,
                'purchase_price' => 19.00,
            ],
            // Tubo PVC — stock ok
            [
                'product_id'     => $productIds["Tubo PVC 1/2\" x 3m"],
                'supplier_id'    => $supplierIds['Importaciones TecnoFerr EIRL'],
                'initial_stock'  => 150,
                'current_stock'  => 112,
                'purchase_price' => 5.50,
            ],
            // Codo PVC — stock ok
            [
                'product_id'     => $productIds["Codo PVC 1/2\" x10"],
                'supplier_id'    => $supplierIds['Importaciones TecnoFerr EIRL'],
                'initial_stock'  => 200,
                'current_stock'  => 178,
                'purchase_price' => 3.00,
            ],
        ];

        foreach ($batches as $batch) {
            DB::table('batches')->insertOrIgnore(array_merge($batch, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── 5. Workers (para login) ────────────────────────────────────────
        $workers = [
            ['name' => 'Admin',    'lastname' => 'Abad',     'document_type' => 'DNI', 'document_number' => '00000001', 'email' => 'admin@abad.local',    'phone' => '900000001'],
            ['name' => 'Logistic', 'lastname' => 'User',     'document_type' => 'DNI', 'document_number' => '00000002', 'email' => 'logistic@abad.local', 'phone' => '900000002'],
            ['name' => 'Ventas',   'lastname' => 'Demo',     'document_type' => 'DNI', 'document_number' => '00000003', 'email' => 'ventas@abad.local',   'phone' => '900000003'],
        ];

        foreach ($workers as $worker) {
            DB::table('workers')->insertOrIgnore(array_merge($worker, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $workerIds = DB::table('workers')->pluck('id', 'email');

        // ── 6. Credenciales ────────────────────────────────────────────────
        $credentials = [
            ['worker_id' => $workerIds['admin@abad.local'],    'username' => 'admin',    'password' => Hash::make('admin123'),    'role' => 'admin'],
            ['worker_id' => $workerIds['logistic@abad.local'], 'username' => 'logistic', 'password' => Hash::make('logistic123'), 'role' => 'logistica'],
            ['worker_id' => $workerIds['ventas@abad.local'],   'username' => 'ventas',   'password' => Hash::make('ventas123'),   'role' => 'ventas'],
        ];

        foreach ($credentials as $cred) {
            DB::table('credentials')->insertOrIgnore(array_merge($cred, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── 7. Descuentos ──────────────────────────────────────────────────
        $discounts = [
            [
                'code' => 'AHORRO10',
                'type_use' => 'MANUAL',
                'type_discount' => 'PERCENTAGE',
                'amount' => 10.00,
                'minimum_amount' => 50.00,
                'maximum_amount' => 20.00,
                'expiration_date' => now()->addDays(30),
                'use_limit' => 100,
                'type_limit' => 'FOR_SALE',
            ],
            [
                'code' => 'REGALO15',
                'type_use' => 'MANUAL',
                'type_discount' => 'AMOUNT',
                'amount' => 15.00,
                'minimum_amount' => 80.00,
                'maximum_amount' => 15.00,
                'expiration_date' => now()->addDays(30),
                'use_limit' => 50,
                'type_limit' => 'FOR_SALE',
            ]
        ];

        foreach ($discounts as $discount) {
            DB::table('discounts')->updateOrInsert(
                ['code' => $discount['code']],
                array_merge($discount, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
