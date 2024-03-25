<?php

namespace Modules\Png\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Http\Requests\CreatePngToPdfRequest;
use Modules\Png\App\Models\Png;
use Modules\Png\App\Services\PngToPdfService;

class PngToPdfController extends Controller
{
    protected $pngToPdfService;
    protected $validationService;

    public function __construct(PngToPdfService $pngToPdfService, ValidationService $validationService)
    {
        $this->pngToPdfService = $pngToPdfService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        $title = 'File Convert - Png To Pdf';
        $typeConvert = 'PNG to PDF Converter';

        return view('png::layouts.pngToPdf.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePngToPdfRequest $request)
    {
        $validateData = $request->validated();

        $uuidOwner = $this->pngToPdfService->convertAndSave($validateData['file']);

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

        return view('png::layouts.pngToPdf.show', compact('pdfFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePngToPdfRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->pngToPdfService->convertAndSave($validateData['file'], $saveUuidFromCall);

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

        return response(Storage::get($filePath))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
