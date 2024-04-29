<?php

namespace Modules\Jpg\tests\Feature\JpgToPdf;

use App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class JpgToPdfReplyTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_reply_jpg_to_pdf_success(): void
    {
        $directory = storage_path('app/public/document_jpg_to_pdf/');

        $jpgName = uniqid() . '_' . 'jpgToPdfReply.jpg';
        $jpgPath = $directory . $jpgName;
        $imageContent = '';
        file_put_contents($jpgPath, $imageContent);

        $pdfName = uniqid() . '_' . 'jpgToPdfReply.pdf';
        $pdfPath = $directory . $pdfName;
        file_put_contents($pdfPath, $imageContent);

        $jpg = Jpg::jpgOwnerFactory()->create();

        Pdf::create([
            'jpg_uuid' => $jpg->uuid,
            'png_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_jpg_to_pdf/' . $pdfName,
            'name' => 'jpgToPdfReply.pdf',
            'preview' => 'document_jpg_to_pdf/' . $jpgName,
        ]);

        $image = UploadedFile::fake()->image('jpgToPdfReply2.jpg');

        $response = $this->post("/jpg-to-pdf/$jpg->owner", [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $this->assertDatabaseHas('pdfs', [
            'name' => 'jpgToPdfReply2.pdf',
        ]);

        Storage::delete('public/document_jpg_to_pdf/' . $jpgName);
        Storage::delete('public/document_jpg_to_pdf/' . $pdfName);

        $pdf = Pdf::where('name', 'jpgToPdfReply2.pdf')->first();
        Storage::delete('public/' . $pdf->file);
        Storage::delete('public/' . $pdf->preview);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File jpg berhasil di convert ke pdf!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully reply convert jpg to pdf:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_reply_jpg_to_pdf_failed_because_form_is_empty(): void
    {
        $response = $this->post('/jpg-to-pdf/fdf0da63-1f27-415f-99a5-f9cc9db968eb', [
            '_token' => csrf_token(),
            'file' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_jpg_to_pdf_failed_because_not_jpg_file(): void
    {
        $image = UploadedFile::fake()->image('jpgToPdfReply.png');

        $response = $this->post('/jpg-to-pdf/fdf0da63-1f27-415f-99a5-f9cc9db968eb', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_jpg_to_pdf_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('jpgToPdfReply.jpg')->size(2048);

        $response = $this->post('/jpg-to-pdf/fdf0da63-1f27-415f-99a5-f9cc9db968eb', [
            '_token' => csrf_token(),
            'file' => $image,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
