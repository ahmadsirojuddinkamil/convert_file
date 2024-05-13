<?php

namespace Modules\Png\tests\Feature\PngToJpg;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToJpgReplyTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_reply_png_to_jpg_success(): void
    {
        $directory = storage_path('app/public/document_png_to_jpg/');
        $fileName = uniqid() . '_' . 'pngToJpgReply.jpg';
        ;
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);

        $png = Png::pngOwnerFactory()->create();

        Jpg::create([
            'png_uuid' => $png->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_png_to_jpg/' . $fileName,
            'name' => $fileName,
        ]);

        Storage::delete('public/document_png_to_jpg/' . $fileName);

        $image = UploadedFile::fake()->image('pngToJpgReply2.png');
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgReply2',
        ];

        $response = $this->post("/png-to-jpg/$png->owner", [
            '_token' => csrf_token(),
            'file-png' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'pngToJpgReply2.jpg',
        ]);

        Storage::delete('public/' . Jpg::where('name', 'pngToJpgReply2.jpg')->value('file'));

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File png berhasil di convert ke jpg!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully reply convert png to jpg:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_reply_png_to_jpg_failed_because_not_uuid(): void
    {
        $image = UploadedFile::fake()->image('pngToJpgReply3.png');
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgReply3',
        ];

        $response = $this->post("/png-to-jpg/uuid", [
            '_token' => csrf_token(),
            'file-png' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_reply_png_to_jpg_failed_because_form_is_empty(): void
    {
        $response = $this->post("/png-to-jpg/6e4ceb24-9310-4620-9455-e6d3e4e97bfb", [
            '_token' => csrf_token(),
            'file-png' => '',
            'file' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_png_to_jpg_failed_because_not_png_file(): void
    {
        $image = UploadedFile::fake()->image('pngToJpgReply4.jpg');
        $base64Png = 'data:image/jpeg;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgReply4',
        ];

        $response = $this->post('/png-to-jpg/6e4ceb24-9310-4620-9455-e6d3e4e97bfb', [
            '_token' => csrf_token(),
            'file-png' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_reply_jpg_to_png_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('pngToJpgReply4.png')->size(2048);
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgReply4',
        ];

        $response = $this->post('/png-to-jpg', [
            '_token' => csrf_token(),
            'file-png' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
