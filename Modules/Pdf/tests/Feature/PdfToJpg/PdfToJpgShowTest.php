<?php

namespace Modules\Pdf\tests\Feature\PdfToJpg;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class PdfToJpgShowTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_show_pdf_to_jpg_success(): void
    {
        $directory = storage_path('app/public/document_pdf_to_jpg/');
        $fileName = uniqid() . '_' . 'pdfToJpgShow.jpg';
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);

        $pdf = Pdf::pdfOwnerFactory()->create();

        Jpg::create([
            'png_uuid' => null,
            'pdf_uuid' => $pdf->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_pdf_to_jpg/' . $fileName,
            'name' => $fileName,
        ]);

        Storage::delete('public/document_pdf_to_jpg/' . $fileName);

        $response = $this->get("/pdf-to-jpg/$pdf->owner");
        $response->assertStatus(200);

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Pdf To Jpg');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PDF to JPG Converter');

        $response->assertViewHas('jpgFiles');
        $jpgFiles = $response->original->getData()['jpgFiles'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $jpgFiles);
        $this->assertGreaterThan(0, $jpgFiles->count());

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully viewed pdf to jpg data with uuid pdf: ';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_show_pdf_to_jpg_failed_because_not_uuid(): void
    {
        $response = $this->get("/pdf-to-jpg/uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_show_pdf_to_jpg_failed_because_data_not_found(): void
    {
        $response = $this->get("/pdf-to-jpg/a4b1d9c8-6f77-41ef-beb9-ed8678106b15");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data jpg anda tidak ditemukan!', session('error'));
    }
}
