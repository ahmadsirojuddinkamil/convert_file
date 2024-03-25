<?php

namespace Modules\Jpg\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class JpgToPngService
{
    public function convertAndSave($saveDataFromCall, $saveUuidFromCall = null)
    {
        set_time_limit(300);

        return DB::transaction(function () use ($saveDataFromCall, $saveUuidFromCall) {
            list($header, $data) = explode(',', $saveDataFromCall['file']);
            $imageData = base64_decode($data);
            $namePng = str_replace('.jpg', '', $saveDataFromCall['name']);
            $fileName = uniqid() . '_' . $namePng . '.png';
            $filePath = storage_path('app/public/document_jpg_to_png/' . $fileName);
            file_put_contents($filePath, $imageData);

            $uuidOwner = $saveUuidFromCall ?? Uuid::uuid4()->toString();

            $jpgFile = Jpg::firstOrCreate(
                ['owner' => $uuidOwner],
                [
                    'png_uuid' => null,
                    'pdf_uuid' => null,
                    'uuid' => Uuid::uuid4()->toString(),
                    'file' => null,
                    'name' => null,
                ]
            );

            $uuidJpg = $jpgFile->uuid;

            Png::create([
                'jpg_uuid' => $uuidJpg ?? $jpgFile->uuid,
                'pdf_uuid' => null,
                'uuid' => Uuid::uuid4()->toString(),
                'owner' => null,
                'name' => $namePng . '.png',
                'file' => 'document_jpg_to_png/' . $fileName,
            ]);

            return $uuidOwner;
        });
    }
}
