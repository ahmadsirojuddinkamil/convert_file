<?php

namespace App\Services;

use App\Models\{Jpg, Pdf};
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Spatie\PdfToImage\Pdf as SpatiePDF;
use Illuminate\Support\Str;

class PdfToJpgService
{
    public function convertAndSave($saveFileFromCallRequest, $saveUuidFromCallRequest = null)
    {
        $pdfFilePath = $saveFileFromCallRequest->store('public/document_pdf_to_jpg');
        $pdfFilePath = str_replace('public/', '', $pdfFilePath);

        $saveOriginalNameFile = $saveFileFromCallRequest->getClientOriginalName();
        $changeFormatName = str_replace('.pdf', '.jpg', $saveOriginalNameFile);

        $pdf = new SpatiePDF('storage/' . $pdfFilePath);
        $randomFileName = Str::random(40);
        $jpgFilePath = "storage/document_pdf_to_jpg/{$randomFileName}.jpg";
        $pdf->saveImage($jpgFilePath);

        $dataUuidLocalStorage = $saveUuidFromCallRequest ?? Uuid::uuid4()->toString();
        $dataFile = Pdf::create([
            'uuid' => $dataUuidLocalStorage,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $saveOriginalNameFile,
            'file' => $pdfFilePath,
        ]);

        $removeStorage = str_replace('storage/', '', $jpgFilePath);
        Jpg::create([
            'pdf_id' => $dataFile->id,
            'uuid' => $dataFile->uuid,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $changeFormatName,
            'file' => $removeStorage,
        ]);

        Storage::delete('public/' . $dataFile->file);

        return $dataFile;
    }
}
