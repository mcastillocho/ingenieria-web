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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY

            // Referencias
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');

            // Datos
            $table->string('username', 100);
            $table->string('password', 255);
            $table->string('role', 50);

            // Auditoria
            $table->timestamps();
            $table->softDeletes();

            // Constraint
            $table->unique('username');
            $table->unique('worker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
