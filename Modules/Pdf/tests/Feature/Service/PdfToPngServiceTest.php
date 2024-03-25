<?php

namespace Modules\Pdf\tests\Feature\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Pdf\App\Services\PdfToPngService;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PdfToPngServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_create_pdf_to_png_success(): void
    {
        $pdfFile = UploadedFile::fake()->create('ServicePdfToPngCreate.pdf', 100);
        $base64Png = 'data:image/png;base64,' . base64_encode($pdfFile);

        $pngToPdf = new PdfToPngService();

        $dummyData = [
            'file_pdf' => $pdfFile,
            'link_pdf' => $base64Png,
            'name' => 'ServicePdfToPngCreate',
        ];

        $uuidOwner = $pngToPdf->convertAndSave($dummyData);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pngs', [
            'name' => 'ServicePdfToPngCreate.png',
        ]);

        Storage::delete('public/' . Png::where('name', 'ServicePdfToPngCreate.png')->value('file'));
    }

    public function test_service_reply_pdf_to_png_success(): void
    {
        $pdfFile = UploadedFile::fake()->create('ServicePdfToPngReply.pdf', 100);
        $base64Png = 'data:image/png;base64,' . base64_encode($pdfFile);

        $pdf = Pdf::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $pdfToPng = new PdfToPngService();

        $dummyData = [
            'file_pdf' => $pdfFile,
            'link_pdf' => $base64Png,
            'name' => 'ServicePdfToPngReply2',
        ];

        $uuidOwner = $pdfToPng->convertAndSave($dummyData, $pdf->owner);

        $this->assertNotEmpty($uuidOwner);

        $this->assertDatabaseHas('pngs', [
            'name' => 'ServicePdfToPngReply2.png',
        ]);

        Storage::delete('public/' . Png::where('name', 'ServicePdfToPngReply2.png')->value('file'));
    }
}
