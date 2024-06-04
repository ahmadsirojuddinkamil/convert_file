<?php

namespace Modules\Pdf\App\Http\Controllers;

use Modules\Utility\App\Services\{TimeService, ValidationService};
use Modules\Pdf\App\Http\Requests\CreatePdfToPngRequest;
use Modules\Pdf\App\Services\PdfToPngService;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;

class PdfToPngController extends Controller
{
    protected $pdfToPngService;
    protected $validationService;
    protected $timeService;

    public function __construct(PdfToPngService $pdfToPngService, ValidationService $validationService, TimeService $timeService)
    {
        $this->pdfToPngService = $pdfToPngService;
        $this->validationService = $validationService;
        $this->timeService = $timeService;
    }

    public function index()
    {
        $title = 'File Convert - Pdf To Png';
        $typeConvert = 'PDF to PNG Converter';

        return view('pdf::layouts.pdfToPng.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePdfToPngRequest $request)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $uuidOwner = $this->pdfToPngService->convertAndSave($validateData);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);
        Log::info('user successfully convert pdf to png: ' . $finalTime . ' detik');

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

        Log::info('user successfully viewed pdf to png data with uuid pdf: ', ['uuid' => $saveUuidFromCall]);

        return view('pdf::layouts.pdfToPng.show', compact('pngFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePdfToPngRequest $request, $saveUuidFromCall)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $validateData = $request->validated();
        $uuidOwner = $this->pdfToPngService->convertAndSave($validateData, $saveUuidFromCall);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);
        Log::info('user successfully reply convert pdf to png: ' . $finalTime . ' detik, uuid: ' . $saveUuidFromCall);

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

        Log::info('user successfully downloads the pdf to png conversion result with png uuid: ', ['uuid' => $saveUuidFromCall]);

        return response(Storage::get($filePath))
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
