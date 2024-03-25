<?php

namespace Modules\Pdf\tests\Feature\PdfToJpg;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;

class PdfToJpgCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_pdf_to_jpg_success(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToJpgCreate.pdf', 100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'pdfToJpgCreate',
        ];

        $response = $this->post('/pdf-to-jpg', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'pdfToJpgCreate.jpg',
        ]);

        $jpg = Jpg::where('name', 'pdfToJpgCreate.jpg')->first();
        Storage::delete('public/' . $jpg->file);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File pdf berhasil di convert ke jpg!', session('success'));
    }

    public function test_create_pdf_to_jpg_failed_because_form_is_empty(): void
    {
        $response = $this->post('/pdf-to-jpg', [
            '_token' => csrf_token(),
            'file_pdf' => '',
            'link_pdf' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_pdf_to_jpg_failed_because_not_pdf_file(): void
    {
        $image = UploadedFile::fake()->image('home.jpg');
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'home',
        ];

        $response = $this->post('/pdf-to-jpg', [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_pdf_to_jpg_failed_because_more_than_1_mb(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToJpgCreate.pdf', 1100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'home',
        ];

        $response = $this->post('/pdf-to-jpg', [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
