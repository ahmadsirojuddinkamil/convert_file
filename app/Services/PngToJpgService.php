<?php

namespace App\Services;

use App\Models\{Jpg, Png};
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class PngToJpgService
{
    public function convertAndSave($saveFileFromCallRequest, $saveUuidFromCallRequest = null)
    {
        $pngFilePath = $saveFileFromCallRequest->store('public/document_png_to_jpg');
        $pngFilePath = str_replace('public/', '', $pngFilePath);
        $jpgFilePath = str_replace('.png', '.jpg', $pngFilePath);

        $image = Image::make(storage_path('app/public/' . $pngFilePath));
        $image->encode('jpg', 100)->save(storage_path('app/public/' . $jpgFilePath));

        $dataUuidLocalStorage = $saveUuidFromCallRequest ?? Uuid::uuid4()->toString();
        $dataFile = Png::create([
            'uuid' => $dataUuidLocalStorage,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $saveFileFromCallRequest->getClientOriginalName(),
            'file' => $pngFilePath,
        ]);

        Jpg::create([
            'jpg_id' => $dataFile->id,
            'uuid' => $dataFile->uuid,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => pathinfo($saveFileFromCallRequest->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
            'file' => 'document_png_to_jpg/' . basename($jpgFilePath),
        ]);

        Storage::delete('public/' . $dataFile->file);

        return $dataFile;
    }
}
