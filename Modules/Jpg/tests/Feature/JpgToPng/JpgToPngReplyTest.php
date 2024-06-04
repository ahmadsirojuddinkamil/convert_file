<?php

namespace Modules\Jpg\tests\Feature\JpgToPng;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class JpgToPngReplyTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_reply_jpg_to_png_success(): void
    {
        $directory = storage_path('app/public/document_jpg_to_png/');
        $fileName = uniqid() . '_' . 'jpgToPngReply.png';
        ;
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);

        $jpg = Jpg::jpgOwnerFactory()->create();

        Png::create([
            'jpg_uuid' => $jpg->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_jpg_to_png/' . $fileName,
            'name' => $fileName,
        ]);

        Storage::delete('public/document_jpg_to_png/' . $fileName);

        $image = UploadedFile::fake()->image('jpgToPngReply2.jpg');
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'jpgToPngReply2.jpg',
        ];

        $response = $this->post("/jpg-to-png/$jpg->owner", [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('pngs', [
            'name' => 'jpgToPngReply2.png',
        ]);

        Storage::delete('public/' . Png::where('name', 'jpgToPngReply2.png')->value('file'));

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File jpg berhasil di convert ke png!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully reply convert jpg to png:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_reply_jpg_to_png_failed_because_not_uuid(): void
    {
        $image = UploadedFile::fake()->image('jpgToPngReply3.jpg');
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'jpgToPngReply3.jpg',
        ];

        $response = $this->post("/jpg-to-png/uuid", [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_reply_jpg_to_png_failed_because_form_is_empty(): void
    {
        $response = $this->post("/jpg-to-png/6e4ceb24-9310-4620-9455-e6d3e4e97bfb", [
            '_token' => csrf_token(),
            'file-jpg' => '',
            'file' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_jpg_to_png_failed_because_not_jpg_file(): void
    {
        $image = UploadedFile::fake()->image('jpgToPngReply4.png');
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'jpgToPngReply4.jpg',
        ];

        $response = $this->post('/jpg-to-png/6e4ceb24-9310-4620-9455-e6d3e4e97bfb', [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_jpg_to_png_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('jpgToPngReply4.jpg')->size(2048);
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'jpgToPngReply4.jpg',
        ];

        $response = $this->post('/jpg-to-png', [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
