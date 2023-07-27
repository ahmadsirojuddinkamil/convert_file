<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePngToPdfRequest;
use App\Models\Pdf;
use App\Services\PngToPdfService;

class pngToPdfController extends Controller
{
    public function index()
    {
        return view('pages.convert.pngToPdf.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Pdf::FindPdfByUuid($saveUuidShowFromRoute)->latest()->get();

        if ($findAndGetDataFile->isEmpty()) {
            abort(404);
        } else {
            return view('pages.convert.pngToPdf.show', compact('findAndGetDataFile'));
        }
    }

    public function create(CreatePngToPdfRequest $request, PngToPdfService $pngToPdfService)
    {
        $validateData = $request->validated();

        $dataFile = $pngToPdfService->convertAndSave($validateData['file'], $request->uuid);

        return redirect('/png_to_pdf/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function reply(CreatePngToPdfRequest $request, PngToPdfService $pngToPdfService, $save_uuid_reply_from_route)
    {
        $validateData = $request->validated();

        $dataFile = $pngToPdfService->convertAndSave($validateData['file'], $save_uuid_reply_from_route);

        return redirect('/png_to_pdf/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($saveUuidDownloadFromRoute)
    {
        $pdf = Pdf::FindPdfByUniqueId($saveUuidDownloadFromRoute)->firstOrFail();

        $box = 'storage/' . $pdf->file;
        $fileName = pathinfo($pdf->name, PATHINFO_FILENAME) . '.pdf';

        return response()->download($box, $fileName);
    }
}
