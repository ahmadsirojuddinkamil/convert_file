<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePdfToJpgRequest;
use App\Models\Jpg;
use App\Services\PdfToJpgService;

class pdfToJpgController extends Controller
{
    public function index()
    {
        return view('pages.convert.pdfToJpg.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Jpg::FindJpgByUuid($saveUuidShowFromRoute)->latest()->get();

        if ($findAndGetDataFile->isEmpty()) {
            abort(404);
        } else {
            return view('pages.convert.pdfToJpg.show', compact('findAndGetDataFile'));
        }
    }

    public function create(CreatePdfToJpgRequest $request, PdfToJpgService $pdfToJpgService)
    {
        $validateData = $request->validated();

        $dataFile = $pdfToJpgService->convertAndSave($validateData['file'], $request->uuid);

        return redirect('/pdf_to_jpg/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function reply(CreatePdfToJpgRequest $request, PdfToJpgService $jpgToPdfService, $save_uuid_reply_from_route)
    {
        $validateData = $request->validated();

        $dataFile = $jpgToPdfService->convertAndSave($validateData['file'], $save_uuid_reply_from_route);

        return redirect('/pdf_to_jpg/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($saveUuidDownloadFromRoute)
    {
        $jpg = Jpg::FindJpgByUniqueId($saveUuidDownloadFromRoute)->firstOrFail();

        $box = 'storage/' . $jpg->file;
        $fileName = pathinfo($jpg->name, PATHINFO_FILENAME) . '.jpg';

        return response()->download($box, $fileName);
    }
}
