<?php

namespace Modules\Pdf\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Http\Requests\CreatePdfToJpgRequest;
use Modules\Pdf\App\Models\Pdf;
use Modules\Pdf\App\Services\PdfToJpgService;

class PdfToJpgController extends Controller
{
    protected $pdfToJpgService;
    protected $validationService;

    public function __construct(PdfToJpgService $pdfToJpgService, ValidationService $validationService)
    {
        $this->pdfToJpgService = $pdfToJpgService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        $title = 'File Convert - Pdf To Jpg';
        $typeConvert = 'PDF to JPG Converter';

        return view('pdf::layouts.pdfToJpg.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePdfToJpgRequest $request)
    {
        $validateData = $request->validated();

        $uuidOwner = $this->pdfToJpgService->convertAndSave($validateData);

        return redirect('/pdf-to-jpg/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File pdf berhasil di convert ke jpg!'
        ]);
    }

    public function show($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $pdfFiles = Pdf::with('jpgs')->where('owner', $saveUuidFromCall)->first();

        if (!$pdfFiles) {
            return redirect('/')->with('error', 'Data jpg anda tidak ditemukan!');
        }

        $jpgFiles = $pdfFiles->jpgs->sortByDesc('created_at');

        $title = 'File Convert - Pdf To Jpg';
        $typeConvert = 'PDF to JPG Converter';

        return view('pdf::layouts.pdfToJpg.show', compact('jpgFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePdfToJpgRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->pdfToJpgService->convertAndSave($validateData, $saveUuidFromCall);

        return redirect('/pdf-to-jpg/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File pdf berhasil di convert ke jpg!'
        ]);
    }

    public function download($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $result = Jpg::where('uuid', $saveUuidFromCall)->first();

        if (!$result) {
            return redirect('/')->with('error', 'Data jpg anda tidak ditemukan!');
        }

        $filePath = 'public/' . $result->file;
        $fileName = pathinfo($result->name, PATHINFO_FILENAME) . '.jpg';

        return response(Storage::get($filePath))
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
