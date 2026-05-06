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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY

            // Datos
            $table->string('name', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 9)->nullable();
            $table->enum('document_type', ['DNI', 'RUC', 'CE', 'PASSPORT', 'OTHER']);
            $table->string('document_number', 12);

            // Auditoria
            $table->timestamps();

            // Constraint
            $table->unique(['document_type', 'document_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
