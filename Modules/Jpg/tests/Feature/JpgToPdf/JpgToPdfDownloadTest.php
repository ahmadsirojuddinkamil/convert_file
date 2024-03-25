<?php

namespace Modules\Jpg\tests\Feature\JpgToPdf;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class JpgToPdfDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_result_jpg_to_pdf_success(): void
    {
        $jpg = Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        $pdf = Pdf::create([
            'jpg_uuid' => $jpg->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_example/example.pdf',
            'name' => 'example.pdf',
            'preview' => 'document_example/example.jpg',
        ]);

        $response = $this->get("/jpg-to-pdf/$pdf->uuid/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_download_result_jpg_to_pdf_failed_because_not_uuid(): void
    {
        $response = $this->get("/jpg-to-pdf/uuid/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_download_result_jpg_to_pdf_failed_because_data_not_found(): void
    {
        $response = $this->get("/jpg-to-pdf/8266643b-8491-414c-97fa-a4aa5b967079/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data pdf anda tidak ditemukan!', session('error'));
    }
}