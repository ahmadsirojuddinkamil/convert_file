<?php

namespace Modules\Jpg\tests\Feature\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Jpg\App\Services\JpgToPngService;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class JpgToPngServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_create_jpg_to_png_success(): void
    {
        $image = UploadedFile::fake()->image('serviceCreateJpgToPng.jpg');
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($image);

        $jpgToPng = new JpgToPngService();

        $validateData = [
            'file' => $base64Jpg,
            'name' => 'serviceCreateJpgToPng.jpg'
        ];

        $uuidOwner = $jpgToPng->convertAndSave($validateData);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pngs', [
            'name' => 'serviceCreateJpgToPng.png',
        ]);

        Storage::delete('public/' . Png::where('name', 'serviceCreateJpgToPng.png')->value('file'));
    }

    public function test_service_reply_jpg_to_png_success(): void
    {
        $image = UploadedFile::fake()->image('serviceReplyJpgToPng.jpg');
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($image);

        $jpgToPng = new JpgToPngService();

        $validateData = [
            'file' => $base64Jpg,
            'name' => 'serviceReplyJpgToPng.jpg'
        ];

        $jpg = Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $uuidOwner = $jpgToPng->convertAndSave($validateData, $jpg->owner);

        $this->assertNotEmpty($uuidOwner);
        $this->assertDatabaseHas('pngs', [
            'name' => 'serviceReplyJpgToPng.png',
        ]);

        Storage::delete('public/' . Png::where('name', 'serviceReplyJpgToPng.png')->value('file'));
    }
}
