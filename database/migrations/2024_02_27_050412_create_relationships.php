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
        Schema::table('jpgs', function (Blueprint $table) {
            $table->foreign('png_uuid')->references('uuid')->on('pngs')->onDelete('cascade');
            $table->foreign('pdf_uuid')->references('uuid')->on('pdfs')->onDelete('cascade');
        });

        Schema::table('pngs', function (Blueprint $table) {
            $table->foreign('jpg_uuid')->references('uuid')->on('jpgs')->onDelete('cascade');
            $table->foreign('pdf_uuid')->references('uuid')->on('pdfs')->onDelete('cascade');
        });

        Schema::table('pdfs', function (Blueprint $table) {
            $table->foreign('jpg_uuid')->references('uuid')->on('jpgs')->onDelete('cascade');
            $table->foreign('png_uuid')->references('uuid')->on('pngs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
