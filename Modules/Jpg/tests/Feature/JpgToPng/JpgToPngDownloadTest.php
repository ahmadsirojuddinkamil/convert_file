<?php

namespace Modules\Jpg\tests\Feature\JpgToPng;

use App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class JpgToPngDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_download_result_jpg_to_png_success(): void
    {
        $jpg = Jpg::jpgOwnerFactory()->create();

        $png = Png::create([
            'jpg_uuid' => $jpg->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_example/example.png',
            'name' => 'example.png',
        ]);

        $response = $this->get("/jpg-to-png/$png->uuid/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'image/png');

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully downloads the jpg to png conversion result with png uuid:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_download_result_jpg_to_png_failed_because_not_uuid(): void
    {
        $response = $this->get("/jpg-to-png/uuid/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_download_result_jpg_to_png_failed_because_data_not_found(): void
    {
        $response = $this->get("/jpg-to-png/8266643b-8491-414c-97fa-a4aa5b967079/download");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data png anda tidak ditemukan!', session('error'));
    }
}
