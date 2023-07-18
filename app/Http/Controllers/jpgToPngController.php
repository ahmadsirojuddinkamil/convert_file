<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJpgToPngRequest;
use App\Models\Png;
use App\Services\JpgToPngService;

class JpgToPngController extends Controller
{
    public function index()
    {
        return view('pages.convert.jpgToPng.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Png::FindPngByUuid($saveUuidShowFromRoute)->latest()->get();

        if (!$findAndGetDataFile) {
            abort(404);
        } else {
            return view('pages.convert.jpgToPng.show', compact('findAndGetDataFile'));
        }
    }

    public function create(CreateJpgToPngRequest $request, JpgToPngService $jpgToPngService)
    {
        $validateData = $request->validated();

        $dataFile = $jpgToPngService->convertAndSave($validateData['file'], $request->uuid);

        return redirect('/jpg_to_png/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($saveUuidDownloadFromRoute)
    {
        $png = Png::FindPngByUuid($saveUuidDownloadFromRoute)->firstOrFail();

        $box = 'storage/' . $png->file;
        $fileName = pathinfo($png->name, PATHINFO_FILENAME) . '.png';

        return response()->download($box, $fileName);
    }

    public function delete($saveUuidDeleteFromRoute, JpgToPngService $jpgToPngService)
    {
        return $jpgToPngService->deleteFilePng($saveUuidDeleteFromRoute);
    }
}
