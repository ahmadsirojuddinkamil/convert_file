<?php

namespace Modules\Pdf\tests\Feature\PdfToJpg;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class PdfToJpgDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_result_pdf_to_jpg_success(): void
    {
        $directory = storage_path('app/public/document_pdf_to_jpg/');
        $fileName = uniqid() . '_' . 'pdfToJpgDownload.jpg';
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

        $jpg = Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => $pdf->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_pdf_to_jpg/' . $fileName,
            'name' => $fileName,
        ]);

        $response = $this->get("/pdf-to-jpg/$jpg->uuid/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'image/jpeg');

        Storage::delete('public/document_pdf_to_jpg/' . $fileName);
    }

    public function test_download_result_pdf_to_jpg_failed_because_not_uuid(): void
    {
        $response = $this->get("/pdf-to-jpg/uuid/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_download_result_pdf_to_jpg_failed_because_data_not_found(): void
    {
        $response = $this->get("/pdf-to-jpg/8266643b-8491-414c-97fa-a4aa5b967079/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data jpg anda tidak ditemukan!', session('error'));
    }
}
