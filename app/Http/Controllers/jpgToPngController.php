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

        if ($findAndGetDataFile->isEmpty()) {
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

    public function reply(CreateJpgToPngRequest $request, JpgToPngService $jpgToPngService, $save_uuid_reply_from_route)
    {
        $validateData = $request->validated();

        $dataFile = $jpgToPngService->convertAndSave($validateData['file'], $save_uuid_reply_from_route);

        return redirect('/jpg_to_png/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($saveUuidDownloadFromRoute)
    {
        $png = Png::FindPngByUniqueId($saveUuidDownloadFromRoute)->firstOrFail();

        $box = 'storage/' . $png->file;
        $fileName = pathinfo($png->name, PATHINFO_FILENAME) . '.png';

        return response()->download($box, $fileName);
    }
}
