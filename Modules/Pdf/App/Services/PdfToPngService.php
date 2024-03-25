<?php

namespace Modules\Pdf\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PdfToPngService
{
    public function convertAndSave($saveDataFromCall, $saveUuidFromCall = null)
    {
        set_time_limit(300);

        return DB::transaction(function () use ($saveDataFromCall, $saveUuidFromCall) {
            list($header, $data) = explode(',', $saveDataFromCall['link_pdf']);
            $imageData = base64_decode($data);
            $namePng = str_replace('.png', '', $saveDataFromCall['name']);
            $fileName = uniqid() . '_' . $namePng . '.png';
            $filePath = storage_path('app/public/document_pdf_to_png/' . $fileName);
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

            Png::create([
                'jpg_uuid' => null,
                'pdf_uuid' => $uuidPdf ?? $pdfFile->uuid,
                'uuid' => Uuid::uuid4()->toString(),
                'owner' => null,
                'name' => $namePng . '.png',
                'file' => 'document_pdf_to_png/' . $fileName,
            ]);

            return $uuidOwner;
        });
    }

    public function validationUuid($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall) || strlen($saveUuidFromCall) != 36) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        } else {
            return true;
        }
    }
}
