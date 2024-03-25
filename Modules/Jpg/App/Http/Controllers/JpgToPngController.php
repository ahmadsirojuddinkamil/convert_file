<?php

namespace Modules\Jpg\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Http\Requests\CreateJpgToPngRequest;
use Modules\Jpg\App\Models\Jpg;
use Modules\Jpg\App\Services\JpgToPngService;
use Modules\Png\App\Models\Png;

class jpgToPngController extends Controller
{
    protected $jpgToPngService;
    protected $validationService;

    public function __construct(JpgToPngService $jpgToPngService, ValidationService $validationService)
    {
        $this->jpgToPngService = $jpgToPngService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        $title = 'File Convert - Jpg To Png';
        $typeConvert = 'JPG to PNG Converter';

        return view('jpg::layouts.jpgToPng.index', compact('title', 'typeConvert'));
    }

    public function create(CreateJpgToPngRequest $request)
    {
        $validateData = $request->validated();

        $uuidOwner = $this->jpgToPngService->convertAndSave($validateData);

        return redirect('/jpg-to-png/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File jpg berhasil di convert ke png!'
        ]);
    }

    public function show($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $jpgFiles = Jpg::with('pngs')->where('owner', $saveUuidFromCall)->first();

        if (!$jpgFiles) {
            return redirect('/')->with('error', 'Data png anda tidak ditemukan!');
        }

        $pngFiles = $jpgFiles->pngs->sortByDesc('created_at');

        $title = 'File Convert - Jpg To Png';
        $typeConvert = 'JPG to PNG Converter';

        return view('jpg::layouts.jpgToPng.show', compact('pngFiles', 'title', 'typeConvert'));
    }

    public function reply(CreateJpgToPngRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->jpgToPngService->convertAndSave($validateData, $saveUuidFromCall);

        return redirect('/jpg-to-png/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File jpg berhasil di convert ke png!'
        ]);
    }

    public function download($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $result = Png::FindPngByUuid($saveUuidFromCall)->first();

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
