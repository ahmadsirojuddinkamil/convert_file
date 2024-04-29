<?php

namespace Modules\Pdf\tests\Feature\PdfToPng;

use App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PdfToPngReplyTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_reply_pdf_to_png_success(): void
    {
        $directory = storage_path('app/public/document_pdf_to_png/');
        $fileName = uniqid() . '_' . 'pdfToPngReply.pdf';
        ;
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);

        $pdf = Pdf::pdfOwnerFactory()->create();

        Png::create([
            'jpg_uuid' => null,
            'pdf_uuid' => $pdf->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_pdf_to_png/' . $fileName,
            'name' => $fileName,
        ]);

        Storage::delete('public/document_pdf_to_png/' . $fileName);

        $pdfFile = UploadedFile::fake()->create('pdfToPngReply2.pdf', 100);
        $base64Jpg = 'data:image/png;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'pdfToPngReply2',
        ];

        $response = $this->post("/pdf-to-png/$pdf->owner", [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('pngs', [
            'name' => 'pdfToPngReply2.png',
        ]);

        Storage::delete('public/' . Png::where('name', 'pdfToPngReply2.png')->value('file'));

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File pdf berhasil di convert ke png!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully reply convert pdf to png:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_reply_pdf_to_png_failed_because_not_uuid(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToPngReply3.pdf', 100);
        $base64Jpg = 'data:image/png;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'pdfToPngReply3',
        ];

        $response = $this->post("/pdf-to-png/uuid", [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_reply_pdf_to_png_failed_because_form_is_empty(): void
    {
        $response = $this->post("/pdf-to-png/6e4ceb24-9310-4620-9455-e6d3e4e97bfb", [
            '_token' => csrf_token(),
            'file-jpg' => '',
            'file' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_pdf_to_png_failed_because_not_png_file(): void
    {
        $image = UploadedFile::fake()->image('pdfToPngReply4.jpg');
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'pdfToPngReply4',
        ];

        $response = $this->post('/pdf-to-png/6e4ceb24-9310-4620-9455-e6d3e4e97bfb', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_pdf_to_png_failed_because_more_than_1_mb(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToPngReply4.pdf', 1100);
        $base64Png = 'data:image/png;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Png,
            'pdfToPngReply4',
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
