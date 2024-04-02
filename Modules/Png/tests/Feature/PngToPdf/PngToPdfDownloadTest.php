<?php

namespace Modules\Png\tests\Feature\PngToPdf;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToPdfDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_result_png_to_pdf_success(): void
    {
        $png = Png::pngOwnerFactory()->create();

        $pdf = Pdf::create([
            'jpg_uuid' => null,
            'png_uuid' => $png->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_example/example.pdf',
            'name' => 'example.pdf',
            'preview' => 'document_example/example.png',
        ]);

        $response = $this->get("/png-to-pdf/$pdf->uuid/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_download_result_png_to_pdf_failed_because_not_uuid(): void
    {
        $response = $this->get("/png-to-pdf/uuid/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_download_result_png_to_pdf_failed_because_data_not_found(): void
    {
        $response = $this->get("/png-to-pdf/8266643b-8491-414c-97fa-a4aa5b967079/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data pdf anda tidak ditemukan!', session('error'));
    }
}
