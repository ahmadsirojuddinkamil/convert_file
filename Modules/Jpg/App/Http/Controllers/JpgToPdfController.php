<?php

namespace Modules\Jpg\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Http\Requests\CreateJpgToPdfRequest;
use Modules\Jpg\App\Models\Jpg;
use Modules\Jpg\App\Services\JpgToPdfService;
use Modules\Pdf\App\Models\Pdf;

class JpgToPdfController extends Controller
{
    protected $jpgToPdfService;
    protected $validationService;

    public function __construct(JpgToPdfService $jpgToPdfService, ValidationService $validationService)
    {
        $this->jpgToPdfService = $jpgToPdfService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        $title = 'File Convert - Jpg To Pdf';
        $typeConvert = 'JPG to PDF Converter';
        return view('jpg::layouts.jpgToPdf.index', compact('title', 'typeConvert'));
    }

    public function create(CreateJpgToPdfRequest $request)
    {
        $validateData = $request->validated();

        $uuidOwner = $this->jpgToPdfService->convertAndSave($validateData['file']);

        return redirect('/jpg-to-pdf/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File jpg berhasil di convert ke pdf!'
        ]);
    }

    public function show($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $pngFiles = Jpg::with('pdfs')->where('owner', $saveUuidFromCall)->first();

        if (!$pngFiles) {
            return redirect('/')->with('error', 'Data pdf anda tidak ditemukan!');
        }

        $pdfFiles = $pngFiles->pdfs->sortByDesc('created_at');

        $title = 'File Convert - Jpg To Pdf';
        $typeConvert = 'JPG to PDF Converter';

        return view('jpg::layouts.jpgToPdf.show', compact('pdfFiles', 'title', 'typeConvert'));
    }

    public function reply(CreateJpgToPdfRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->jpgToPdfService->convertAndSave($validateData['file'], $saveUuidFromCall);

        return redirect('/jpg-to-pdf/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File jpg berhasil di convert ke pdf!'
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
