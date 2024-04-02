<?php

namespace Modules\Png\tests\Feature\PngToJpg;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToJpgShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_png_to_jpg_success(): void
    {
        $directory = storage_path('app/public/document_png_to_jpg/');
        $fileName = uniqid() . '_' . 'pngToJpgShow.jpg';
        ;
        $filePath = $directory . $fileName;
        $imageContent = '';
        file_put_contents($filePath, $imageContent);

        $png = Png::pngOwnerFactory()->create();

        Jpg::create([
            'png_uuid' => $png->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => null,
            'file' => 'document_png_to_jpg/' . $fileName,
            'name' => $fileName,
        ]);

        Storage::delete('public/document_png_to_jpg/' . $fileName);

        $response = $this->get("/png-to-jpg/$png->owner");
        $response->assertStatus(200);

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Png To Jpg');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PNG to JPG Converter');

        $response->assertViewHas('jpgFiles');
        $jpgFiles = $response->original->getData()['jpgFiles'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $jpgFiles);
        $this->assertGreaterThan(0, $jpgFiles->count());
    }

    public function test_show_png_to_jpg_failed_because_not_uuid(): void
    {
        $response = $this->get("/png-to-jpg/uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data anda tidak valid!', session('error'));
    }

    public function test_show_png_to_jpg_failed_because_data_not_found(): void
    {
        $response = $this->get("/png-to-jpg/a4b1d9c8-6f77-41ef-beb9-ed8678106b15");
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data jpg anda tidak ditemukan!', session('error'));
    }
}
