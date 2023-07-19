<?php

namespace Tests\Unit;

use App\Services\PngToJpgService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ServicePngToJpgTest extends TestCase
{
    use RefreshDatabase;

    public function testConvertAndSave()
    {
        Storage::fake('public');

        $pngFile = UploadedFile::fake()->image('file.png');

        $pngToJpgService = new PngToJpgService();

        $result = $pngToJpgService->convertAndSave($pngFile);

        Storage::disk('public')->assertMissing($result->file);

        // // Memastikan bahwa file png ada di storage
        // Storage::disk('public')->assertExists($result->png->file);

        $this->assertDatabaseHas('pngs', [
            'uuid' => $result->uuid,
            'name' => $pngFile->getClientOriginalName(),
            'file' => $result->file,
        ]);

        $this->assertDatabaseHas('jpgs', [
            // 'png_id' => $result->id,
            'uuid' => $result->uuid,
            'name' => pathinfo($pngFile->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
            // 'file' => $result->png ? 'document_jpg_to_png/' . basename($result->png->file) : null,
        ]);

    }
}
