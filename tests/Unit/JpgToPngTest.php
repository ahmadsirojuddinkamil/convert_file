<?php

namespace Tests\Unit;

use App\Models\{Jpg, Png};
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

    public function testReply()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.jpg');

        $dataFile = [
            'uuid' => 'af2d4559-0ee8-405f-bd05-1e348962357f',
            'file' => $file,
            'name' => 'file before png',
        ];

        $dataExists = Jpg::where('uuid', $dataFile['uuid'])->exists();

        if(!$dataExists) {
            $response = $this->post('/jpg_to_pdf', $dataFile);
            $response->assertStatus(302);
            $response->assertRedirect('/jpg_to_pdf/af2d4559-0ee8-405f-bd05-1e348962357f/file');
        }

        $dataFileResponse = [
            'uuid' => 'af2d4559-0ee8-405f-bd05-1e348962357f',
            'file' => $file,
            'name' => 'file before png 2',
        ];

        $replyResponse = $this->post('/jpg_to_pdf/af2d4559-0ee8-405f-bd05-1e348962357f/reply', $dataFileResponse);
        $replyResponse->assertStatus(302);
        $replyResponse->assertRedirect('/jpg_to_pdf/af2d4559-0ee8-405f-bd05-1e348962357f/file');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Png::factory()->create();
        $response = $this->get('/jpg_to_png/' . $dataFile->unique_id . '/download');
        $response->assertStatus(500);
    }
}
