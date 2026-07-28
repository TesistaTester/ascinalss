<?php
// database/migrations/2026_07_24_000001_agregar_imagen_a_filiales.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('filiales', function (Blueprint $table) {
            $table->string('fil_imagen')->nullable()->after('fil_telefono');
        });
    }

    public function down(): void
    {
        Schema::table('filiales', function (Blueprint $table) {
            $table->dropColumn('fil_imagen');
        });
    }
};