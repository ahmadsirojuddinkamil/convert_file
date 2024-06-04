<?php

namespace Modules\Home\tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class DeleteAllConvertTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_all_convert_file_above_10_minute_success(): void
    {
        $twentyMinutesAgo = Carbon::now()->subMinutes(20);

        $jpg = Jpg::jpgOwnerFactory()->create();

        Png::create([
            'jpg_uuid' => $jpg->uuid,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
            'created_at' => $twentyMinutesAgo,
        ]);

        $response = $this->delete('/delete-convert/10-minute');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'data yang lebih dari 10 menit berhasil dihapus!'
        ]);
    }

    public function test_delete_all_convert_file_above_10_minute_nothing_delete(): void
    {
        $response = $this->delete('/delete-convert/10-minute');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'tidak ada data yang lebih dari 10 menit!'
        ]);
    }
}
