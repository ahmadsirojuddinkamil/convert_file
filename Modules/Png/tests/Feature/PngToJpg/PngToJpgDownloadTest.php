<?php

namespace Modules\Png\tests\Feature;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToJpgDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_download_result_png_to_jpg_success(): void
    {
        $png = Png::pngOwnerFactory()->create();

        $jpg = Jpg::create([
            'png_uuid' => $png->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_example/example.jpg',
            'name' => 'example.jpg',
        ]);

        $response = $this->get("/png-to-jpg/$jpg->uuid/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'image/jpeg');

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully downloads the png to jpg conversion result with jpg uuid:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_download_result_png_to_jpg_failed_because_not_uuid(): void
    {
        $response = $this->get("/png-to-jpg/uuid/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_download_result_png_to_jpg_failed_because_data_not_found(): void
    {
        $response = $this->get("/png-to-jpg/8266643b-8491-414c-97fa-a4aa5b967079/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data jpg anda tidak ditemukan!', session('error'));
    }
}
