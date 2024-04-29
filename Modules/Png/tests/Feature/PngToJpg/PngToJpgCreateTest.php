<?php

namespace Modules\Png\tests\Feature\PngToJpg;

use App\Services\LoggingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;

class PngToJpgCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_create_png_to_jpg_success(): void
    {
        $image = UploadedFile::fake()->image('pngToJpgCreate.png');
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgCreate',
        ];

        $response = $this->post('/png-to-jpg', [
            '_token' => csrf_token(),
            'file-png' => $dummyData[0],
            'file' => $dummyData[1],
            'name' => $dummyData[2],
        ]);

        $this->assertDatabaseHas('jpgs', [
            'name' => 'pngToJpgCreate.jpg',
        ]);

        $jpg = Jpg::where('name', 'pngToJpgCreate.jpg')->first();
        Storage::delete('public/' . $jpg->file);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('File png berhasil di convert ke jpg!', session('success'));

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        $expectedLogText = 'user successfully convert png to jpg:';
        $this->assertStringContainsString($expectedLogText, $logContent);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_create_png_to_jpg_failed_because_form_is_empty(): void
    {
        $response = $this->post('/png-to-jpg', [
            '_token' => csrf_token(),
            'file-png' => '',
            'file' => '',
            'name' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_png_to_jpg_failed_because_not_png_file(): void
    {
        $image = UploadedFile::fake()->image('pngToJpgCreate.jpg');
        $base64Png = 'data:image/jpeg;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgCreate',
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

    public function test_create_png_to_jpg_failed_because_more_than_1_mb(): void
    {
        $image = UploadedFile::fake()->image('pngToJpgCreate.png')->size(2048);
        $base64Png = 'data:image/png;base64,' . base64_encode($image);

        $dummyData = [
            $image,
            $base64Png,
            'pngToJpgCreate',
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
