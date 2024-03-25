<?php

namespace Modules\Png\App\Services;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;

class PngToPdfService
{
    public function convertAndSave($saveFileFromCall, $saveUuidFromCall = null)
    {
        set_time_limit(300);

        return DB::transaction(function () use ($saveFileFromCall, $saveUuidFromCall) {
            $pngFilePath = str_replace('public/', '', $saveFileFromCall->store('public/document_png_to_pdf'));
            $fileContentsArray = file(storage_path('app/public/' . $pngFilePath));
            $fileContents = implode("", $fileContentsArray);
            $pngFileEndCode = 'data:image/png;base64,' . base64_encode($fileContents);

            $previewImage = '
                <!DOCTYPE html>
                <html>
                    <head>
                        <style>
                            * {
                                margin: 0;
                                padding: 0;
                            }
                        </style>
                    </head>
                    
                    <body>
                        <img src="' . $pngFileEndCode . '">
                    </body>
                </html>
            ';

            list($imageWidth, $imageHeight) = getimagesize($saveFileFromCall);
            $mmWidth = $imageWidth * 0.751;
            $mmHeight = $imageHeight * 0.752;

            $pdf = DomPDF::loadHtml($previewImage);
            $customPaper = array(0, 0, $mmWidth, $mmHeight, 'landscape');
            $pdf->setPaper($customPaper);
            $pdfContent = $pdf->output();

            $randomFileName = Str::random(40);
            $pdfFilePath = "public/document_png_to_pdf/{$randomFileName}.pdf";
            Storage::put($pdfFilePath, $pdfContent);

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

            $uuidPng = $pngFile->uuid;

            Pdf::create([
                'jpg_uuid' => null,
                'png_uuid' => $uuidPng ?? $pngFile->uuid,
                'uuid' => Uuid::uuid4()->toString(),
                'owner' => null,
                'name' => pathinfo($saveFileFromCall->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf',
                'file' => 'document_png_to_pdf/' . basename($pdfFilePath),
                'preview' => $pngFilePath,
            ]);

            return $uuidOwner;
        });
    }
}
