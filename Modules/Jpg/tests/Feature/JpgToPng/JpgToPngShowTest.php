<?php

namespace Modules\Jpg\tests\Feature\JpgToPng;

use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class JpgToPngShowTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_show_jpg_to_png_success(): void
    {
        $directory = storage_path('app/public/document_jpg_to_png/');
        $fileName = uniqid() . '_' . 'jpgToPngShow.png';
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

        $response = $this->get("/jpg-to-png/$jpg->owner");
        $response->assertStatus(200);

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Jpg To Png');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('JPG to PNG Converter');

        $response->assertViewHas('pngFiles');
        $pngFiles = $response->original->getData()['pngFiles'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $pngFiles);
        $this->assertGreaterThan(0, $pngFiles->count());

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully viewed jpg to png data with uuid jpg: ';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_show_jpg_to_png_failed_because_not_uuid(): void
    {
        $response = $this->get("/jpg-to-png/uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_show_jpg_to_png_failed_because_data_not_found(): void
    {
        $response = $this->get("/jpg-to-png/a4b1d9c8-6f77-41ef-beb9-ed8678106b15");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data png anda tidak ditemukan!', session('error'));
    }
}
