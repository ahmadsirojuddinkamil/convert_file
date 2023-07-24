<?php

namespace App\Services;

use App\Models\{Jpg, Pdf};
use PDF as DomPDF;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class JpgToPdfService
{
    public function convertAndSave($saveFileFromCallRequest, $saveUuidFromCallRequest = null)
    {
        $jpgFilePath = $saveFileFromCallRequest->store('public/document_jpg_to_pdf');
        $jpgFilePath = str_replace('public/', '', $jpgFilePath);

        $image = Image::make($saveFileFromCallRequest);
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        $mmWidth = $imageWidth * 0.755;
        $mmHeight = $imageHeight * 0.755;

        $pdf = DomPDF::loadHtml('
            <!DOCTYPE html>
            <html>
            <head>
                <title>Hi</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                    }
                </style>
            </head>
            <body>
                <img src="storage/' . $jpgFilePath . '">
            </body>
            </html>
        ');

        $customPaper = array(0, 0, $mmWidth, $mmHeight, 'landscape');
        $pdf->setPaper($customPaper);
        $pdfContent = $pdf->output();

        $randomFileName = Str::random(40);
        $pdfFilePath = "public/document_jpg_to_pdf/{$randomFileName}.pdf";
        Storage::put($pdfFilePath, $pdfContent);

        $dataFile = Jpg::create([
            'uuid' => $saveUuidFromCallRequest ?? Uuid::uuid4()->toString(),
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $saveFileFromCallRequest->getClientOriginalName(),
            'file' => $jpgFilePath,
        ]);

        $pdfNameFile = str_replace('.jpg', '.pdf', $saveFileFromCallRequest->getClientOriginalName());
        $removePublicPath = str_replace('public/', '', $pdfFilePath);
        Pdf::create([
            'jpg_id' => $dataFile->id,
            'uuid' => $dataFile->uuid,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $pdfNameFile,
            'file' => $removePublicPath,
        ]);

        Storage::delete('public/' . $dataFile->file);

        return $dataFile;
    }
}
