<?php

namespace Modules\Jpg\App\Http\Controllers;

use Modules\Utility\App\Services\{TimeService, ValidationService};
use Modules\Jpg\App\Http\Requests\CreateJpgToPngRequest;
use Modules\Jpg\App\Services\JpgToPngService;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Jpg\App\Models\Jpg;
use Modules\Png\App\Models\Png;

class jpgToPngController extends Controller
{
    protected $jpgToPngService;
    protected $validationService;
    protected $timeService;

    public function __construct(JpgToPngService $jpgToPngService, ValidationService $validationService, TimeService $timeService)
    {
        $this->jpgToPngService = $jpgToPngService;
        $this->validationService = $validationService;
        $this->timeService = $timeService;
    }

    public function index()
    {
        $title = 'File Convert - Jpg To Png';
        $typeConvert = 'JPG to PNG Converter';

        return view('jpg::layouts.jpgToPng.index', compact('title', 'typeConvert'));
    }

    public function create(CreateJpgToPngRequest $request)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $uuidOwner = $this->jpgToPngService->convertAndSave($validateData);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);

        Log::info('user successfully convert jpg to png: ' . $finalTime . ' detik');

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

        Log::info('user successfully viewed jpg to png data with uuid jpg: ', ['uuid' => $saveUuidFromCall]);

        return view('jpg::layouts.jpgToPng.show', compact('pngFiles', 'title', 'typeConvert'));
    }

    public function reply(CreateJpgToPngRequest $request, $saveUuidFromCall)
    {
        $startTime = $this->timeService->startCalculateProcessTime();

        $validateData = $request->validated();
        $validateUuid = $this->validationService->validationUuid($saveUuidFromCall);

        if ($validateUuid === false) {
            return redirect('/')->with('error', 'Data anda tidak valid!');
        }

        $uuidOwner = $this->jpgToPngService->convertAndSave($validateData, $saveUuidFromCall);

        $finalTime = $this->timeService->endCalculateProcessTime($startTime);

        Log::info('user successfully reply convert jpg to png: ' . $finalTime . ' detik, uuid: ' . $saveUuidFromCall);

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

        Log::info('user successfully downloads the jpg to png conversion result with png uuid: ', ['uuid' => $saveUuidFromCall]);

        return response(Storage::get($filePath))
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
