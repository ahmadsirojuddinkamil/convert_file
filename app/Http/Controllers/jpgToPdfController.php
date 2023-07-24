<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJpgToPdfRequest;
use App\Models\Pdf;
use App\Services\JpgToPdfService;

class jpgToPdfController extends Controller
{
    public function index()
    {
        return view('pages.convert.jpgToPdf.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Pdf::FindPdfByUuid($saveUuidShowFromRoute)->latest()->get();

        if ($findAndGetDataFile->isEmpty()) {
            abort(404);
        } else {
            return view('pages.convert.jpgToPdf.show', compact('findAndGetDataFile'));
        }
    }

    public function create(CreateJpgToPdfRequest $request, JpgToPdfService $jpgToPdfService)
    {
        $validateData = $request->validated();

        $dataFile = $jpgToPdfService->convertAndSave($validateData['file'], $request->uuid);

        return redirect('/jpg_to_pdf/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function reply(CreateJpgToPdfRequest $request, JpgToPdfService $jpgToPdfService, $save_uuid_reply_from_route)
    {
        $validateData = $request->validated();

        $dataFile = $jpgToPdfService->convertAndSave($validateData['file'], $save_uuid_reply_from_route);

        return redirect('/jpg_to_pdf/'. $dataFile['uuid'] . '/file')->with([
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
