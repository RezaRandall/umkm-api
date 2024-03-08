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
        Schema::create('umkm_images', function (Blueprint $table) {
            $table->id();
            $table->integer('umkm_id');
            $table->string('umkm_image_file1');
            $table->string('umkm_image_file2');
            $table->string('umkm_image_file3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm_images');
    }
};
