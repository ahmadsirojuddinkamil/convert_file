<?php

namespace App\Services;

use App\Models\{Jpg, Png};
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class JpgToPngService
{
    public function convertAndSave($saveFileFromCallRequest, $saveUuidFromCallRequest = null)
    {
        $jpgFilePath = $saveFileFromCallRequest->store('public/document_jpg_to_png');
        $jpgFilePath = str_replace('public/', '', $jpgFilePath);

        $pngFilePath = str_replace('.jpg', '.png', $jpgFilePath);

        $image = Image::make(storage_path('app/public/' . $jpgFilePath));
        $image->encode('png', 100)->save(storage_path('app/public/' . $pngFilePath));

        $dataUuidLocalStorage = $saveUuidFromCallRequest ?? Uuid::uuid4()->toString();
        $dataFile = Jpg::create([
            'uuid' => $dataUuidLocalStorage,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $saveFileFromCallRequest->getClientOriginalName(),
            'file' => $jpgFilePath,
        ]);

        Png::create([
            'jpg_id' => $dataFile->id,
            'uuid' => $dataFile->uuid,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => pathinfo($saveFileFromCallRequest->getClientOriginalName(), PATHINFO_FILENAME) . '.png',
            'file' => 'document_jpg_to_png/' . basename($pngFilePath),
        ]);

        Storage::delete('public/' . $dataFile->file);

        return $dataFile;
    }
}
