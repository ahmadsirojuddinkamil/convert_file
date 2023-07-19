<?php

namespace Tests\Unit;

use App\Models\Jpg;
use App\Models\Png;
use App\Services\DeleteFileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceDeleteAllFileTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteAllFile()
    {
        Storage::fake('public');

        $jpg = Jpg::factory()->create();
        $png = Png::factory()->create(['jpg_id' => $jpg->id]);

        $service = new DeleteFileService();
        $service->deleteAllFile($jpg->uuid);

        Storage::disk('public')->assertMissing('public/' . $jpg->file);
        Storage::disk('public')->assertMissing('public/' . $png->file);

        $this->assertDatabaseMissing('jpgs', ['id' => $jpg->id]);
        $this->assertDatabaseMissing('pngs', ['id' => $png->id]);

        // Simulasi sebaliknya
        $pngWithoutJpg = Png::factory()->create();
        $service->deleteAllFile($pngWithoutJpg->uuid);

        Storage::disk('public')->assertMissing('public/' . $pngWithoutJpg->file);

        $this->assertDatabaseMissing('pngs', ['id' => $pngWithoutJpg->id]);
    }
}
