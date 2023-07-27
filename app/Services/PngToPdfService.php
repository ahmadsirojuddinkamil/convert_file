<?php

namespace App\Services;

use App\Models\{Pdf, Png};
use PDF as DomPDF;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class PngToPdfService
{
    public function convertAndSave($saveFileFromCallRequest, $saveUuidFromCallRequest = null)
    {
        $pngFilePath = $saveFileFromCallRequest->store('public/document_png_to_pdf');
        $pngFilePath = str_replace('public/', '', $pngFilePath);

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
                <img src="storage/' . $pngFilePath . '">
            </body>
            </html>
        ');

        $customPaper = array(0, 0, $mmWidth, $mmHeight, 'landscape');
        $pdf->setPaper($customPaper);
        $pdfContent = $pdf->output();

        $randomFileName = Str::random(40);
        $pdfFilePath = "public/document_png_to_pdf/{$randomFileName}.pdf";
        Storage::put($pdfFilePath, $pdfContent);

        $dataFile = Png::create([
            'uuid' => $saveUuidFromCallRequest ?? Uuid::uuid4()->toString(),
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $saveFileFromCallRequest->getClientOriginalName(),
            'file' => $pngFilePath,
        ]);

        $pdfNameFile = str_replace('.png', '.pdf', $saveFileFromCallRequest->getClientOriginalName());
        $removePublicPath = str_replace('public/', '', $pdfFilePath);
        Pdf::create([
            'png_id' => $dataFile->id,
            'uuid' => $dataFile->uuid,
            'unique_id' => Uuid::uuid4()->toString(),
            'name' => $pdfNameFile,
            'file' => $removePublicPath,
        ]);

        Storage::delete('public/' . $dataFile->file);

        return $dataFile;
    }
}
