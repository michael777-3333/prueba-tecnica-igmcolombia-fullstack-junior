<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si el campo role_id existe, si no existe, crearlo
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable()->after('id')->constrained()->onDelete('set null');
            });
        }

        // Asignar roles si es necesario
      
    }

    public function down(): void
    {
        // No hacer nada en el rollback para evitar problemas
    }
};
