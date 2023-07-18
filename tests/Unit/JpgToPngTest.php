<?php

namespace Tests\Unit;

use App\Models\Png;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class JpgToPngTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/jpg_to_png');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.jpgToPng.index');
    }

    public function testShow()
    {
        $dataFile = Png::factory()->create();
        $response = $this->get('/jpg_to_png/' . $dataFile->uuid . '/file');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.jpgToPng.show');
        $response->assertViewHas('findAndGetDataFile');
    }

    public function testCreate()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.jpg');

        $dataFile = [
            'file' => $file,
            'uuid' => '41d450a1-eeae-4e89-a307-da309f682cca',
            'name' => 'file before png',
        ];

        $response = $this->post('/jpg_to_png', $dataFile);

        $response->assertStatus(302);
        $response->assertRedirect('/jpg_to_png/' . $dataFile['uuid'] . '/file');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Png::factory()->create();
        $response = $this->get('/jpg_to_png/' . $dataFile->uuid . '/download');
        $response->assertStatus(500);
    }

    public function testDelete()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Png::factory()->create();
        $response = $this->delete('/jpg_to_png/' . $dataFile->uuid);
        $response->assertStatus(200);
    }
}
