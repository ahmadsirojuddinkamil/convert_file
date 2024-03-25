<?php

namespace Modules\Png\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Http\Requests\CreatePngToJpgRequest;
use Modules\Png\App\Models\Png;
use Modules\Png\App\Services\PngToJpgService;

class PngToJpgController extends Controller
{
    protected $pngToJpgService;
    protected $validationService;

    public function __construct(PngToJpgService $pngToJpgService, ValidationService $validationService)
    {
        $this->pngToJpgService = $pngToJpgService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        $title = 'File Convert - Png To Jpg';
        $typeConvert = 'PNG to JPG Converter';
        return view('png::layouts.pngToJpg.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePngToJpgRequest $request)
    {
        $validateData = $request->validated();

        $uuidOwner = $this->pngToJpgService->convertAndSave($validateData);

        return redirect('/png-to-jpg/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File png berhasil di convert ke jpg!'
        ]);
    }

    public function show($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $pngFiles = Png::with('jpgs')->where('owner', $saveUuidFromCall)->first();

        if (!$pngFiles) {
            return redirect('/')->with('error', 'Data jpg anda tidak ditemukan!');
        }

        $jpgFiles = $pngFiles->jpgs->sortByDesc('created_at');

        $title = 'File Convert - Png To Jpg';
        $typeConvert = 'PNG to JPG Converter';

        return view('png::layouts.pngToJpg.show', compact('jpgFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePngToJpgRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->pngToJpgService->convertAndSave($validateData, $saveUuidFromCall);

        return redirect('/png-to-jpg/' . $uuidOwner)->with([
            'uuid' => $uuidOwner,
            'success' => 'File png berhasil di convert ke jpg!'
        ]);
    }

    public function download($saveUuidFromCall)
    {
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $result = Jpg::FindJpgByUuid($saveUuidFromCall)->first();

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
