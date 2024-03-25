<?php

namespace Modules\Png\tests\Feature\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Modules\Png\App\Services\PngToPdfService;
use Ramsey\Uuid\Uuid;

class PngToPdfServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_create_png_to_pdf_success(): void
    {
        $image = UploadedFile::fake()->image('serviceCreatePngToPdf.png');

        $pngToPdf = new PngToPdfService();

        $uuidOwner = $pngToPdf->convertAndSave($image);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'serviceCreatePngToPdf.pdf',
        ]);

        Storage::delete('public/' . Pdf::where('name', 'serviceCreatePngToPdf.pdf')->value('file'));
        Storage::delete('public/' . Pdf::where('name', 'serviceCreatePngToPdf.pdf')->value('preview'));
    }

    public function test_service_reply_png_to_pdf_success(): void
    {
        $image = UploadedFile::fake()->image('serviceReply2PngToPdf.png');

        $pngToPdf = new PngToPdfService();

        $png = Png::create([
            'jpg_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $uuidOwner = $pngToPdf->convertAndSave($image, $png->owner);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'serviceReply2PngToPdf.pdf',
        ]);

        Storage::delete('public/' . Pdf::where('name', 'serviceReply2PngToPdf.pdf')->value('file'));
        Storage::delete('public/' . Pdf::where('name', 'serviceReply2PngToPdf.pdf')->value('preview'));
    }
}
