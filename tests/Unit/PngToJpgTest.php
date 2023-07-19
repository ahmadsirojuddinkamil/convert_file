<?php

namespace Tests\Unit;

use App\Models\Jpg;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PngToJpgTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/png_to_jpg');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pngToJpg.index');
    }

    public function testShow()
    {
        $dataFile = Jpg::factory()->create();
        $response = $this->get('/png_to_jpg/' . $dataFile->uuid . '/file');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pngToJpg.show');
        $response->assertViewHas('findAndGetDataFile');
    }

    public function testCreate()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.png');

        $dataFile = [
            'file' => $file,
            'uuid' => '41d450a1-eeae-4e89-a307-da309f682cca',
            'name' => 'file before jpg',
        ];

        $response = $this->post('/png_to_jpg', $dataFile);

        $response->assertStatus(302);
        $response->assertRedirect('/png_to_jpg/' . $dataFile['uuid'] . '/file');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Jpg::factory()->create();
        $response = $this->get('/png_to_jpg/' . $dataFile->unique_id . '/download');
        $response->assertStatus(500);
    }
}
