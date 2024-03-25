<?php

namespace Modules\Pdf\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class PdfToJpgService
{
    public function convertAndSave($saveDataFromCall, $saveUuidFromCall = null)
    {
        // dd($saveDataFromCall);
        set_time_limit(300);

        return DB::transaction(function () use ($saveDataFromCall, $saveUuidFromCall) {
            list($header, $data) = explode(',', $saveDataFromCall['link_pdf']);
            $imageData = base64_decode($data);
            $nameJpg = str_replace('.jpg', '', $saveDataFromCall['name']);
            $fileName = uniqid() . '_' . $nameJpg . '.jpg';
            $filePath = storage_path('app/public/document_pdf_to_jpg/' . $fileName);
            file_put_contents($filePath, $imageData);

            $uuidOwner = $saveUuidFromCall ?? Uuid::uuid4()->toString();

            $pdfFile = Pdf::firstOrCreate(
                ['owner' => $uuidOwner],
                [
                    'jpg_uuid' => null,
                    'png_uuid' => null,
                    'uuid' => Uuid::uuid4()->toString(),
                    'file' => null,
                    'name' => null,
                    'preview' => null,
                ]
            );

            $uuidPdf = $pdfFile->uuid;

            Jpg::create([
                'png_uuid' => null,
                'pdf_uuid' => $uuidPdf ?? $pdfFile->uuid,
                'uuid' => Uuid::uuid4()->toString(),
                'owner' => null,
                'name' => $nameJpg . '.jpg',
                'file' => 'document_pdf_to_jpg/' . $fileName,
            ]);

            return $uuidOwner;
        });
    }
}
