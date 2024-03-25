<?php

namespace Modules\Pdf\tests\Feature\PdfToPng;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PdfToPngShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_pdf_to_png_success(): void
    {
        $directory = storage_path('app/public/document_pdf_to_png/');
        $fileName = uniqid() . '_' . 'pdfToPngShow.png';
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

        Png::create([
            'jpg_uuid' => null,
            'pdf_uuid' => $pdf->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_pdf_to_png/' . $fileName,
            'name' => $fileName,
        ]);

        Storage::delete('public/document_pdf_to_png/' . $fileName);

        $response = $this->get("/pdf-to-png/$pdf->owner");
        $response->assertStatus(200);

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Pdf To Png');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PDF to PNG Converter');

        $response->assertViewHas('pngFiles');
        $pngFiles = $response->original->getData()['pngFiles'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $pngFiles);
        $this->assertGreaterThan(0, $pngFiles->count());
    }

    public function test_show_pdf_to_png_failed_because_not_uuid(): void
    {
        $response = $this->get("/pdf-to-png/uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_show_pdf_to_png_failed_because_data_not_found(): void
    {
        $response = $this->get("/pdf-to-png/a4b1d9c8-6f77-41ef-beb9-ed8678106b15");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data png anda tidak ditemukan!', session('error'));
    }
}