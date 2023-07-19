<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jpgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('png_id')->nullable();
            $table->uuid('uuid');
            $table->uuid('unique_id');
            $table->string('file');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pngs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jpg_id')->nullable();
            $table->uuid('uuid');
            $table->uuid('unique_id');
            $table->string('file');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('jpgs', function (Blueprint $table) {
            $table->foreign('png_id')->references('id')->on('pngs')->onDelete('cascade');
        });

        Schema::table('pngs', function (Blueprint $table) {
            $table->foreign('jpg_id')->references('id')->on('jpgs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jpgs');
        Schema::dropIfExists('pngs');
    }
};
