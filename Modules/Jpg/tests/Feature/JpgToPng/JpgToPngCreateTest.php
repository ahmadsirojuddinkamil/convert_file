<?php

namespace Modules\Jpg\tests\Feature\JpgToPng;

use App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Png\App\Models\Png;

class JpgToPngCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_create_jpg_to_png_success(): void
    {
        $image = UploadedFile::fake()->image('jpgToPngCreate.jpg');
        $base64Jpg = 'data:image/jpeg;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'jpgToPngCreate.jpg',
        ];

        $response = $this->post('/jpg-to-png', [
            '_token' => csrf_token(),
            'file-jpg' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('pngs', [
            'name' => 'jpgToPngCreate.png',
        ]);

        $png = Png::where('name', 'jpgToPngCreate.png')->first();
        Storage::delete('public/' . $png->file);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File jpg berhasil di convert ke png!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully convert jpg to png:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_create_jpg_to_png_failed_because_form_is_empty(): void
    {
        $response = $this->post('/jpg-to-png', [
            '_token' => csrf_token(),
            'file-jpg' => '',
            'file' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_jpg_to_png_failed_because_not_jpg_file(): void
    {
        $image = UploadedFile::fake()->image('home.png');
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'home.jpg',
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

    public function test_create_jpg_to_png_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('home.jpg')->size(2048);
        $base64Jpg = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Jpg,
            'home.jpg',
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
