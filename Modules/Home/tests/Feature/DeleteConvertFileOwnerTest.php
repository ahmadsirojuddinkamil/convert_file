<?php

namespace Modules\Home\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class DeleteConvertFileOwnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_owner_file_after_10_minute_success(): void
    {
        $directory = storage_path('app/public/document_jpg_to_png/');
        $fileName = uniqid() . '_' . 'file.png';
        ;
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);
        $twentyMinutesAgo = Carbon::now()->addMinutes(20);

        $jpg = Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
            'created_at' => $twentyMinutesAgo,
        ]);

        Png::create([
            'jpg_uuid' => $jpg->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_jpg_to_png/' . $fileName,
            'name' => $fileName,
            'created_at' => $twentyMinutesAgo,
        ]);

        $response = $this->delete("/delete-convert/{$jpg->owner}/JPG to PNG Converter");
        $response->assertStatus(200);
    }

    public function test_delete_owner_file_after_10_minute_failed_bacause_not_uuid(): void
    {
        $response = $this->delete("/delete-convert/uuid/JPG to PNG Converter");
        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Data uuid tidak valid'
        ]);
    }

    public function test_delete_owner_file_after_10_minute_failed_bacause_title_not_available(): void
    {
        $response = $this->delete("/delete-convert/eb7f20a9-16f1-49c8-9ed5-a7b3b6379278/converter");
        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Data title tidak valid'
        ]);
    }

    public function test_delete_owner_file_after_10_minute_failed_bacause_data_primary_not_found(): void
    {
        $response = $this->delete("/delete-convert/eb7f20a9-16f1-49c8-9ed5-a7b3b6379278/JPG to PNG Converter");
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Data utama anda tidak ditemukan!'
        ]);
    }

    public function test_delete_owner_file_after_10_minute_failed_bacause_data_relation_not_found(): void
    {
        $twentyMinutesAgo = Carbon::now()->addMinutes(20);

        $jpg = Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
            'created_at' => $twentyMinutesAgo,
        ]);

        $response = $this->delete("/delete-convert/$jpg->owner/JPG to PNG Converter");
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Data child anda tidak ditemukan!'
        ]);
    }
}
