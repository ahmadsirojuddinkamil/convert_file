<?php

namespace Modules\Jpg\tests\Feature\JpgToPdf;

use Modules\Utility\App\Services\LoggingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Modules\Pdf\App\Models\Pdf;
use Tests\TestCase;

class JpgToPdfCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_create_jpg_to_pdf_success(): void
    {
        $image = UploadedFile::fake()->image('jpgToPdfCreate.jpg');

        $response = $this->post('/jpg-to-pdf', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'jpgToPdfCreate.pdf',
        ]);

        $pdf = Pdf::where('name', 'jpgToPdfCreate.pdf')->first();
        Storage::delete('public/' . $pdf->file);
        Storage::delete('public/' . $pdf->preview);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File jpg berhasil di convert ke pdf!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully convert jpg to pdf:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_create_jpg_to_pdf_failed_because_form_is_empty(): void
    {
        $response = $this->post('/jpg-to-pdf', [
            '_token' => csrf_token(),
            'file' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_jpg_to_pdf_failed_because_not_jpg_file(): void
    {
        $image = UploadedFile::fake()->image('jpgToPdfCreate.png');

        $response = $this->post('/jpg-to-pdf', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_jpg_to_pdf_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('jpgToPdfCreate.jpg')->size(2048);

        $response = $this->post('/jpg-to-pdf', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
