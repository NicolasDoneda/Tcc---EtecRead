<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('ano_escolar', ['1', '2', '3'])->nullable()->after('role');
            $table->softDeletes(); // Soft delete pra manter histórico
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ano_escolar');
            $table->dropSoftDeletes();
        });
    }
};