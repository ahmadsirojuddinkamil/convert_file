<?php

namespace Modules\Png\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngToJpgService
{
    public function convertAndSave($saveDataFromCall, $saveUuidFromCall = null)
    {
        set_time_limit(300);

        return DB::transaction(function () use ($saveDataFromCall, $saveUuidFromCall) {
            list($header, $data) = explode(',', $saveDataFromCall['file']);
            $imageData = base64_decode($data);
            $namePng = str_replace('.png', '', $saveDataFromCall['name']);
            $fileName = uniqid() . '_' . $namePng . '.jpg';
            $filePath = storage_path('app/public/document_png_to_jpg/' . $fileName);
            file_put_contents($filePath, $imageData);

            $uuidOwner = $saveUuidFromCall ?? Uuid::uuid4()->toString();

            $pngFile = Png::firstOrCreate(
                ['owner' => $uuidOwner],
                [
                    'jpg_uuid' => null,
                    'pdf_uuid' => null,
                    'uuid' => Uuid::uuid4()->toString(),
                    'file' => null,
                    'name' => null,
                ]
            );

            $uuidJpg = $pngFile->uuid;

            Jpg::create([
                'png_uuid' => $uuidJpg ?? $pngFile->uuid,
                'pdf_uuid' => null,
                'uuid' => Uuid::uuid4()->toString(),
                'owner' => null,
                'name' => $namePng . '.jpg',
                'file' => 'document_png_to_jpg/' . $fileName,
            ]);

            return $uuidOwner;
        });
    }
}
