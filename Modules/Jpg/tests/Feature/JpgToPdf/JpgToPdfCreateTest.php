<?php

namespace Modules\Jpg\tests\Feature\JpgToPdf;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;

class JpgToPdfCreateTest extends TestCase
{
    use RefreshDatabase;

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
