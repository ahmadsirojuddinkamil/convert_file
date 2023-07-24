<?php

namespace Tests\Unit;

use App\Models\{Jpg, Pdf};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JpgToPdfTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/jpg_to_pdf');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.jpgToPdf.index');
    }

    public function testShow()
    {
        $dataFile = Pdf::factory()->create();
        $response = $this->get('/jpg_to_pdf/' . $dataFile->uuid . '/file');
        $response->assertStatus(200);
        $response->assertViewIs('pages.convert.jpgToPdf.show');
        $response->assertViewHas('findAndGetDataFile');
    }

    public function testCreate()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.jpg');

        $dataFile = [
            'uuid' => '88139497-6a0d-49a7-92d8-9e68deae045b',
            'unique_id' => 'e9155f94-b196-4df0-84b6-63a6ca529636',
            'file' => $file,
            'name' => 'file before pdf',
        ];

        $response = $this->post('/jpg_to_pdf', $dataFile);

        $response->assertStatus(302);
        $response->assertRedirect('/jpg_to_pdf/88139497-6a0d-49a7-92d8-9e68deae045b/file');
    }

    public function testReply()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.jpg');

        $dataFile = [
            'uuid' => '06b89617-9cfa-41fd-94a2-52ee3db15adc',
            'file' => $file,
            'name' => 'file before pdf',
        ];

        $dataExists = Jpg::where('uuid', $dataFile['uuid'])->exists();

        if(!$dataExists) {
            $response = $this->post('/jpg_to_pdf', $dataFile);
            $response->assertStatus(302);
            $response->assertRedirect('/jpg_to_pdf/06b89617-9cfa-41fd-94a2-52ee3db15adc/file');
        }

        $dataFileResponse = [
            'uuid' => '06b89617-9cfa-41fd-94a2-52ee3db15adc',
            'file' => $file,
            'name' => 'file before pdf 2',
        ];

        $replyResponse = $this->post('/jpg_to_pdf/06b89617-9cfa-41fd-94a2-52ee3db15adc/reply', $dataFileResponse);
        $replyResponse->assertStatus(302);
        $replyResponse->assertRedirect('/jpg_to_pdf/06b89617-9cfa-41fd-94a2-52ee3db15adc/file');
    }

    public function testDownload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $dataFile = Pdf::factory()->create();
        $response = $this->get('/jpg_to_pdf/' . $dataFile->unique_id . '/download');
        $response->assertStatus(500);
    }
}
