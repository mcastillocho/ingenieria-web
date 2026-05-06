<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY

            // Datos
            $table->string('code', 50);
            $table->enum('type_use', ['AUTOMATIC', 'MANUAL']);
            $table->enum('type_discount', ['AMOUNT', 'PERCENTAGE']);
            $table->decimal('amount', 12, 2);
            $table->decimal('minimum_amount', 12, 2);
            $table->decimal('maximum_amount', 12, 2);
            $table->dateTime('expiration_date');
            $table->integer('use_limit');
            $table->enum('type_limit', ['FOR_PRODUCT', 'FOR_SALE', 'UNLIMITED']);

            // Auditoria
            $table->timestamps();

            // Constraint
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
