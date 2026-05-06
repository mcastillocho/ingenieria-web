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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY

            // Referencias
            $table->foreignId('client_id')->constrained();
            $table->foreignId('worker_id')->constrained();

            // Datos
            $table->decimal('total_net', 12, 2);
            $table->decimal('total_taxes', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['COMPLETED', 'CANCELLED'])->default('COMPLETED');

            // Auditoria
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
