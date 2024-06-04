<?php

namespace Modules\Png\tests\Feature\PngToPdf;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToPdfReplyTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_reply_png_to_pdf_success(): void
    {
        $directory = storage_path('app/public/document_png_to_pdf/');

        $pngName = uniqid() . '_' . 'pngToPdfReply.png';
        $pngPath = $directory . $pngName;
        $imageContent = '';
        file_put_contents($pngPath, $imageContent);

        $pdfName = uniqid() . '_' . 'pngToPdfReply.pdf';
        $pdfPath = $directory . $pdfName;
        file_put_contents($pdfPath, $imageContent);

        $png = Png::pngOwnerFactory()->create();

        Pdf::create([
            'jpg_uuid' => null,
            'png_uuid' => $png->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_png_to_pdf/' . $pdfName,
            'name' => 'pngToPdfReply.pdf',
            'preview' => 'document_png_to_pdf/' . $pngName,
        ]);

        $image = UploadedFile::fake()->image('pngToPdfReply2.png');

        $response = $this->post("/png-to-pdf/$png->owner", [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'pngToPdfReply2.pdf',
        ]);

        Storage::delete('public/document_png_to_pdf/' . $pngName);
        Storage::delete('public/document_png_to_pdf/' . $pdfName);

        $pdf = Pdf::where('name', 'pngToPdfReply2.pdf')->first();
        Storage::delete('public/' . $pdf->file);
        Storage::delete('public/' . $pdf->preview);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File png berhasil di convert ke pdf!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully reply convert png to pdf:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_reply_png_to_pdf_failed_because_form_is_empty(): void
    {
        $response = $this->post('/png-to-pdf/fdf0da63-1f27-415f-99a5-f9cc9db968eb', [
            '_token' => csrf_token(),
            'file' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_png_to_pdf_failed_because_not_png_file(): void
    {
        $image = UploadedFile::fake()->image('pngToPdfReply.jpg');

        $response = $this->post('/png-to-pdf/fdf0da63-1f27-415f-99a5-f9cc9db968eb', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_png_to_pdf_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('pngToPdfReply.png')->size(2048);

        $response = $this->post('/png-to-pdf/fdf0da63-1f27-415f-99a5-f9cc9db968eb', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
