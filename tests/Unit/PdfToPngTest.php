<?php

namespace Tests\Unit;

use App\Models\Png;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfToPngTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/pdf_to_png');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pdfToPng.index');
    }

    public function testShow()
    {
        $dataFile = Png::factory()->create();
        $response = $this->get('/pdf_to_png/' . $dataFile->uuid . '/file');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.pdfToPng.show');
        $response->assertViewHas('findAndGetDataFile');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Png::factory()->create();
        $response = $this->get('/pdf_to_png/' . $dataFile->unique_id . '/download');
        $response->assertStatus(500);
    }
}
