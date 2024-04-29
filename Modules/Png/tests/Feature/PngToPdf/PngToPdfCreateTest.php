<?php

namespace Modules\Png\tests\Feature\PngToPdf;

use App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;

class PngToPdfCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_create_png_to_pdf_success(): void
    {
        $image = UploadedFile::fake()->image('pngToPdfCreate.png');

        $response = $this->post('/png-to-pdf', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'pngToPdfCreate.pdf',
        ]);

        $pdf = Pdf::where('name', 'pngToPdfCreate.pdf')->first();
        Storage::delete('public/' . $pdf->file);
        Storage::delete('public/' . $pdf->preview);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File png berhasil di convert ke pdf!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully convert png to pdf:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_create_png_to_pdf_failed_because_form_is_empty(): void
    {
        $response = $this->post('/png-to-pdf', [
            '_token' => csrf_token(),
            'file' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_png_to_pdf_failed_because_not_png_file(): void
    {
        $image = UploadedFile::fake()->image('pngToPdfCreate.jpg');

        $response = $this->post('/png-to-pdf', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_png_to_pdf_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('pngToPdfCreate.png')->size(2048);

        $response = $this->post('/png-to-pdf', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
