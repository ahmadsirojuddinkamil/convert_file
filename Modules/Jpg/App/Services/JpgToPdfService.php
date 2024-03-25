<?php

namespace Modules\Jpg\App\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;

class JpgToPdfService
{
    public function convertAndSave($saveFileFromCall, $saveUuidFromCall = null)
    {
        set_time_limit(300);

        return DB::transaction(function () use ($saveFileFromCall, $saveUuidFromCall) {
            $jpgFilePath = str_replace('public/', '', $saveFileFromCall->store('public/document_jpg_to_pdf'));
            $fileContentsArray = file(storage_path('app/public/' . $jpgFilePath));
            $fileContents = implode("", $fileContentsArray);
            $jpgFileEndCode = 'data:image/jpeg;base64,' . base64_encode($fileContents);

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
                        <img src="' . $jpgFileEndCode . '">
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
            $pdfFilePath = "public/document_jpg_to_pdf/{$randomFileName}.pdf";
            Storage::put($pdfFilePath, $pdfContent);

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

            Pdf::create([
                'jpg_uuid' => $uuidJpg ?? $jpgFile->uuid,
                'png_uuid' => null,
                'uuid' => Uuid::uuid4()->toString(),
                'owner' => null,
                'name' => pathinfo($saveFileFromCall->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf',
                'file' => 'document_jpg_to_pdf/' . basename($pdfFilePath),
                'preview' => $jpgFilePath,
            ]);

            return $uuidOwner;
        });
    }
}
