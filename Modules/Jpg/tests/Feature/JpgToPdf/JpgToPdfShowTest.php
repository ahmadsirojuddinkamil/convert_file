<?php

namespace Modules\Jpg\tests\Feature\JpgToPdf;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class JpgToPdfShowTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_show_jpg_to_pdf_success(): void
    {
        $directory = storage_path('app/public/document_jpg_to_pdf/');

        $jpgName = uniqid() . '_' . 'jpgToPdfShow.jpg';
        $jpgPath = $directory . $jpgName;
        $imageContent = '';
        file_put_contents($jpgPath, $imageContent);

        $pdfName = uniqid() . '_' . 'jpgToPdfShow.pdf';
        $pdfPath = $directory . $pdfName;
        file_put_contents($pdfPath, $imageContent);

        $jpg = Jpg::jpgOwnerFactory()->create();

        Pdf::create([
            'jpg_uuid' => $jpg->uuid,
            'png_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_jpg_to_pdf/' . $pdfName,
            'name' => 'jpgToPdfShow.pdf',
            'preview' => 'document_jpg_to_pdf/' . $jpgName,
        ]);

        Storage::delete('public/document_jpg_to_pdf/' . $jpgName);
        Storage::delete('public/document_jpg_to_pdf/' . $pdfName);

        $response = $this->get("/jpg-to-pdf/$jpg->owner");
        $response->assertStatus(200);

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Jpg To Pdf');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('JPG to PDF Converter');

        $response->assertViewHas('pdfFiles');
        $pdfFiles = $response->original->getData()['pdfFiles'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $pdfFiles);
        $this->assertGreaterThan(0, $pdfFiles->count());

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully viewed jpg to pdf data with uuid jpg: ';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_show_jpg_to_pdf_failed_because_not_uuid(): void
    {
        $response = $this->get("/jpg-to-pdf/uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_show_jpg_to_pdf_failed_because_data_not_found(): void
    {
        $response = $this->get("/jpg-to-pdf/a4b1d9c8-6f77-41ef-beb9-ed8678106b15");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data pdf anda tidak ditemukan!', session('error'));
    }
}
