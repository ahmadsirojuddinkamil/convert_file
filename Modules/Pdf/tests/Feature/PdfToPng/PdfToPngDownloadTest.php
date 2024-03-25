<?php

namespace Modules\Pdf\tests\Feature\PdfToPng;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PdfToPngDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_result_pdf_to_png_success(): void
    {
        $directory = storage_path('app/public/document_pdf_to_png/');
        $fileName = uniqid() . '_' . 'pdfToPngDownload.jpg';
        ;
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);

        $pdf = Pdf::create([
            'jpg_uuid' => null,
            'png_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
            'preview' => null,
        ]);

        $png = Png::create([
            'jpg_uuid' => null,
            'pdf_uuid' => $pdf->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_pdf_to_png/' . $fileName,
            'name' => $fileName,
        ]);

        $response = $this->get("/pdf-to-png/$png->uuid/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'image/png');

        Storage::delete('public/document_pdf_to_png/' . $fileName);
    }

    public function test_download_result_pdf_to_png_failed_because_not_uuid(): void
    {
        $response = $this->get("/pdf-to-png/uuid/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_download_result_pdf_to_png_failed_because_data_not_found(): void
    {
        $response = $this->get("/pdf-to-png/8266643b-8491-414c-97fa-a4aa5b967079/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data png anda tidak ditemukan!', session('error'));
    }
}
