<?php

namespace Modules\Jpg\tests\Feature\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Jpg\App\Services\JpgToPdfService;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class JpgToPdfServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_create_jpg_to_pdf_success(): void
    {
        $image = UploadedFile::fake()->image('serviceCreateJpgToPdf.jpg');

        $jpgToPdf = new JpgToPdfService();

        $uuidOwner = $jpgToPdf->convertAndSave($image);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'serviceCreateJpgToPdf.pdf',
        ]);

        Storage::delete('public/' . Pdf::where('name', 'serviceCreateJpgToPdf.pdf')->value('file'));
        Storage::delete('public/' . Pdf::where('name', 'serviceCreateJpgToPdf.pdf')->value('preview'));
    }

    public function test_service_reply_jpg_to_pdf_success(): void
    {
        $image = UploadedFile::fake()->image('serviceReply2JpgToPdf.jpg');

        $jpgToPng = new JpgToPdfService();

        $jpg = Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $uuidOwner = $jpgToPng->convertAndSave($image, $jpg->owner);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'serviceReply2JpgToPdf.pdf',
        ]);

        Storage::delete('public/' . Pdf::where('name', 'serviceReply2JpgToPdf.pdf')->value('file'));
        Storage::delete('public/' . Pdf::where('name', 'serviceReply2JpgToPdf.pdf')->value('preview'));
    }
}
