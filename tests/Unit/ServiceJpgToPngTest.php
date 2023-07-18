<?php

namespace Tests\Unit;

use App\Models\{Jpg, Png};
use App\Services\JpgToPngService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ServiceJpgToPngTest extends TestCase
{
    use RefreshDatabase;

    public function testConvertAndSave()
    {
        Storage::fake('public');

        $jpgFile = UploadedFile::fake()->image('file.jpg');

        $jpgToPngService = new JpgToPngService();

        $result = $jpgToPngService->convertAndSave($jpgFile);

        Storage::disk('public')->assertMissing($result->file);

        // // Memastikan bahwa file png ada di storage
        // Storage::disk('public')->assertExists($result->png->file);

        $this->assertDatabaseHas('jpgs', [
            'uuid' => $result->uuid,
            'name' => $jpgFile->getClientOriginalName(),
            'file' => $result->file,
        ]);

        $this->assertDatabaseHas('pngs', [
            'jpg_id' => $result->id,
            'uuid' => $result->uuid,
            'name' => pathinfo($jpgFile->getClientOriginalName(), PATHINFO_FILENAME) . '.png',
            // 'file' => $result->png ? 'document_jpg_to_png/' . basename($result->png->file) : null,
        ]);

    }

    public function testDeleteFilePng()
    {
        Storage::fake('public');

        $jpg = Jpg::factory()->create();
        $png = Png::factory()->create(['jpg_id' => $jpg->id]);

        $service = new JpgToPngService();
        $service->deleteFilePng($jpg->uuid);

        Storage::disk('public')->assertMissing($jpg->file);
        Storage::disk('public')->assertMissing($png->file);

        $this->assertDatabaseMissing('jpgs', ['id' => $jpg->id]);
        $this->assertDatabaseMissing('pngs', ['id' => $png->id]);
    }
}
