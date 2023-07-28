<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePdfToPngRequest;
use App\Models\Png;
use App\Services\PdfToPngService;

class pdfToPngController extends Controller
{
    public function index()
    {
        return view('pages.convert.pdfToPng.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Png::FindPngByUuid($saveUuidShowFromRoute)->latest()->get();

        if ($findAndGetDataFile->isEmpty()) {
            abort(404);
        } else {
            return view('pages.convert.pdfToPng.show', compact('findAndGetDataFile'));
        }
    }

    public function create(CreatePdfToPngRequest $request, PdfToPngService $pdfToPngService)
    {
        $validateData = $request->validated();

        $dataFile = $pdfToPngService->convertAndSave($validateData['file'], $request->uuid);

        return redirect('/pdf_to_png/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function reply(CreatePdfToPngRequest $request, PdfToPngService $pdfToPngService, $save_uuid_reply_from_route)
    {
        $validateData = $request->validated();

        $dataFile = $pdfToPngService->convertAndSave($validateData['file'], $save_uuid_reply_from_route);

        return redirect('/pdf_to_png/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($saveUuidDownloadFromRoute)
    {
        $jpg = Png::FindPngByUniqueId($saveUuidDownloadFromRoute)->firstOrFail();

        $box = 'storage/' . $jpg->file;
        $fileName = pathinfo($jpg->name, PATHINFO_FILENAME) . '.png';

        return response()->download($box, $fileName);
    }
}
