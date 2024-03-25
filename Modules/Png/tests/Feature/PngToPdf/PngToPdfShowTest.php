<?php

namespace Modules\Png\tests\Feature\PngToPdf;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToPdfShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_png_to_pdf_success(): void
    {
        $directory = storage_path('app/public/document_png_to_pdf/');

        $pngName = uniqid() . '_' . 'pngToPdfShow.png';
        $pngPath = $directory . $pngName;
        $imageContent = '';
        file_put_contents($pngPath, $imageContent);

        $pdfName = uniqid() . '_' . 'pngToPdfShow.pdf';
        $pdfPath = $directory . $pdfName;
        file_put_contents($pdfPath, $imageContent);

        $png = Png::create([
            'jpg_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ]);

        Pdf::create([
            'jpg_uuid' => null,
            'png_uuid' => $png->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_png_to_pdf/' . $pdfName,
            'name' => 'jpgToPdfShow.pdf',
            'preview' => 'document_png_to_pdf/' . $pngName,
        ]);

        Storage::delete('public/document_png_to_pdf/' . $pngName);
        Storage::delete('public/document_png_to_pdf/' . $pdfName);

        $response = $this->get("/png-to-pdf/$png->owner");
        $response->assertStatus(200);

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Png To Pdf');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PNG to PDF Converter');

        $response->assertViewHas('pdfFiles');
        $pdfFiles = $response->original->getData()['pdfFiles'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $pdfFiles);
        $this->assertGreaterThan(0, $pdfFiles->count());
    }

    public function test_show_png_to_pdf_failed_because_not_uuid(): void
    {
        $response = $this->get("/png-to-pdf/uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_show_png_to_pdf_failed_because_data_not_found(): void
    {
        $response = $this->get("/png-to-pdf/a4b1d9c8-6f77-41ef-beb9-ed8678106b15");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data pdf anda tidak ditemukan!', session('error'));
    }
}
