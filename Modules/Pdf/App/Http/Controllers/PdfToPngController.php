<?php

namespace Modules\Pdf\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Http\Requests\CreatePdfToPngRequest;
use Modules\Pdf\App\Models\Pdf;
use Modules\Pdf\App\Services\PdfToPngService;
use Modules\Png\App\Models\Png;

class PdfToPngController extends Controller
{
    protected $pdfToPngService;
    protected $validationService;

    public function __construct(PdfToPngService $pdfToPngService, ValidationService $validationService)
    {
        $this->pdfToPngService = $pdfToPngService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        $title = 'File Convert - Pdf To Png';
        $typeConvert = 'PDF to PNG Converter';

        return view('pdf::layouts.pdfToPng.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePdfToPngRequest $request)
    {
        $validateData = $request->validated();

        $uuidOwner = $this->pdfToPngService->convertAndSave($validateData);

        return redirect('/pdf-to-png/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File pdf berhasil di convert ke png!'
        ]);
    }

    public function show($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $pdfFiles = Pdf::with('pngs')->where('owner', $saveUuidFromCall)->first();

        if (!$pdfFiles) {
            return redirect('/')->with('error', 'Data png anda tidak ditemukan!');
        }

        $pngFiles = $pdfFiles->pngs->sortByDesc('created_at');

        $title = 'File Convert - Pdf To Png';
        $typeConvert = 'PDF to PNG Converter';

        return view('pdf::layouts.pdfToPng.show', compact('pngFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePdfToPngRequest $request, $saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $validateData = $request->validated();

        $uuidOwner = $this->pdfToPngService->convertAndSave($validateData, $saveUuidFromCall);

        return redirect('/pdf-to-png/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File pdf berhasil di convert ke png!'
        ]);
    }

    public function download($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $result = Png::where('uuid', $saveUuidFromCall)->first();

        if (!$result) {
            return redirect('/')->with('error', 'Data png anda tidak ditemukan!');
        }

        $filePath = 'public/' . $result->file;
        $fileName = pathinfo($result->name, PATHINFO_FILENAME) . '.png';

        return response(Storage::get($filePath))
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
