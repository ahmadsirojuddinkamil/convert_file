<?php

namespace Tests\Unit;

use App\Models\Jpg;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfToJpgTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/pdf_to_jpg');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pdfToJpg.index');
    }

    public function testShow()
    {
        $dataFile = Jpg::factory()->create();
        $response = $this->get('/pdf_to_jpg/' . $dataFile->uuid . '/file');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pdfToJpg.show');
        $response->assertViewHas('findAndGetDataFile');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Jpg::factory()->create();
        $response = $this->get('/pdf_to_jpg/' . $dataFile->unique_id . '/download');
        $response->assertStatus(500);
    }
}
