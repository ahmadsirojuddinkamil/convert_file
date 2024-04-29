<?php

namespace Modules\Png\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TimeService;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Http\Requests\CreatePngToJpgRequest;
use Modules\Png\App\Models\Png;
use Modules\Png\App\Services\PngToJpgService;

class PngToJpgController extends Controller
{
    protected $pngToJpgService;
    protected $validationService;
    protected $timeService;

    public function __construct(PngToJpgService $pngToJpgService, ValidationService $validationService, TimeService $timeService)
    {
        $this->pngToJpgService = $pngToJpgService;
        $this->validationService = $validationService;
        $this->timeService = $timeService;
    }

    public function index()
    {
        $title = 'File Convert - Png To Jpg';
        $typeConvert = 'PNG to JPG Converter';
        return view('png::layouts.pngToJpg.index', compact('title', 'typeConvert'));
    }

    public function create(CreatePngToJpgRequest $request)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $uuidOwner = $this->pngToJpgService->convertAndSave($validateData);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);
        Log::info('user successfully convert png to jpg: ' . $finalTime . ' detik');

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

        Log::info('user successfully viewed png to jpg data with uuid png: ', ['uuid' => $saveUuidFromCall]);

        return view('png::layouts.pngToJpg.show', compact('jpgFiles', 'title', 'typeConvert'));
    }

    public function reply(CreatePngToJpgRequest $request, $saveUuidFromCall)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->pngToJpgService->convertAndSave($validateData, $saveUuidFromCall);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);
        Log::info('user successfully reply convert png to jpg: ' . $finalTime . ' detik, uuid: ' . $saveUuidFromCall);

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

        Log::info('user successfully downloads the png to jpg conversion result with jpg uuid: ', ['uuid' => $saveUuidFromCall]);

        return response(Storage::get($filePath))
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
