<?php

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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 10);
            $table->string('nama');
            $table->string('no_hp', 20)->nullable();
            $table->string('layanan', 50)->default('umum');
            $table->enum('status', ['waiting', 'called', 'missed', 'done'])->default('waiting');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('layanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
