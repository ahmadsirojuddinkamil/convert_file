<?php

namespace Modules\Pdf\tests\Feature\PdfToPng;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Png\App\Models\Png;

class PdfToPngCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_create_pdf_to_png_success(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToPngCreate.pdf', 100);
        $base64Png = 'data:image/png;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Png,
            'pdfToPngCreate',
        ];

        $response = $this->post('/pdf-to-png', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('pngs', [
            'name' => 'pdfToPngCreate.png',
        ]);

        $png = Png::where('name', 'pdfToPngCreate.png')->first();
        Storage::delete('public/' . $png->file);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File pdf berhasil di convert ke png!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully convert pdf to png:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_create_pdf_to_png_failed_because_form_is_empty(): void
    {
        $response = $this->post('/pdf-to-png', [
            '_token' => csrf_token(),
            'file_pdf' => '',
            'link_pdf' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_pdf_to_png_failed_because_not_pdf_file(): void
    {
        $image = UploadedFile::fake()->image('pdfToPngCreate.png');
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pdfToPngCreate',
        ];

        $response = $this->post('/pdf-to-png', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_pdf_to_png_failed_because_more_than_1_mb(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToPngCreate.pdf', 1100);
        $base64Png = 'data:image/png;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Png,
            'pdfToPngCreate',
        ];

        $response = $this->post('/pdf-to-png', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
