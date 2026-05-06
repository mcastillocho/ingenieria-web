<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY

            // Referencias
            $table->foreignId('product_category_id')->constrained();

            // Datos
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('sale_price', 12, 2);
            $table->string('image_path', 255)->nullable();

            // Auditoria
            $table->timestamps();

            // Constraint
            $table->unique(['product_category_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
