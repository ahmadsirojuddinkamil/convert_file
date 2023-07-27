<?php

namespace Tests\Unit;

use App\Models\{Jpg, Pdf};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PngToPdfTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/png_to_pdf');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pngToPdf.index');
    }

    public function testShow()
    {
        $dataFile = Pdf::factory()->create();
        $response = $this->get('/png_to_pdf/' . $dataFile->uuid . '/file');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pngToPdf.show');
        $response->assertViewHas('findAndGetDataFile');
    }

    public function testCreate()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.png');

        $dataFile = [
            'uuid' => '8ece46c0-b1c1-451c-a197-3786e995a569',
            'unique_id' => 'ed716f49-72d3-46be-b5f6-b3753d7d81ab',
            'file' => $file,
            'name' => 'file before pdf',
        ];

        $response = $this->post('/png_to_pdf', $dataFile);

        $response->assertStatus(302);
        $response->assertRedirect('/png_to_pdf/8ece46c0-b1c1-451c-a197-3786e995a569/file');
    }

    public function testReply()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.png');

        $dataFile = [
            'uuid' => '3998f5fe-5ed5-4b1f-8ebf-244fa1a5586f',
            'file' => $file,
            'name' => 'file before pdf',
        ];

        $dataExists = Jpg::where('uuid', $dataFile['uuid'])->exists();

        if(!$dataExists) {
            $response = $this->post('/png_to_pdf', $dataFile);
            $response->assertStatus(302);
            $response->assertRedirect('/png_to_pdf/3998f5fe-5ed5-4b1f-8ebf-244fa1a5586f/file');
        }

        $dataFileResponse = [
            'uuid' => '3998f5fe-5ed5-4b1f-8ebf-244fa1a5586f',
            'file' => $file,
            'name' => 'file before pdf 2',
        ];

        $replyResponse = $this->post('/png_to_pdf/3998f5fe-5ed5-4b1f-8ebf-244fa1a5586f/reply', $dataFileResponse);
        $replyResponse->assertStatus(302);
        $replyResponse->assertRedirect('/png_to_pdf/3998f5fe-5ed5-4b1f-8ebf-244fa1a5586f/file');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Pdf::factory()->create();
        $response = $this->get('/png_to_pdf/' . $dataFile->unique_id . '/download');
        $response->assertStatus(500);
    }
}
