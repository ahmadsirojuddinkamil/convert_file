<?php

namespace Modules\Png\App\Http\Controllers;

use Modules\Utility\App\Services\{TimeService, ValidationService};
use Modules\Png\App\Http\Requests\CreatePngToPdfRequest;
use Modules\Png\App\Services\PngToPdfService;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;

class PngToPdfController extends Controller
{
    protected $pngToPdfService;
    protected $validationService;
    protected $timeService;

    public function __construct(PngToPdfService $pngToPdfService, ValidationService $validationService, TimeService $timeService)
    {
        $this->pngToPdfService = $pngToPdfService;
        $this->validationService = $validationService;
        $this->timeService = $timeService;
    }

    public function index()
    {
        $title = 'File Convert - Png To Pdf';
        $typeConvert = 'PNG to PDF Converter';

        return view('png::layouts.pngToPdf.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePngToPdfRequest $request)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $uuidOwner = $this->pngToPdfService->convertAndSave($validateData['file']);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);
        Log::info('user successfully convert png to pdf: ' . $finalTime . ' detik');

        return redirect('/png-to-pdf/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File png berhasil di convert ke pdf!'
        ]);
    }

    public function show($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $pngFiles = Png::with('pdfs')->where('owner', $saveUuidFromCall)->first();

        if (!$pngFiles) {
            return redirect('/')->with('error', 'Data pdf anda tidak ditemukan!');
        }

        $pdfFiles = $pngFiles->pdfs->sortByDesc('created_at');

        $title = 'File Convert - Png To Pdf';
        $typeConvert = 'PNG to PDF Converter';

        Log::info('user successfully viewed png to pdf data with uuid png: ', ['uuid' => $saveUuidFromCall]);

        return view('png::layouts.pngToPdf.show', compact('pdfFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePngToPdfRequest $request, $saveUuidFromCall)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->pngToPdfService->convertAndSave($validateData['file'], $saveUuidFromCall);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);
        Log::info('user successfully reply convert png to pdf: ' . $finalTime . ' detik, uuid: ' . $saveUuidFromCall);

        return redirect('/png-to-pdf/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File png berhasil di convert ke pdf!'
        ]);
    }

    public function download($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $result = Pdf::where('uuid', $saveUuidFromCall)->first();

        if (!$result) {
            return redirect('/')->with('error', 'Data pdf anda tidak ditemukan!');
        }

        $filePath = 'public/' . $result->file;
        $fileName = pathinfo($result->name, PATHINFO_FILENAME) . '.pdf';

        Log::info('user successfully downloads the png to pdf conversion result with pdf uuid: ', ['uuid' => $saveUuidFromCall]);

        return response(Storage::get($filePath))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
