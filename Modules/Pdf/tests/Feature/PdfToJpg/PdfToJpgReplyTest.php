<?php

namespace Modules\Pdf\tests\Feature\PdfToJpg;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class PdfToJpgReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_reply_pdf_to_jpg_success(): void
    {
        $directory = storage_path('app/public/document_pdf_to_jpg/');
        $fileName = uniqid() . '_' . 'pdfToJpgReply.pdf';
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

        $pdfFile = UploadedFile::fake()->create('pdfToJpgReply.pdf', 100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'pdfToJpgReply2',
        ];

        $response = $this->post("/pdf-to-jpg/$pdf->owner", [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'pdfToJpgReply2.jpg',
        ]);

        Storage::delete('public/' . Jpg::where('name', 'pdfToJpgReply2.jpg')->value('file'));

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File pdf berhasil di convert ke jpg!', session('success'));
    }

    public function test_reply_pdf_to_jpg_failed_because_not_uuid(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToJpgReply3.pdf', 100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'pdfToJpgReply3.jpg',
        ];

        $response = $this->post("/pdf-to-jpg/uuid", [
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

    public function test_reply_pdf_to_jpg_failed_because_form_is_empty(): void
    {
        $response = $this->post("/pdf-to-jpg/6e4ceb24-9310-4620-9455-e6d3e4e97bfb", [
            '_token' => csrf_token(),
            'file-jpg' => '',
            'file' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_pdf_to_jpg_failed_because_not_jpg_file(): void
    {
        $image = UploadedFile::fake()->image('pdfToJpgReply4.jpg');
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'pdfToJpgReply4',
        ];

        $response = $this->post('/pdf-to-jpg/6e4ceb24-9310-4620-9455-e6d3e4e97bfb', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_pdf_to_jpg_failed_because_more_than_1_mb(): void
    {
        $pdfFile = UploadedFile::fake()->create('pdfToJpgReply4.pdf', 1100);
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($pdfFile);

        $dummyData = [
            $pdfFile,
            $base64Jpg,
            'pdfToJpgReply4',
        ];

        $response = $this->post('/pdf-to-jpg', [
            '_token' => csrf_token(),
            'file_pdf' => $dummyData[0],
            'link_pdf' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
