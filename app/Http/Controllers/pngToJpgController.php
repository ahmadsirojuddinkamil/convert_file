<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePngToJpgRequest;
use App\Models\Jpg;
use App\Services\PngToJpgService;

class pngToJpgController extends Controller
{
    public function index()
    {
        return view('pages.convert.pngToJpg.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Jpg::FindJpgByUuid($saveUuidShowFromRoute)->latest()->get();

        if (!$findAndGetDataFile) {
            abort(404);
        } else {
            return view('pages.convert.pngToJpg.show', compact('findAndGetDataFile'));
        }
    }

    public function create(CreatePngToJpgRequest $request, PngToJpgService $pngToJpgService)
    {
        $validateData = $request->validated();

        $dataFile = $pngToJpgService->convertAndSave($validateData['file'], $request->uuid);

        return redirect('/png_to_jpg/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function reply(CreatePngToJpgRequest $request, PngToJpgService $pngToJpgService, $save_uuid_reply_from_route)
    {
        $validateData = $request->validated();

        $dataFile = $pngToJpgService->convertAndSave($validateData['file'], $save_uuid_reply_from_route);

        return redirect('/png_to_jpg/'. $dataFile['uuid'] . '/file')->with([
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
