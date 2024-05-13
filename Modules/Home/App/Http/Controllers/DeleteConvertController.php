<?php

namespace Modules\Home\App\Http\Controllers;

use Modules\Utility\App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;

class DeleteConvertController extends Controller
{
    protected $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function deleteConvert($saveUuidFromCall, $saveNameFromCall = null)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall) || strlen($saveUuidFromCall) != 36) {
            return response()->json(['error' => 'Data uuid tidak valid'], 400);
        }

        $title = [
            'JPG to PNG Converter',
            'PNG to JPG Converter',
            'JPG to PDF Converter',
            'PDF to JPG Converter',
            'PNG to PDF Converter',
            'PDF to PNG Converter',
            'null'
        ];

        if (!in_array($saveNameFromCall, $title)) {
            return response()->json(['error' => 'Data title tidak valid'], 400);
        }

        $fileTypeMap = [
            'JPG to PNG Converter' => ['model' => Jpg::class, 'relation' => 'pngs', 'deleteColumns' => ['file']],
            'PNG to JPG Converter' => ['model' => Png::class, 'relation' => 'jpgs', 'deleteColumns' => ['file']],
            'JPG to PDF Converter' => ['model' => Jpg::class, 'relation' => 'pdfs', 'deleteColumns' => ['file', 'preview']],
            'PDF to JPG Converter' => ['model' => Pdf::class, 'relation' => 'jpgs', 'deleteColumns' => ['file']],
            'PNG to PDF Converter' => ['model' => Png::class, 'relation' => 'pdfs', 'deleteColumns' => ['file', 'preview']],
            'PDF to PNG Converter' => ['model' => Pdf::class, 'relation' => 'pngs', 'deleteColumns' => ['file']],
        ];

        $fileTypeData = $fileTypeMap[$saveNameFromCall];
        $fileModel = $fileTypeData['model']::with($fileTypeData['relation'])->where('owner', $saveUuidFromCall)->first();

        if (!$fileModel) {
            return response()->json(['error' => 'Data utama anda tidak ditemukan!'], 404);
        }

        $relatedFiles = $fileModel->{$fileTypeData['relation']}->sortByDesc('created_at');

        if ($relatedFiles->isEmpty()) {
            return response()->json(['error' => 'Data child anda tidak ditemukan!'], 404);
        }

        foreach ($relatedFiles as $relatedFile) {
            foreach ($fileTypeData['deleteColumns'] as $column) {
                if ($relatedFile->{$column}) {
                    Storage::delete('public/' . $relatedFile->{$column});
                }
            }
        }

        $fileModel->delete();

        return response()->json(['message' => 'Penghapusan berhasil'], 200);
    }

    public function deleteConvert10Minute()
    {
        $currentTime = time() - (10 * 60); // 10 menit yang lalu
        $countDeleted = 0;

        $jpgs = Jpg::with('pngs', 'pdfs')->latest()->get();
        if (!$jpgs->isEmpty()) {
            foreach ($jpgs as $jpg) {
                foreach ($jpg->pngs as $png) {
                    $createdTime = strtotime($png->created_at);

                    if ($createdTime <= $currentTime) {
                        Storage::delete('public/' . $png->file);
                        $png->delete();
                        $countDeleted++;
                    }
                }

                foreach ($jpg->pdfs as $pdf) {
                    $createdTime = strtotime($pdf->created_at);

                    if ($createdTime <= $currentTime) {
                        Storage::delete('public/' . $pdf->file);
                        Storage::delete('public/' . $pdf->preview);
                        $pdf->delete();
                        $countDeleted++;
                    }
                }

                $createdTime = strtotime($jpg->created_at);
                if ($jpg->pngs->isEmpty() && $jpg->pdfs->isEmpty() && $createdTime <= $currentTime) {
                    $jpg->delete();
                    $countDeleted++;
                }
            }
        }

        $pngs = Png::with('jpgs', 'pdfs')->latest()->get();
        if (!$pngs->isEmpty()) {
            foreach ($pngs as $png) {
                foreach ($png->jpgs as $jpg) {
                    $createdTime = strtotime($jpg->created_at);

                    if ($createdTime <= $currentTime) {
                        Storage::delete('public/' . $jpg->file);
                        $jpg->delete();
                        $countDeleted++;
                    }
                }

                foreach ($png->pdfs as $pdf) {
                    $createdTime = strtotime($pdf->created_at);

                    if ($createdTime <= $currentTime) {
                        Storage::delete('public/' . $pdf->file);
                        Storage::delete('public/' . $pdf->preview);
                        $pdf->delete();
                        $countDeleted++;
                    }
                }

                $createdTime = strtotime($png->created_at);
                if ($png->jpgs->isEmpty() && $png->pdfs->isEmpty() && $createdTime <= $currentTime) {
                    $png->delete();
                    $countDeleted++;
                }
            }
        }

        $pdfs = Pdf::with('jpgs', 'pngs')->latest()->get();
        if (!$pdfs->isEmpty()) {
            foreach ($pdfs as $pdf) {
                foreach ($pdf->jpgs as $jpg) {
                    $createdTime = strtotime($jpg->created_at);

                    if ($createdTime <= $currentTime) {
                        Storage::delete('public/' . $jpg->file);
                        $jpg->delete();
                        $countDeleted++;
                    }
                }

                foreach ($pdf->pngs as $png) {
                    $createdTime = strtotime($png->created_at);

                    if ($createdTime <= $currentTime) {
                        Storage::delete('public/' . $png->file);
                        $png->delete();
                        $countDeleted++;
                    }
                }

                $createdTime = strtotime($pdf->created_at);
                if ($pdf->jpgs->isEmpty() && $pdf->pngs->isEmpty() && $createdTime >= $currentTime) {
                    $pdf->delete();
                    $countDeleted++;
                }
            }
        }

        if ($countDeleted == 0) {
            return response()->json(['message' => 'tidak ada data yang lebih dari 10 menit!'], 200);
        }

        return response()->json(['message' => 'data yang lebih dari 10 menit berhasil dihapus!'], 200);
    }
}
