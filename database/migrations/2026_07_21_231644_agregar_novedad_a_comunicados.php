<?php
// database/migrations/2026_07_21_000001_agregar_novedad_a_comunicados.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('comunicados', 'com_video_url')) {
            Schema::table('comunicados', function (Blueprint $table) {
                $table->string('com_video_url')->nullable()->after('com_pdf_archivo');
                $table->string('com_pptx_archivo')->nullable()->after('com_video_url');
            });
        }

        DB::statement('ALTER TABLE comunicados DROP CONSTRAINT IF EXISTS comunicados_com_tipo_check');
        DB::statement("ALTER TABLE comunicados ADD CONSTRAINT comunicados_com_tipo_check CHECK (com_tipo IN ('normal', 'modal', 'destacado', 'novedad'))");
    }

    public function down(): void
    {
        Schema::table('comunicados', function (Blueprint $table) {
            $table->dropColumn(['com_video_url', 'com_pptx_archivo']);
        });

        DB::statement('ALTER TABLE comunicados DROP CONSTRAINT IF EXISTS comunicados_com_tipo_check');
        DB::statement("ALTER TABLE comunicados ADD CONSTRAINT comunicados_com_tipo_check CHECK (com_tipo IN ('normal', 'modal', 'destacado'))");
    }
};