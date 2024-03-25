<?php

namespace Modules\Png\tests\Feature\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Modules\Png\App\Services\PngToJpgService;
use Ramsey\Uuid\Uuid;

class PngToJpgServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_create_png_to_jpg_success(): void
    {
        $image = UploadedFile::fake()->image('serviceCreatePngToJpg.png');
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $pngToJpg = new PngToJpgService();

        $validateData = [
            'file' => $base64Png,
            'name' => 'serviceCreatePngToJpg.png'
        ];

        $uuidOwner = $pngToJpg->convertAndSave($validateData);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'serviceCreatePngToJpg.jpg',
        ]);

        Storage::delete('public/' . Jpg::where('name', 'serviceCreatePngToJpg.jpg')->value('file'));
    }

    public function test_service_reply_png_to_jpg_success(): void
    {
        $image = UploadedFile::fake()->image('serviceReplyPngToJpg.png');
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $pngToJpg = new PngToJpgService();

        $validateData = [
            'file' => $base64Png,
            'name' => 'serviceReplyPngToJpg.png'
        ];

        $png = Png::create([
            'jpg_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $uuidOwner = $pngToJpg->convertAndSave($validateData, $png->owner);

        $this->assertNotEmpty($uuidOwner);
        $this->assertDatabaseHas('jpgs', [
            'name' => 'serviceReplyPngToJpg.jpg',
        ]);

        Storage::delete('public/' . Jpg::where('name', 'serviceReplyPngToJpg.jpg')->value('file'));
    }
}
