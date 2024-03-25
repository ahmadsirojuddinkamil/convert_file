<?php

namespace Modules\Pdf\tests\Feature\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Modules\Pdf\App\Services\PdfToJpgService;
use Ramsey\Uuid\Uuid;

class PdfToJpgServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_create_pdf_to_jpg_success(): void
    {
        $pdfFile = UploadedFile::fake()->create('ServicePdfToJpgCreate.pdf', 100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $jpgToPdf = new PdfToJpgService();

        $dummyData = [
            'file_pdf' => $pdfFile,
            'link_pdf' => $base64Jpg,
            'name' => 'ServicePdfToJpgCreate',
        ];

        $uuidOwner = $jpgToPdf->convertAndSave($dummyData);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'ServicePdfToJpgCreate.jpg',
        ]);

        Storage::delete('public/' . Jpg::where('name', 'ServicePdfToJpgCreate.jpg')->value('file'));
    }

    public function test_service_reply_pdf_to_jpg_success(): void
    {
        $pdfFile = UploadedFile::fake()->create('ServicePdfToJpgReply.pdf', 100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $pdf = Pdf::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $jpgToPng = new PdfToJpgService();

        $dummyData = [
            'file_pdf' => $pdfFile,
            'link_pdf' => $base64Jpg,
            'name' => 'ServicePdfToJpgReply',
        ];

        $uuidOwner = $jpgToPng->convertAndSave($dummyData, $pdf->owner);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'ServicePdfToJpgReply.jpg',
        ]);

        Storage::delete('public/' . Jpg::where('name', 'ServicePdfToJpgReply.jpg')->value('file'));
    }
}
